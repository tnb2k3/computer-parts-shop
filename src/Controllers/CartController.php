<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;

class CartController extends Controller
{
    private CartRepository $cartRepo;
    private ProductRepository $productRepo;

    public function __construct()
    {
        $this->cartRepo = new CartRepository();
        $this->productRepo = new ProductRepository();
    }

    /**
     * Display cart
     */
    public function index(): void
    {
        $cartItems = $this->cartRepo->getCart();
        $subtotal = $this->cartRepo->getTotal();
        
        // Get coupon data from session
        $coupon = $this->getSession('coupon');
        $discountAmount = 0;
        
        if ($coupon) {
            $discountAmount = $coupon['discount_amount'] ?? 0;
            
            // Re-validate coupon based on current cart total
            $couponRepo = new \App\Repositories\CouponRepository();
            $userId = $this->getSession('user_id');
            $result = $couponRepo->validateCoupon($coupon['code'], $userId, $subtotal);
            
            if (!$result['valid']) {
                // Coupon no longer valid, remove it
                unset($_SESSION['coupon']);
                $coupon = null;
                $discountAmount = 0;
                $this->setSession('error', 'Mã giảm giá không còn hợp lệ: ' . $result['message']);
            } else {
                // Update discount amount based on current total
                $discountAmount = $result['discount_amount'];
                $_SESSION['coupon']['discount_amount'] = $discountAmount;
            }
        }
        
        $total = $subtotal - $discountAmount;
        
        // Get available coupons for display
        $couponRepo = $couponRepo ?? new \App\Repositories\CouponRepository();
        $availableCoupons = $couponRepo->getActiveCoupons();

        $this->view('cart', [
            'title' => 'Giỏ hàng',
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'coupon' => $coupon,
            'discountAmount' => $discountAmount,
            'total' => $total,
            'availableCoupons' => $availableCoupons,
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/cart');
            return;
        }

        $productId = (int)$this->getPost('product_id');
        $quantity = (int)$this->getPost('quantity', 1);

        $product = $this->productRepo->getById($productId);

        if ($product && $product->isInStock()) {
            $this->cartRepo->addItem(
                $product->id,
                $product->name,
                $product->price,
                $quantity,
                $product->image,
                $product->img_url
            );
        }

        $this->redirect('/cart');
    }

    /**
     * Update cart item quantity
     */
    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/cart');
            return;
        }

        $productId = (int)$this->getPost('product_id');
        $quantity = (int)$this->getPost('quantity');

        if ($quantity > 0) {
            $this->cartRepo->updateQuantity($productId, $quantity);
        } else {
            $this->cartRepo->removeItem($productId);
        }

        $this->redirect('/cart');
    }

    /**
     * Remove item from cart
     */
    public function remove(string $productId): void
    {
        $this->cartRepo->removeItem((int)$productId);
        $this->redirect('/cart');
    }

    /**
     * Clear cart
     */
    public function clear(): void
    {
        $this->cartRepo->clearCart();
        $this->redirect('/cart');
    }
}
