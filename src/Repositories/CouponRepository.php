<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Database\Connection;
use PDO;

class CouponRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Get all coupons
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM coupons
            ORDER BY created_at DESC
        ");
        
        $coupons = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $coupons[] = new Coupon($row);
        }
        
        return $coupons;
    }

    /**
     * Get active coupons (valid, not expired, not over usage limit)
     */
    public function getActiveCoupons(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM coupons
            WHERE is_active = 1
            AND (valid_from IS NULL OR valid_from <= NOW())
            AND (valid_to IS NULL OR valid_to >= NOW())
            AND (usage_limit IS NULL OR times_used < usage_limit)
            ORDER BY value DESC
        ");
        
        $coupons = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $coupons[] = new Coupon($row);
        }
        
        return $coupons;
    }

    /**
     * Get coupon by ID
     */
    public function getById(int $id): ?Coupon
    {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? new Coupon($row) : null;
    }

    /**
     * Get coupon by code
     */
    public function getByCode(string $code): ?Coupon
    {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE code = ?");
        $stmt->execute([strtoupper($code)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? new Coupon($row) : null;
    }

    /**
     * Validate coupon and return error message if invalid
     */
    public function validateCoupon(string $code, ?int $userId, float $orderTotal): array
    {
        $coupon = $this->getByCode($code);
        
        if (!$coupon) {
            return ['valid' => false, 'message' => 'Mã giảm giá không tồn tại'];
        }

        if (!$coupon->isValid()) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn hoặc không còn hiệu lực'];
        }

        if (!$coupon->canBeUsed()) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng'];
        }

        if ($orderTotal < $coupon->min_order_value) {
            return [
                'valid' => false, 
                'message' => 'Đơn hàng chưa đủ giá trị tối thiểu ' . $coupon->getFormattedMinOrder()
            ];
        }

        $discountAmount = $coupon->calculateDiscount($orderTotal);
        
        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount_amount' => $discountAmount,
            'message' => 'Áp dụng mã giảm giá thành công'
        ];
    }

    /**
     * Record coupon usage
     */
    public function applyCoupon(int $couponId, int $userId, int $orderId, float $discountAmount): bool
    {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Insert usage record
            $stmt = $this->db->prepare("
                INSERT INTO coupon_usage (coupon_id, user_id, order_id, discount_amount)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$couponId, $userId, $orderId, $discountAmount]);
            
            // Increment times_used
            $stmt = $this->db->prepare("
                UPDATE coupons
                SET times_used = times_used + 1
                WHERE id = ?
            ");
            $stmt->execute([$couponId]);
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Get coupon usage history for a user
     */
    public function getUserCouponHistory(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT cu.*, c.code, c.description, o.total
            FROM coupon_usage cu
            JOIN coupons c ON cu.coupon_id = c.id
            JOIN orders o ON cu.order_id = o.id
            WHERE cu.user_id = ?
            ORDER BY cu.created_at DESC
        ");
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new coupon
     */
    public function create(array $data): ?int
    {
        $stmt = $this->db->prepare("
            INSERT INTO coupons (code, type, value, min_order_value, max_discount, usage_limit, valid_from, valid_to, is_active, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $success = $stmt->execute([
            strtoupper($data['code']),
            $data['type'],
            $data['value'],
            $data['min_order_value'] ?? 0,
            $data['max_discount'] ?? null,
            $data['usage_limit'] ?? null,
            $data['valid_from'] ?? null,
            $data['valid_to'] ?? null,
            $data['is_active'] ?? true,
            $data['description'] ?? null
        ]);
        
        return $success ? (int)$this->db->lastInsertId() : null;
    }

    /**
     * Update a coupon
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE coupons
            SET code = ?, type = ?, value = ?, min_order_value = ?, max_discount = ?, 
                usage_limit = ?, valid_from = ?, valid_to = ?, is_active = ?, description = ?
            WHERE id = ?
        ");
        
        return $stmt->execute([
            strtoupper($data['code']),
            $data['type'],
            $data['value'],
            $data['min_order_value'] ?? 0,
            $data['max_discount'] ?? null,
            $data['usage_limit'] ?? null,
            $data['valid_from'] ?? null,
            $data['valid_to'] ?? null,
            $data['is_active'] ?? true,
            $data['description'] ?? null,
            $id
        ]);
    }

    /**
     * Delete a coupon
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM coupons WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
