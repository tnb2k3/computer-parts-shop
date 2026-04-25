<?php

namespace App\Models;

class Cart
{
    public int $product_id;
    public string $product_name;
    public float $price;
    public int $quantity;
    public ?string $image = null;
    public ?string $img_url = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data): void
    {
        $this->product_id = $data['product_id'] ?? 0;
        $this->product_name = $data['product_name'] ?? '';
        $this->price = $data['price'] ?? 0.0;
        $this->quantity = $data['quantity'] ?? 1;
        $this->image = $data['image'] ?? null;
        $this->img_url = $data['img_url'] ?? null;
    }

    public function getSubtotal(): float
    {
        return $this->price * $this->quantity;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, ',', '.') . ' ₫';
    }

    public function getFormattedSubtotal(): string
    {
        return number_format($this->getSubtotal(), 0, ',', '.') . ' ₫';
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'image' => $this->image,
            'img_url' => $this->img_url,
        ];
    }
}
