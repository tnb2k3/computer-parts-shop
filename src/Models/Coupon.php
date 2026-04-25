<?php

namespace App\Models;

class Coupon
{
    public ?int $id = null;
    public string $code;
    public string $type; // 'percentage' or 'fixed'
    public float $value;
    public float $min_order_value = 0;
    public ?float $max_discount = null;
    public ?int $usage_limit = null;
    public int $times_used = 0;
    public ?string $valid_from = null;
    public ?string $valid_to = null;
    public bool $is_active = true;
    public ?string $description = null;
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
        $this->code = $data['code'] ?? '';
        $this->type = $data['type'] ?? 'percentage';
        $this->value = $data['value'] ?? 0.0;
        $this->min_order_value = $data['min_order_value'] ?? 0.0;
        $this->max_discount = $data['max_discount'] ?? null;
        $this->usage_limit = $data['usage_limit'] ?? null;
        $this->times_used = $data['times_used'] ?? 0;
        $this->valid_from = $data['valid_from'] ?? null;
        $this->valid_to = $data['valid_to'] ?? null;
        $this->is_active = $data['is_active'] ?? true;
        $this->description = $data['description'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'min_order_value' => $this->min_order_value,
            'max_discount' => $this->max_discount,
            'usage_limit' => $this->usage_limit,
            'times_used' => $this->times_used,
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to,
            'is_active' => $this->is_active,
            'description' => $this->description,
        ];
    }

    /**
     * Check if coupon is currently valid based on dates and active status
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = new \DateTime();

        if ($this->valid_from) {
            $validFrom = new \DateTime($this->valid_from);
            if ($now < $validFrom) {
                return false;
            }
        }

        if ($this->valid_to) {
            $validTo = new \DateTime($this->valid_to);
            if ($now > $validTo) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if coupon can still be used (within usage limit)
     */
    public function canBeUsed(): bool
    {
        if ($this->usage_limit === null) {
            return true; // No limit
        }
        return $this->times_used < $this->usage_limit;
    }

    /**
     * Calculate discount amount for a given order total
     */
    public function calculateDiscount(float $orderTotal): float
    {
        if ($orderTotal < $this->min_order_value) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $orderTotal * ($this->value / 100);
            
            // Apply max discount cap if set
            if ($this->max_discount !== null && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            
            return $discount;
        } else {
            // Fixed amount
            return $this->value;
        }
    }

    /**
     * Get formatted discount display (e.g., "10%" or "50,000 ₫")
     */
    public function getFormattedDiscount(): string
    {
        if ($this->type === 'percentage') {
            return $this->value . '%';
        } else {
            return number_format($this->value, 0, ',', '.') . ' ₫';
        }
    }

    /**
     * Get formatted min order value
     */
    public function getFormattedMinOrder(): string
    {
        return number_format($this->min_order_value, 0, ',', '.') . ' ₫';
    }
}
