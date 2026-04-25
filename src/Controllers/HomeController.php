<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

class HomeController extends Controller
{
    private ProductRepository $productRepo;
    private CategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->productRepo = new ProductRepository();
        $this->categoryRepo = new CategoryRepository();
    }

    /**
     * Display homepage
     */
    public function index(): void
    {
        $featuredProducts = $this->productRepo->getFeatured(8);
        $categories = $this->categoryRepo->getAll();

        $this->view('home', [
            'title' => 'Trang chủ - Shop Linh Kiện Máy Tính',
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
        ]);
    }
}
