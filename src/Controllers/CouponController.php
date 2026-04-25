<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\CouponRepository;
use App\Repositories\CartRepository;

class CouponController extends Controller
{
    private CouponRepository $couponRepo;
    private CartRepository $cartRepo;

    public function __construct()
    {
        $this->couponRepo = new CouponRepository();
        $this->cartRepo = new CartRepository();
    }

    /**
     * Apply coupon to cart
     */
    public function apply(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/cart');
            return;
        }

        $code = strtoupper(trim($this->getPost('coupon_code')));
        
        if (empty($code)) {
            $this->setSession('error', 'Vui lòng nhập mã giảm giá');
            $this->redirect('/cart');
            return;
        }

        // Get cart total
        $cartTotal = $this->cartRepo->getTotal();
        
        if ($cartTotal <= 0) {
            $this->setSession('error', 'Giỏ hàng của bạn đang trống');
            $this->redirect('/cart');
            return;
        }

        // Get user ID (can be null for guest checkout, but some coupons might require login)
        $userId = $this->getSession('user_id');

        // Validate coupon
        $result = $this->couponRepo->validateCoupon($code, $userId, $cartTotal);

        if (!$result['valid']) {
            $this->setSession('error', $result['message']);
            $this->redirect('/cart');
            return;
        }

        // Store coupon info in session
        $coupon = $result['coupon'];
        $this->setSession('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount_amount' => $result['discount_amount'],
            'description' => $coupon->description,
        ]);

        $this->setSession('success', $result['message'] . ' - Giảm ' . number_format($result['discount_amount'], 0, ',', '.') . ' ₫');
        $this->redirect('/cart');
    }

    /**
     * Remove coupon from cart
     */
    public function remove(): void
    {
        unset($_SESSION['coupon']);
        $this->setSession('success', 'Đã xóa mã giảm giá');
        $this->redirect('/cart');
    }
}
