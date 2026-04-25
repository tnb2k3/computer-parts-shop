<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Services\EmailService;

class UserController extends Controller
{
    private UserRepository $userRepo;
    private EmailService $emailService;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->emailService = new EmailService();
    }

    /**
     * Display login form
     */
    public function login(): void
    {
        // Redirect if already logged in
        if ($this->getSession('user')) {
            $this->redirect('/');
            return;
        }

        $this->view('login', [
            'title' => 'Đăng nhập',
        ]);
    }

    /**
     * Process login
     */
    public function loginPost(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/login');
            return;
        }

        $email = $this->getPost('email');
        $password = $this->getPost('password');

        $result = $this->userRepo->authenticate($email, $password);

        if ($result === 'unverified') {
            // Email not verified
            $this->redirect('/login?error=unverified');
            return;
        }

        if ($result instanceof User) {
            // Store user in session
            $this->setSession('user', $result->toArray());
            $this->setSession('user_id', $result->id);
            
            // Redirect based on user role
            if ($result->role === 'admin') {
                // Admin users go to admin dashboard
                $this->redirect('/admin/dashboard');
            } else {
                // Regular users go to home or intended page
                $redirect = $this->getSession('redirect_after_login', '/');
                $this->setSession('redirect_after_login', null);
                $this->redirect($redirect);
            }
        } else {
            // Redirect back with error
            $this->redirect('/login?error=1');
        }
    }

    /**
     * Display registration form
     */
    public function register(): void
    {
        // Redirect if already logged in
        if ($this->getSession('user')) {
            $this->redirect('/');
            return;
        }

        $this->view('register', [
            'title' => 'Đăng ký',
        ]);
    }

    /**
     * Process registration
     */
    public function registerPost(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/register');
            return;
        }

        $username = $this->getPost('username');
        $email = $this->getPost('email');
        $password = $this->getPost('password');
        $fullName = $this->getPost('full_name');
        $phone = $this->getPost('phone');
        $address = $this->getPost('address');

        // Check if email already exists
        if ($this->userRepo->findByEmail($email)) {
            $this->redirect('/register?error=email_exists');
            return;
        }

        // Check if username already exists
        if ($this->userRepo->findByUsername($username)) {
            $this->redirect('/register?error=username_exists');
            return;
        }

        // Create new user
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->hashPassword($password);
        $user->full_name = $fullName;
        $user->phone = $phone;
        $user->address = $address;
        $user->role = 'customer';
        $user->email_verified = false;
        
        // Generate verification token
        $token = $user->generateVerificationToken();

        if ($this->userRepo->create($user)) {
            // Send verification email
            $name = $fullName ?: $username;
            $emailSent = $this->emailService->sendVerificationEmail($email, $name, $token);
            
            if ($emailSent) {
                // Store email in session to display on pending page
                $this->setSession('pending_verification_email', $email);
                $this->redirect('/verify-pending');
            } else {
                // Email failed but user created - still redirect to pending with warning
                $this->setSession('pending_verification_email', $email);
                $this->setSession('email_send_failed', true);
                $this->redirect('/verify-pending');
            }
        } else {
            $this->redirect('/register?error=1');
        }
    }

    /**
     * Display verification pending page
     */
    public function verifyPending(): void
    {
        $email = $this->getSession('pending_verification_email');
        $emailFailed = $this->getSession('email_send_failed', false);
        $this->setSession('email_send_failed', null);
        
        $this->view('verify-pending', [
            'title' => 'Xác thực email',
            'email' => $email,
            'emailFailed' => $emailFailed,
        ]);
    }

    /**
     * Verify email with token
     */
    public function verifyEmail(string $token): void
    {
        $user = $this->userRepo->findByVerificationToken($token);

        if (!$user) {
            $this->view('verify-result', [
                'title' => 'Xác thực thất bại',
                'success' => false,
                'message' => 'Link xác thực không hợp lệ hoặc đã được sử dụng.',
            ]);
            return;
        }

        if (!$user->isTokenValid()) {
            $this->view('verify-result', [
                'title' => 'Link đã hết hạn',
                'success' => false,
                'message' => 'Link xác thực đã hết hạn. Vui lòng đăng ký lại hoặc yêu cầu gửi lại email xác thực.',
            ]);
            return;
        }

        // Verify email
        if ($this->userRepo->verifyEmail($user->id)) {
            $this->view('verify-result', [
                'title' => 'Xác thực thành công',
                'success' => true,
                'message' => 'Email của bạn đã được xác thực thành công! Bạn có thể đăng nhập ngay bây giờ.',
            ]);
        } else {
            $this->view('verify-result', [
                'title' => 'Lỗi xác thực',
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi xác thực. Vui lòng thử lại sau.',
            ]);
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerification(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/login');
            return;
        }

        $email = $this->getPost('email');
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            $this->redirect('/login?error=email_not_found');
            return;
        }

        if ($user->email_verified) {
            $this->redirect('/login?message=already_verified');
            return;
        }

        // Generate new token
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        if ($this->userRepo->updateVerificationToken($user->id, $token, $expiresAt)) {
            $name = $user->full_name ?: $user->username;
            $this->emailService->sendVerificationEmail($email, $name, $token);
        }

        $this->setSession('pending_verification_email', $email);
        $this->redirect('/verify-pending?resent=1');
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        $this->setSession('user', null);
        $this->setSession('user_id', null);
        $this->redirect('/');
    }

    /**
     * User profile (only for customers, not admins)
     */
    public function profile(): void
    {
        $user = $this->getSession('user');
        
        if (!$user) {
            $this->setSession('redirect_after_login', '/profile');
            $this->redirect('/login');
            return;
        }

        // Admin không cần trang profile, chuyển về trang admin
        if (($user['role'] ?? '') === 'admin') {
            $this->redirect('/admin');
            return;
        }

        $this->view('profile', [
            'title' => 'Thông tin cá nhân',
            'user' => $user,
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/profile');
            return;
        }

        $sessionUser = $this->getSession('user');
        if (!$sessionUser) {
            $this->redirect('/login');
            return;
        }

        $fullName = $this->getPost('full_name');
        $phone = $this->getPost('phone');
        $address = $this->getPost('address');

        $updateData = [
            'full_name' => $fullName,
            'phone' => $phone,
            'address' => $address,
        ];

        if ($this->userRepo->updateProfile($sessionUser['id'], $updateData)) {
            // Update session data
            $sessionUser['full_name'] = $fullName;
            $sessionUser['phone'] = $phone;
            $sessionUser['address'] = $address;
            $this->setSession('user', $sessionUser);
            
            $this->redirect('/profile?success=1');
        } else {
            $this->redirect('/profile?error=1');
        }
    }

    /**
     * Change user password
     */
    public function changePassword(): void
    {
        if (!$this->isPost()) {
            $this->redirect('/profile');
            return;
        }

        $sessionUser = $this->getSession('user');
        if (!$sessionUser) {
            $this->redirect('/login');
            return;
        }

        $currentPassword = $this->getPost('current_password');
        $newPassword = $this->getPost('new_password');
        $confirmPassword = $this->getPost('confirm_password');

        // Validate passwords match
        if ($newPassword !== $confirmPassword) {
            $this->redirect('/profile?error=password_mismatch');
            return;
        }

        // Validate new password length
        if (strlen($newPassword) < 6) {
            $this->redirect('/profile?error=password_short');
            return;
        }

        // Get full user data to verify current password
        $user = $this->userRepo->findById($sessionUser['id']);
        if (!$user || !$user->verifyPassword($currentPassword)) {
            $this->redirect('/profile?error=wrong_password');
            return;
        }

        // Update password
        if ($this->userRepo->updatePassword($sessionUser['id'], $newPassword)) {
            $this->redirect('/profile?success=password');
        } else {
            $this->redirect('/profile?error=1');
        }
    }
}

