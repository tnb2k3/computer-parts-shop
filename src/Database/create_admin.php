<?php
/**
 * Script to create/update admin user
 * Run this from command line: php create_admin.php
 * Or access via browser: http://localhost/create_admin.php (remove after use for security)
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;
use App\Models\User;
use App\Repositories\UserRepository;

// Database connection
$db = Connection::getInstance();
$userRepo = new UserRepository();

// Admin credentials
$adminEmail = 'bao089492@gmail.com';
$adminPassword = 'baodz123'; // Admin password
$adminUsername = 'admin_bao';
$adminFullName = 'Administrator';

// Check if admin already exists
$existingUser = $userRepo->findByEmail($adminEmail);

if ($existingUser) {
    // Update existing user to admin
    $existingUser->role = 'admin';
    $existingUser->hashPassword($adminPassword);
    
    // Update password in database
    $stmt = $db->prepare("UPDATE users SET password = ?, role = ? WHERE email = ?");
    $stmt->execute([$existingUser->password, 'admin', $adminEmail]);
    
    echo "✓ Admin user updated successfully!\n";
    echo "Email: $adminEmail\n";
    echo "Password: $adminPassword\n";
    echo "Role: admin\n";
} else {
    // Create new admin user
    $admin = new User();
    $admin->username = $adminUsername;
    $admin->email = $adminEmail;
    $admin->hashPassword($adminPassword);
    $admin->full_name = $adminFullName;
    $admin->role = 'admin';
    
    if ($userRepo->create($admin)) {
        echo "✓ Admin user created successfully!\n";
        echo "Email: $adminEmail\n";
        echo "Password: $adminPassword\n";
        echo "Role: admin\n";
    } else {
        echo "✗ Failed to create admin user!\n";
    }
}

echo "\nYou can now login with:\n";
echo "Email: $adminEmail\n";
echo "Password: $adminPassword\n";

