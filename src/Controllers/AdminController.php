<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    private CategoryRepository $categoryRepo;
    private ProductRepository $productRepo;
    private OrderRepository $orderRepo;
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->categoryRepo = new CategoryRepository();
        $this->productRepo = new ProductRepository();
        $this->orderRepo = new OrderRepository();
        $this->userRepo = new UserRepository();
        
        // Check if user is admin
        $this->checkAdmin();
    }

    /**
     * Check if user is admin
     */
    private function checkAdmin(): void
    {
        $user = $this->getSession('user');
        
        if (!$user || $user['role'] !== 'admin') {
            $this->redirect('/login');
            exit;
        }
    }

    /**
     * Admin dashboard
     */
    public function dashboard(): void
    {
        $stats = $this->orderRepo->getStatistics();
        $recentOrders = array_slice($this->orderRepo->getAll(), 0, 5);
        
        // Revenue statistics
        $revenueByCategory = $this->orderRepo->getRevenueByCategory();
        
        // Get time period from query string
        $timePeriod = $this->getGet('period') ?: 'day';
        $revenueByTime = $this->orderRepo->getRevenueByTime($timePeriod);

        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'revenueByCategory' => $revenueByCategory,
            'revenueByTime' => $revenueByTime,
            'timePeriod' => $timePeriod,
            '_no_layout' => true,
        ]);
    }

    /**
     * Manage categories
     */
    public function categories(): void
    {
        $categories = $this->categoryRepo->getAll();

        $this->view('admin/categories', [
            'title' => 'Quản lý danh mục',
            'categories' => $categories,
            '_no_layout' => true,
        ]);
    }

    /**
     * Create/Edit category
     */
    public function categoryForm(): void
    {
        $categoryId = $this->getGet('id');
        $category = $categoryId ? $this->categoryRepo->getById((int)$categoryId) : null;

        if ($this->isPost()) {
            $cat = new Category();
            $cat->name = $this->getPost('name');
            $cat->description = $this->getPost('description');

            if ($categoryId) {
                $cat->id = (int)$categoryId;
                $this->categoryRepo->update($cat);
            } else {
                $this->categoryRepo->create($cat);
            }

            $this->redirect('/admin/categories');
            return;
        }

        $this->view('admin/category-form', [
            'title' => $categoryId ? 'Sửa danh mục' : 'Thêm danh mục',
            'category' => $category,
            '_no_layout' => true,
        ]);
    }

    /**
     * Delete category
     */
    public function deleteCategory(string $id): void
    {
        $this->categoryRepo->delete((int)$id);
        $this->redirect('/admin/categories');
    }

    /**
     * Manage products
     */
    public function products(): void
    {
        $products = $this->productRepo->getAll();

        $this->view('admin/products', [
            'title' => 'Quản lý sản phẩm',
            'products' => $products,
            '_no_layout' => true,
        ]);
    }

    /**
     * Create/Edit product
     */
    public function productForm(): void
    {
        $productId = $this->getGet('id');
        $product = $productId ? $this->productRepo->getById((int)$productId) : null;
        $categories = $this->categoryRepo->getAll();

        if ($this->isPost()) {
            $prod = new Product();
            $prod->category_id = (int)$this->getPost('category_id');
            $prod->name = $this->getPost('name');
            $prod->description = $this->getPost('description');
            $prod->price = (float)$this->getPost('price');
            $prod->stock = (int)$this->getPost('stock');
            
            // Handle file upload
            $uploadedImage = $this->handleImageUpload($product->image ?? null);
            if ($uploadedImage !== null) {
                $prod->image = $uploadedImage;
            } elseif ($product) {
                // Keep existing image if no new upload
                $prod->image = $product->image;
            }

            if ($productId) {
                $prod->id = (int)$productId;
                $this->productRepo->update($prod);
            } else {
                $this->productRepo->create($prod);
            }

            $this->redirect('/admin/products');
            return;
        }

        $this->view('admin/product-form', [
            'title' => $productId ? 'Sửa sản phẩm' : 'Thêm sản phẩm',
            'product' => $product,
            'categories' => $categories,
            '_no_layout' => true,
        ]);
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload(?string $currentImage): ?string
    {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['image'];
        
        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }
        
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            return null;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('product_') . '.' . $extension;
        
        // Upload directory
        $uploadDir = __DIR__ . '/../../public/images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $destination = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Delete old image if exists
            if ($currentImage && file_exists($uploadDir . $currentImage)) {
                unlink($uploadDir . $currentImage);
            }
            return $filename;
        }
        
        return null;
    }


    /**
     * Delete product
     */
    public function deleteProduct(string $id): void
    {
        $this->productRepo->delete((int)$id);
        $this->redirect('/admin/products');
    }

    /**
     * Manage orders
     */
    public function orders(): void
    {
        $orders = $this->orderRepo->getAll();

        $this->view('admin/orders', [
            'title' => 'Quản lý đơn hàng',
            'orders' => $orders,
            '_no_layout' => true,
        ]);
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/admin/orders');
            return;
        }

        $orderId = (int)$this->getPost('order_id');
        $status = $this->getPost('status');

        $this->orderRepo->updateStatus($orderId, $status);
        $this->redirect('/admin/orders');
    }

    /**
     * Manage users
     */
    public function users(): void
    {
        $users = $this->userRepo->getAll();

        $this->view('admin/users', [
            'title' => 'Quản lý tài khoản',
            'users' => $users,
            '_no_layout' => true,
        ]);
    }

    /**
     * Create/Edit user
     */
    public function userForm(): void
    {
        $userId = $this->getGet('id');
        $user = $userId ? $this->userRepo->getById((int)$userId) : null;
        $error = null;

        if ($this->isPost()) {
            $username = $this->getPost('username');
            $email = $this->getPost('email');
            $fullName = $this->getPost('full_name');
            $phone = $this->getPost('phone');
            $address = $this->getPost('address');
            $role = $this->getPost('role');

            if ($userId && $user) {
                // Update existing user
                $user->username = $username;
                $user->email = $email;
                $user->full_name = $fullName;
                $user->phone = $phone;
                $user->address = $address;
                $user->role = $role;

                $this->userRepo->updateByAdmin($user);
                $this->redirect('/admin/users');
                return;
            }

            // Note: Creating new user from admin not implemented
            // Users should register themselves
            $error = 'Không thể tạo user mới từ admin. Người dùng cần tự đăng ký.';
        }

        $this->view('admin/user-form', [
            'title' => $userId ? 'Chỉnh sửa tài khoản' : 'Thêm tài khoản',
            'editUser' => $user,
            'error' => $error,
            '_no_layout' => true,
        ]);
    }

    /**
     * Delete user
     */
    public function deleteUser(string $id): void
    {
        // Prevent admin from deleting themselves
        $currentUser = $this->getSession('user');
        if ($currentUser && (int)$currentUser['id'] === (int)$id) {
            $this->redirect('/admin/users');
            return;
        }

        $this->userRepo->delete((int)$id);
        $this->redirect('/admin/users');
    }

    /**
     * Manage reviews
     */
    public function reviews(): void
    {
        $reviewRepo = new \App\Repositories\ReviewRepository();
        $reviews = $reviewRepo->getAll();

        $this->view('admin/reviews', [
            'title' => 'Quản lý đánh giá',
            'reviews' => $reviews,
            '_no_layout' => true,
        ]);
    }

    /**
     * Delete review
     */
    public function deleteReview(string $id): void
    {
        $reviewRepo = new \App\Repositories\ReviewRepository();
        $reviewRepo->delete((int)$id);
        $this->redirect('/admin/reviews');
    }
}
