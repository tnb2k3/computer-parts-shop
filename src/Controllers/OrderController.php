<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\CartRepository;
use App\Models\Order;

class OrderController extends Controller
{
    private OrderRepository $orderRepo;
    private CartRepository $cartRepo;

    public function __construct()
    {
        $this->orderRepo = new OrderRepository();
        $this->cartRepo = new CartRepository();
    }

    /**
     * Display checkout form
     */
    public function checkout(): void
    {
        if ($this->cartRepo->isEmpty()) {
            $this->redirect('/cart');
            return;
        }

        $cartItems = $this->cartRepo->getCart();
        $total = $this->cartRepo->getTotal();

        // Pre-fill if user is logged in
        $user = $this->getSession('user');

        $this->view('checkout', [
            'title' => 'Thanh toán',
            'cartItems' => $cartItems,
            'total' => $total,
            'user' => $user,
        ]);
    }

    /**
     * Process order
     */
    public function place(): void
    {
        if (!$this->isPost() || $this->cartRepo->isEmpty()) {
            $this->redirect('/cart');
            return;
        }

        // Get form data
        $customerName = $this->getPost('customer_name');
        $customerEmail = $this->getPost('customer_email');
        $customerPhone = $this->getPost('customer_phone');
        $shippingAddress = $this->getPost('shipping_address');
        $notes = $this->getPost('notes');
        $paymentMethod = $this->getPost('payment_method') ?? 'cod';

        // Create order
        $order = new Order();
        $order->user_id = $this->getSession('user')['id'] ?? 0;
        $order->total = $this->cartRepo->getTotal();
        $order->status = 'pending';
        $order->customer_name = $customerName;
        $order->customer_email = $customerEmail;
        $order->customer_phone = $customerPhone;
        $order->shipping_address = $shippingAddress;
        $order->notes = $notes;
        $order->payment_method = $paymentMethod;
        $order->payment_status = $paymentMethod === 'cod' ? 'pending' : 'pending';

        // Prepare order items
        $cartItems = $this->cartRepo->getCart();
        $orderItems = [];
        foreach ($cartItems as $item) {
            $orderItems[] = [
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->getSubtotal(),
            ];
        }

        // Create order
        $orderId = $this->orderRepo->create($order, $orderItems);

        if ($orderId) {
            // Clear cart
            $this->cartRepo->clearCart();
            
            // Redirect based on payment method
            if ($paymentMethod === 'qr_bank') {
                $this->redirect('/order/qr-payment/' . $orderId);
            } else {
                $this->redirect('/order/success/' . $orderId);
            }
        } else {
            // Error handling
            $this->redirect('/checkout?error=1');
        }
    }

    /**
     * QR Payment page
     */
    public function qrPayment(string $orderId): void
    {
        $order = $this->orderRepo->getById((int)$orderId);

        if (!$order || $order->payment_method !== 'qr_bank') {
            $this->redirect('/');
            return;
        }

        // Bank info for BIDV
        $bankId = 'BIDV';
        $accountNo = '5321205841';
        $accountName = 'TRAN NGOC BAO'; // Account holder name
        $amount = (int)$order->total;
        $description = 'DH' . $order->id; // Order reference

        // Generate VietQR URL
        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-compact2.png?amount={$amount}&addInfo=" . urlencode($description);

        $this->view('qr-payment', [
            'title' => 'Thanh toán QR Banking',
            'order' => $order,
            'qrUrl' => $qrUrl,
            'bankId' => $bankId,
            'accountNo' => $accountNo,
            'accountName' => $accountName,
            'description' => $description,
        ]);
    }

    /**
     * Confirm payment (user confirms they have paid)
     */
    public function confirmPayment(string $orderId): void
    {
        if (!$this->isPost()) {
            $this->redirect('/');
            return;
        }

        $order = $this->orderRepo->getById((int)$orderId);

        if (!$order) {
            $this->redirect('/');
            return;
        }

        // Update payment status to indicate user confirmed payment
        // In a real app, you would verify the payment via bank API
        $this->orderRepo->updatePaymentStatus((int)$orderId, 'paid');
        
        $this->redirect('/order/success/' . $orderId);
    }

    /**
     * Order success page
     */
    public function success(string $orderId): void
    {
        $order = $this->orderRepo->getById((int)$orderId);

        if (!$order) {
            $this->redirect('/');
            return;
        }

        $this->view('order-success', [
            'title' => 'Đặt hàng thành công',
            'order' => $order,
        ]);
    }

    /**
     * Order history (for logged in users)
     */
    public function history(): void
    {
        $user = $this->getSession('user');
        
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $orders = $this->orderRepo->getByUser($user['id']);

        $this->view('order-history', [
            'title' => 'Lịch sử đơn hàng',
            'orders' => $orders,
        ]);
    }
}
