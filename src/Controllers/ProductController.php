<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

class ProductController extends Controller
{
    private ProductRepository $productRepo;
    private CategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->categoryRepo = new CategoryRepository();
    }

    /**
     * Display all products
     */
    public function index(): void
    {
        $categoryId = $this->getGet('category');
        $search = $this->getGet('search');

        if ($categoryId) {
            $products = $this->productRepo->getByCategory((int)$categoryId);
            $category = $this->categoryRepo->getById((int)$categoryId);
            $title = 'Sản phẩm - ' . ($category ? $category->name : 'Tất cả');
        } elseif ($search) {
            $products = $this->productRepo->search($search);
            $title = 'Tìm kiếm: ' . $search;
        } else {
            $products = $this->productRepo->getAll();
            $title = 'Tất cả sản phẩm';
        }

        $categories = $this->categoryRepo->getAll();

        $this->view('products', [
            'title' => $title,
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $categoryId ?? null,
            'searchKeyword' => $search ?? '',
        ]);
    }

    /**
     * Display single product detail
     */
    public function detail(string $id): void
    {
        $product = $this->productRepo->getById((int)$id);

        if (!$product) {
            $this->redirect('/products');
            return;
        }

        // Get related products from same category
        $relatedProducts = $this->productRepo->getByCategory($product->category_id);
        // Remove current product and limit to 4
        $relatedProducts = array_filter($relatedProducts, fn($p) => $p->id != $product->id);
        $relatedProducts = array_slice($relatedProducts, 0, 4);

        // Get reviews data
        $reviewRepo = new \App\Repositories\ReviewRepository();
        $reviews = $reviewRepo->getByProductId((int)$id);
        $averageRating = $reviewRepo->getAverageRating((int)$id);
        $ratingCounts = $reviewRepo->getRatingCounts((int)$id);
        $reviewCount = $reviewRepo->getReviewCount((int)$id);
        
        // Check if current user has reviewed this product
        $userId = $this->getSession('user_id');
        $userHasReviewed = $userId ? $reviewRepo->userHasReviewed($userId, (int)$id) : false;

        $this->view('product-detail', [
            'title' => $product->name,
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'ratingCounts' => $ratingCounts,
            'reviewCount' => $reviewCount,
            'userHasReviewed' => $userHasReviewed,
        ]);
    }
}
