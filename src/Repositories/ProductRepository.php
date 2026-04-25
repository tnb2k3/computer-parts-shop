<?php

namespace App\Repositories;

use App\Database\Connection;
use App\Models\Product;
use PDO;

class ProductRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Get all products
     */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             LEFT JOIN categories c ON p.category_id = c.id 
             ORDER BY p.created_at DESC"
        );

        $products = [];
        while ($row = $stmt->fetch()) {
            $products[] = new Product($row);
        }
        return $products;
    }

    /**
     * Get product by ID
     */
    public function getById(int $id): ?Product
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             LEFT JOIN categories c ON p.category_id = c.id 
             WHERE p.id = ?"
        );
        $stmt->execute([$id]);

        $row = $stmt->fetch();
        return $row ? new Product($row) : null;
    }

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             LEFT JOIN categories c ON p.category_id = c.id 
             WHERE p.category_id = ? 
             ORDER BY p.created_at DESC"
        );
        $stmt->execute([$categoryId]);

        $products = [];
        while ($row = $stmt->fetch()) {
            $products[] = new Product($row);
        }
        return $products;
    }

    /**
     * Search products by name
     */
    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             LEFT JOIN categories c ON p.category_id = c.id 
             WHERE p.name LIKE ? OR p.description LIKE ?
             ORDER BY p.created_at DESC"
        );
        $searchTerm = "%$keyword%";
        $stmt->execute([$searchTerm, $searchTerm]);

        $products = [];
        while ($row = $stmt->fetch()) {
            $products[] = new Product($row);
        }
        return $products;
    }

    /**
     * Get featured products (latest products)
     */
    public function getFeatured(int $limit = 8): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name as category_name 
             FROM products p 
             LEFT JOIN categories c ON p.category_id = c.id 
             WHERE p.stock > 0
             ORDER BY p.created_at DESC 
             LIMIT ?"
        );
        $stmt->execute([$limit]);

        $products = [];
        while ($row = $stmt->fetch()) {
            $products[] = new Product($row);
        }
        return $products;
    }

    /**
     * Create a new product
     */
    public function create(Product $product): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO products (category_id, name, description, price, image, img_url, stock) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $product->category_id,
            $product->name,
            $product->description,
            $product->price,
            $product->image,
            $product->img_url,
            $product->stock,
        ]);
    }

    /**
     * Update a product
     */
    public function update(Product $product): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE products 
             SET category_id = ?, name = ?, description = ?, price = ?, image = ?, img_url = ?, stock = ? 
             WHERE id = ?"
        );

        return $stmt->execute([
            $product->category_id,
            $product->name,
            $product->description,
            $product->price,
            $product->image,
            $product->img_url,
            $product->stock,
            $product->id,
        ]);
    }

    /**
     * Delete a product
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Update product stock
     */
    public function updateStock(int $productId, int $quantity): bool
    {
        $stmt = $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        return $stmt->execute([$quantity, $productId]);
    }
}
