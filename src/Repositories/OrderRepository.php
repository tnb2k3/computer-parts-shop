<?php

namespace App\Repositories;

use App\Database\Connection;
use App\Models\Order;
use PDO;

class OrderRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Create a new order
     */
    public function create(Order $order, array $items): ?int
    {
        try {
            $this->db->beginTransaction();

            // Insert order
            $stmt = $this->db->prepare(
                "INSERT INTO orders (user_id, total, status, customer_name, customer_email, customer_phone, shipping_address, notes, payment_method, payment_status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            
            $stmt->execute([
                $order->user_id,
                $order->total,
                $order->status,
                $order->customer_name,
                $order->customer_email,
                $order->customer_phone,
                $order->shipping_address,
                $order->notes,
                $order->payment_method,
                $order->payment_status,
            ]);

            $orderId = $this->db->lastInsertId();

            // Insert order items
            $stmt = $this->db->prepare(
                "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, subtotal) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );

            foreach ($items as $item) {
                $stmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['product_name'],
                    $item['quantity'],
                    $item['price'],
                    $item['subtotal'],
                ]);

                // Update product stock
                $updateStmt = $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $updateStmt->execute([$item['quantity'], $item['product_id']]);
            }

            $this->db->commit();
            return (int)$orderId;

        } catch (\Exception $e) {
            $this->db->rollBack();
            return null;
        }
    }

    /**
     * Get order by ID
     */
    public function getById(int $id): ?Order
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }

        $order = new Order($row);
        $order->items = $this->getOrderItems($id);
        
        return $order;
    }

    /**
     * Get order items
     */
    public function getOrderItems(int $orderId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        
        return $stmt->fetchAll();
    }

    /**
     * Get orders by user
     */
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC"
        );
        $stmt->execute([$userId]);
        
        $orders = [];
        while ($row = $stmt->fetch()) {
            $order = new Order($row);
            $order->items = $this->getOrderItems($order->id);
            $orders[] = $order;
        }
        return $orders;
    }

    /**
     * Get all orders (for admin)
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM orders ORDER BY created_at DESC");
        
        $orders = [];
        while ($row = $stmt->fetch()) {
            $order = new Order($row);
            $order->items = $this->getOrderItems($order->id);
            $orders[] = $order;
        }
        return $orders;
    }

    /**
     * Update order status
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        // Lấy trạng thái hiện tại
        $stmt = $this->db->prepare("SELECT status FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $currentStatus = $stmt->fetchColumn();

        // Không cho phép thay đổi nếu đơn đã hoàn thành
        if ($currentStatus === false || $currentStatus === 'completed') {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $orderId, string $paymentStatus): bool
    {
        $stmt = $this->db->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
        return $stmt->execute([$paymentStatus, $orderId]);
    }

    /**
     * Get order statistics (for admin dashboard)
     */
    public function getStatistics(): array
    {
        $stmt = $this->db->query(
            "SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                SUM(total) as total_revenue
             FROM orders"
        );
        
        return $stmt->fetch();
    }

    /**
     * Get revenue by category
     */
    public function getRevenueByCategory(): array
    {
        $stmt = $this->db->query(
            "SELECT c.id, c.name as category_name, 
                    COALESCE(SUM(oi.subtotal), 0) as revenue,
                    COALESCE(SUM(oi.quantity), 0) as quantity
             FROM categories c
             LEFT JOIN products p ON p.category_id = c.id
             LEFT JOIN order_items oi ON oi.product_id = p.id
             LEFT JOIN orders o ON oi.order_id = o.id AND o.status != 'cancelled'
             GROUP BY c.id, c.name
             ORDER BY revenue DESC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Get revenue by time period
     * @param string $period - 'day', 'week', 'month', 'year'
     */
    public function getRevenueByTime(string $period = 'day'): array
    {
        $groupBy = match($period) {
            'day' => "DATE(o.created_at)",
            'week' => "YEARWEEK(o.created_at, 1)",
            'month' => "DATE_FORMAT(o.created_at, '%Y-%m')",
            'year' => "YEAR(o.created_at)",
            default => "DATE(o.created_at)"
        };

        $labelFormat = match($period) {
            'day' => "DATE_FORMAT(o.created_at, '%d/%m')",
            'week' => "CONCAT('Tuần ', WEEK(o.created_at, 1))",
            'month' => "DATE_FORMAT(o.created_at, '%m/%Y')",
            'year' => "YEAR(o.created_at)",
            default => "DATE_FORMAT(o.created_at, '%d/%m')"
        };

        $limit = match($period) {
            'day' => 14,
            'week' => 12,
            'month' => 12,
            'year' => 5,
            default => 14
        };

        $stmt = $this->db->prepare(
            "SELECT {$labelFormat} as label,
                    {$groupBy} as time_group,
                    SUM(o.total) as revenue,
                    COUNT(o.id) as order_count
             FROM orders o
             WHERE o.status != 'cancelled'
             GROUP BY time_group, label
             ORDER BY time_group DESC
             LIMIT {$limit}"
        );
        $stmt->execute();
        
        return array_reverse($stmt->fetchAll());
    }
}
