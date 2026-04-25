<?php

namespace App\Models;

class Review
{
    public ?int $id = null;
    public int $product_id;
    public int $user_id;
    public int $rating;
    public string $comment;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    // Related data
    public ?string $user_name = null;
    public ?string $product_name = null;

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->fill($data);
        }
    }

    public function fill(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->product_id = $data['product_id'] ?? 0;
        $this->user_id = $data['user_id'] ?? 0;
        $this->rating = $data['rating'] ?? 5;
        $this->comment = $data['comment'] ?? '';
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
        $this->user_name = $data['user_name'] ?? null;
        $this->product_name = $data['product_name'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function getStars(): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $this->rating ? '★' : '☆';
        }
        return $stars;
    }

    public function getFormattedDate(): string
    {
        if (!$this->created_at) {
            return '';
        }
        $date = new \DateTime($this->created_at);
        return $date->format('d/m/Y H:i');
    }
}
