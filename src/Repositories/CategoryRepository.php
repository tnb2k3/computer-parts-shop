<?php

namespace App\Repositories;

use App\Database\Connection;
use App\Models\Category;
use PDO;

class CategoryRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Get all categories
     */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT c.*, COUNT(p.id) as product_count 
             FROM categories c 
             LEFT JOIN products p ON c.id = p.category_id 
             GROUP BY c.id 
             ORDER BY c.name"
        );
        
        $categories = [];
        while ($row = $stmt->fetch()) {
            $categories[] = new Category($row);
        }
        return $categories;
    }

    /**
     * Get category by ID
     */
    public function getById(int $id): ?Category
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, COUNT(p.id) as product_count 
             FROM categories c 
             LEFT JOIN products p ON c.id = p.category_id 
             WHERE c.id = ? 
             GROUP BY c.id"
        );
        $stmt->execute([$id]);
        
        $row = $stmt->fetch();
        return $row ? new Category($row) : null;
    }

    /**
     * Create a new category
     */
    public function create(Category $category): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO categories (name, description) VALUES (?, ?)"
        );
        
        return $stmt->execute([
            $category->name,
            $category->description,
        ]);
    }

    /**
     * Update a category
     */
    public function update(Category $category): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE categories SET name = ?, description = ? WHERE id = ?"
        );
        
        return $stmt->execute([
            $category->name,
            $category->description,
            $category->id,
        ]);
    }

    /**
     * Delete a category
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
