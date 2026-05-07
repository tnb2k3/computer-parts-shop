<?php

namespace App\Repositories;

use App\Database\Connection;
use App\Models\User;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getDb(): PDO
    {
        return $this->db;
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    /**
     * Find user by username
     */
    public function findByUsername(string $username): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    /**
     * Get user by ID
     */
    public function getById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    /**
     * Find user by verification token
     */
    public function findByVerificationToken(string $token): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE verification_token = ?");
        $stmt->execute([$token]);
        
        $row = $stmt->fetch();
        return $row ? new User($row) : null;
    }

    /**
     * Create a new user with verification token
     */
    public function create(User $user): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, email, password, full_name, phone, address, role, email_verified, verification_token, token_expires_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        return $stmt->execute([
            $user->username,
            $user->email,
            $user->password,
            $user->full_name,
            $user->phone,
            $user->address,
            $user->role,
            $user->email_verified ? 1 : 0,
            $user->verification_token,
            $user->token_expires_at,
        ]);
    }

    /**
     * Verify user email
     */
    public function verifyEmail(int $userId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET email_verified = TRUE, verification_token = NULL, token_expires_at = NULL WHERE id = ?"
        );
        return $stmt->execute([$userId]);
    }

    /**
     * Update verification token (for resend)
     */
    public function updateVerificationToken(int $userId, string $token, string $expiresAt): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET verification_token = ?, token_expires_at = ? WHERE id = ?"
        );
        return $stmt->execute([$token, $expiresAt, $userId]);
    }

    /**
     * Update user profile
     */
    public function update(User $user): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users 
             SET username = ?, email = ?, full_name = ?, phone = ?, address = ? 
             WHERE id = ?"
        );
        
        return $stmt->execute([
            $user->username,
            $user->email,
            $user->full_name,
            $user->phone,
            $user->address,
            $user->id,
        ]);
    }

    /**
     * Authenticate user (check email verification)
     * Returns: User if success, null if failed, 'unverified' string if email not verified
     */
    public function authenticate(string $email, string $password): User|string|null
    {
        $user = $this->findByEmail($email);
        
        if ($user && $user->verifyPassword($password)) {
            // Check if email is verified (admin users bypass this check)
            if (!$user->email_verified && $user->role !== 'admin') {
                return 'unverified';
            }
            return $user;
        }
        
        return null;
    }

    /**
     * Get all users (for admin)
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        
        $users = [];
        while ($row = $stmt->fetch()) {
            $users[] = new User($row);
        }
        return $users;
    }

    /**
     * Update user role (for admin)
     */
    public function updateRole(int $id, string $role): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }

    /**
     * Update user by admin (includes role)
     */
    public function updateByAdmin(User $user): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users 
             SET username = ?, email = ?, full_name = ?, phone = ?, address = ?, role = ? 
             WHERE id = ?"
        );
        
        return $stmt->execute([
            $user->username,
            $user->email,
            $user->full_name,
            $user->phone,
            $user->address,
            $user->role,
            $user->id,
        ]);
    }

    /**
     * Delete user (for admin)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Find user by ID (alias for getById)
     */
    public function findById(int $id): ?User
    {
        return $this->getById($id);
    }

    /**
     * Update user profile (partial update)
     */
    public function updateProfile(int $userId, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users 
             SET full_name = ?, phone = ?, address = ? 
             WHERE id = ?"
        );
        
        return $stmt->execute([
            $data['full_name'] ?? '',
            $data['phone'] ?? '',
            $data['address'] ?? '',
            $userId,
        ]);
    }

    /**
     * Update user password
     */
    public function updatePassword(int $userId, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $userId]);
    }
}

