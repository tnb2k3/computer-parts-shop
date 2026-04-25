<?php

namespace App\Models;

class Order
{
    public ?int $id = null;
    public int $user_id;
    public float $total;
    public string $status = 'pending';
    public string $customer_name;
    public string $customer_email;
    public string $customer_phone;
    public string $shipping_address;
    public ?string $notes = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;
    public string $payment_method = 'cod';
    public string $payment_status = 'pending';

    // Related data
    public array $items = [];

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? 0;
        $this->total = $data['total'] ?? 0.0;
        $this->status = $data['status'] ?? 'pending';
        $this->customer_name = $data['customer_name'] ?? '';
        $this->customer_email = $data['customer_email'] ?? '';
        $this->customer_phone = $data['customer_phone'] ?? '';
        $this->shipping_address = $data['shipping_address'] ?? '';
        $this->notes = $data['notes'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
        $this->payment_method = $data['payment_method'] ?? 'cod';
        $this->payment_status = $data['payment_status'] ?? 'pending';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total' => $this->total,
            'status' => $this->status,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'shipping_address' => $this->shipping_address,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
        ];
    }

    public function getFormattedTotal(): string
    {
        return number_format($this->total, 0, ',', '.') . ' ₫';
    }

    public function getStatusLabel(): string
    {
        $labels = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentMethodLabel(): string
    {
        $labels = [
            'cod' => 'Thanh toán khi nhận hàng',
            'qr_bank' => 'Chuyển khoản QR Banking',
        ];
        return $labels[$this->payment_method] ?? $this->payment_method;
    }

    public function getPaymentStatusLabel(): string
    {
        $labels = [
            'pending' => 'Chưa thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
        ];
        return $labels[$this->payment_status] ?? $this->payment_status;
    }
}
