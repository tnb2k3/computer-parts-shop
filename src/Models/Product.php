<?php

namespace App\Models;

class Product
{
    public ?int $id = null;
    public int $category_id;
    public string $name;
    public ?string $description = null;
    public float $price;
    public ?string $image = null;
    public ?string $img_url = null;
    public int $stock = 0;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // Related data
    public ?string $category_name = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->category_id = $data['category_id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->price = $data['price'] ?? 0.0;
        $this->image = $data['image'] ?? null;
        $this->img_url = $data['img_url'] ?? null;
        $this->stock = $data['stock'] ?? 0;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
        $this->category_name = $data['category_name'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image,
            'img_url' => $this->img_url,
            'stock' => $this->stock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, ',', '.') . ' ₫';
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
