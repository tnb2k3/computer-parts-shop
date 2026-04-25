<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository
{
    /**
     * Get all cart items
     */
    public function getCart(): array
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $cartItems = [];
        foreach ($_SESSION['cart'] as $item) {
            $cartItems[] = new Cart($item);
        }

        return $cartItems;
    }

    /**
     * Add item to cart
     */
    public function addItem(int $productId, string $productName, float $price, int $quantity = 1, ?string $image = null, ?string $img_url = null): void
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if item already exists
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        // Add new item if not found
        if (!$found) {
            $_SESSION['cart'][] = [
                'product_id' => $productId,
                'product_name' => $productName,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $image,
                'img_url' => $img_url,
            ];
        }
    }

    /**
     * Update item quantity
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        if (!isset($_SESSION['cart'])) {
            return;
        }

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
                break;
            }
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(int $productId): void
    {
        if (!isset($_SESSION['cart'])) {
            return;
        }

        $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($productId) {
            return $item['product_id'] != $productId;
        });

        // Re-index array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    /**
     * Clear cart
     */
    public function clearCart(): void
    {
        $_SESSION['cart'] = [];
    }

    /**
     * Get cart total
     */
    public function getTotal(): float
    {
        $total = 0;
        $items = $this->getCart();

        foreach ($items as $item) {
            $total += $item->getSubtotal();
        }

        return $total;
    }

    /**
     * Get cart item count
     */
    public function getItemCount(): int
    {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }

        $count = 0;
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty(): bool
    {
        return empty($_SESSION['cart']);
    }
}
