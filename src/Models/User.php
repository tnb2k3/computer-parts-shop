<?php

namespace App\Models;

class User
{
    public ?int $id = null;
    public string $username;
    public string $email;
    public string $password;
    public ?string $full_name = null;
    public ?string $phone = null;
    public ?string $address = null;
    public string $role = 'customer';
    public bool $email_verified = false;
    public ?string $verification_token = null;
    public ?string $token_expires_at = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->username = $data['username'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->full_name = $data['full_name'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->address = $data['address'] ?? null;
        $this->role = $data['role'] ?? 'customer';
        $this->email_verified = (bool)($data['email_verified'] ?? false);
        $this->verification_token = $data['verification_token'] ?? null;
        $this->token_expires_at = $data['token_expires_at'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'role' => $this->role,
            'email_verified' => $this->email_verified,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified;
    }

    public function hashPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Generate a 6-digit OTP code
     */
    public function generateVerificationToken(): string
    {
        $this->verification_token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->token_expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        return $this->verification_token;
    }

    /**
     * Check if verification token is valid and not expired
     */
    public function isTokenValid(): bool
    {
        if (empty($this->verification_token) || empty($this->token_expires_at)) {
            return false;
        }
        return strtotime($this->token_expires_at) > time();
    }
}

