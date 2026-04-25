<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\CategoryRepository;
use App\Models\Category;

class CategoryController extends Controller
{
    private CategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->categoryRepo = new CategoryRepository();
    }

    /**
     * Display all categories
     */
    public function index(): void
    {
        $categories = $this->categoryRepo->getAll();

        $this->view('categories', [
            'title' => 'Danh mục sản phẩm',
            'categories' => $categories,
        ]);
    }
}
