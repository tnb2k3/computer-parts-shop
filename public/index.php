<?php

// Start session
session_start();

// Load Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

// Create router instance
$router = new Router();

// Define routes

// Home
$router->get('/', 'HomeController', 'index');

// Products
$router->get('/products', 'ProductController', 'index');
$router->get('/product/:id', 'ProductController', 'detail');

// Categories
$router->get('/categories', 'CategoryController', 'index');

// Cart
$router->get('/cart', 'CartController', 'index');
$router->post('/cart/add', 'CartController', 'add');
$router->post('/cart/update', 'CartController', 'update');
$router->get('/cart/remove/:id', 'CartController', 'remove');
$router->get('/cart/clear', 'CartController', 'clear');

// Checkout & Orders
$router->get('/checkout', 'OrderController', 'checkout');
$router->post('/order/place', 'OrderController', 'place');
$router->get('/order/success/:id', 'OrderController', 'success');
$router->get('/order/history', 'OrderController', 'history');
$router->get('/order/qr-payment/:id', 'OrderController', 'qrPayment');
$router->post('/order/confirm-payment/:id', 'OrderController', 'confirmPayment');

// User
$router->get('/login', 'UserController', 'login');
$router->post('/login', 'UserController', 'loginPost');
$router->get('/register', 'UserController', 'register');
$router->post('/register', 'UserController', 'registerPost');
$router->get('/logout', 'UserController', 'logout');
$router->get('/profile', 'UserController', 'profile');
$router->post('/profile/update', 'UserController', 'updateProfile');
$router->post('/profile/change-password', 'UserController', 'changePassword');

// Email verification
$router->get('/verify-pending', 'UserController', 'verifyPending');
$router->post('/verify-otp', 'UserController', 'verifyEmail');
$router->get('/verify/:token', 'UserController', 'verifyEmail');
$router->post('/resend-verification', 'UserController', 'resendVerification');

// Reviews
$router->post('/review/create', 'ReviewController', 'create');

// Coupons
$router->post('/cart/apply-coupon', 'CouponController', 'apply');
$router->get('/cart/remove-coupon', 'CouponController', 'remove');

// Admin
$router->get('/admin', 'AdminController', 'dashboard');
$router->get('/admin/dashboard', 'AdminController', 'dashboard');

$router->get('/admin/categories', 'AdminController', 'categories');
$router->get('/admin/category/form', 'AdminController', 'categoryForm');
$router->post('/admin/category/form', 'AdminController', 'categoryForm');
$router->get('/admin/category/delete/:id', 'AdminController', 'deleteCategory');

$router->get('/admin/products', 'AdminController', 'products');
$router->get('/admin/product/form', 'AdminController', 'productForm');
$router->post('/admin/product/form', 'AdminController', 'productForm');
$router->get('/admin/product/delete/:id', 'AdminController', 'deleteProduct');

$router->get('/admin/orders', 'AdminController', 'orders');
$router->post('/admin/order/status', 'AdminController', 'updateOrderStatus');

$router->get('/admin/users', 'AdminController', 'users');
$router->get('/admin/user/form', 'AdminController', 'userForm');
$router->post('/admin/user/form', 'AdminController', 'userForm');
$router->get('/admin/user/delete/:id', 'AdminController', 'deleteUser');

// Admin Reviews
$router->get('/admin/reviews', 'AdminController', 'reviews');
$router->get('/admin/review/delete/:id', 'AdminController', 'deleteReview');

// Dispatch the request
try {
    $router->dispatch();
} catch (\Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
