<?php

namespace App\Repositories;

use App\Models\Review;
use App\Database\Connection;
use PDO;

class ReviewRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Get all reviews for a specific product
     */
    public function getByProductId(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.full_name as user_name, u.username
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.product_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$productId]);
        
        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = new Review($row);
        }
        
        return $reviews;
    }

    /**
     * Get average rating for a product
     */
    public function getAverageRating(int $productId): float
    {
        $stmt = $this->db->prepare("
            SELECT AVG(rating) as avg_rating
            FROM reviews
            WHERE product_id = ?
        ");
        $stmt->execute([$productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result && $result['avg_rating'] ? (float)$result['avg_rating'] : 0;
    }

    /**
     * Get rating distribution (count for each star level)
     */
    public function getRatingCounts(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT rating, COUNT(*) as count
            FROM reviews
            WHERE product_id = ?
            GROUP BY rating
            ORDER BY rating DESC
        ");
        $stmt->execute([$productId]);
        
        // Initialize all ratings to 0
        $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $counts[(int)$row['rating']] = (int)$row['count'];
        }
        
        return $counts;
    }

    /**
     * Get total review count for a product
     */
    public function getReviewCount(int $productId): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM reviews
            WHERE product_id = ?
        ");
        $stmt->execute([$productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (int)$result['count'] : 0;
    }

    /**
     * Check if a user has already reviewed a product
     */
    public function userHasReviewed(int $userId, int $productId): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM reviews
            WHERE user_id = ? AND product_id = ?
        ");
        $stmt->execute([$userId, $productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result && $result['count'] > 0;
    }

    /**
     * Create a new review
     */
    public function create(array $data): ?int
    {
        $stmt = $this->db->prepare("
            INSERT INTO reviews (product_id, user_id, rating, comment)
            VALUES (?, ?, ?, ?)
        ");
        
        $success = $stmt->execute([
            $data['product_id'],
            $data['user_id'],
            $data['rating'],
            $data['comment']
        ]);
        
        return $success ? (int)$this->db->lastInsertId() : null;
    }

    /**
     * Get a review by ID
     */
    public function getById(int $id): ?Review
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.full_name as user_name, u.username
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? new Review($row) : null;
    }

    /**
     * Delete a review
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get all reviews (for admin)
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT r.*, u.full_name as user_name, u.username, p.name as product_name
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            JOIN products p ON r.product_id = p.id
            ORDER BY r.created_at DESC
        ");
        
        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = new Review($row);
        }
        
        return $reviews;
    }
}
