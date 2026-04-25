<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\ReviewRepository;
use App\Repositories\ProductRepository;

class ReviewController extends Controller
{
    private ReviewRepository $reviewRepo;
    private ProductRepository $productRepo;

    public function __construct()
    {
        $this->reviewRepo = new ReviewRepository();
        $this->productRepo = new ProductRepository();
    }

    /**
     * Create a new review (POST only)
     */
    public function create(): void
    {
        // Check if user is logged in
        $userId = $this->getSession('user_id');
        if (!$userId) {
            $this->setSession('error', 'Bạn cần đăng nhập để đánh giá sản phẩm');
            $this->redirect('/login');
            return;
        }

        if (!$this->isPost()) {
            $this->redirect('/products');
            return;
        }

        $productId = (int)$this->getPost('product_id');
        $rating = (int)$this->getPost('rating');
        $comment = trim($this->getPost('comment'));

        // Validation
        $errors = [];
        
        if ($productId <= 0) {
            $errors[] = 'Sản phẩm không hợp lệ';
        }

        if ($rating < 1 || $rating > 5) {
            $errors[] = 'Đánh giá phải từ 1-5 sao';
        }

        if (empty($comment)) {
            $errors[] = 'Vui lòng nhập nhận xét';
        }

        // Check if product exists
        $product = $this->productRepo->getById($productId);
        if (!$product) {
            $errors[] = 'Sản phẩm không tồn tại';
        }

        // Check if user already reviewed this product
        if ($this->reviewRepo->userHasReviewed($userId, $productId)) {
            $errors[] = 'Bạn đã đánh giá sản phẩm này rồi';
        }

        if (!empty($errors)) {
            $this->setSession('error', implode(', ', $errors));
            $this->redirect('/product/' . $productId);
            return;
        }

        // Create review
        $reviewId = $this->reviewRepo->create([
            'product_id' => $productId,
            'user_id' => $userId,
            'rating' => $rating,
            'comment' => $comment,
        ]);

        if ($reviewId) {
            $this->setSession('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
        } else {
            $this->setSession('error', 'Có lỗi xảy ra. Vui lòng thử lại');
        }

        $this->redirect('/product/' . $productId);
    }
}
