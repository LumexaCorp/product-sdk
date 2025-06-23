<?php

declare(strict_types=1);

namespace Lumexa\ProductSdk\DTOs;

use Lumexa\ProductSdk\DTOs\ImageDTO;

class ProductDTO
{
    /**
     * @param array<ProductVariantDTO> $variants
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
        public readonly ?array $variants,
        public readonly string $slug,
        public readonly ?array $images,
        public readonly bool $is_active,
        public readonly ?ProductTypeDTO $product_type,
        public readonly string $availableAt,
        public readonly string $created_at,
        public readonly string $updated_at,
    ) {
    }

    /**
     * Create a DTO from an array
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['id'],
            name: (string) $data['name'],
            slug: (string) $data['slug'],
            description: (string) $data['description'],
            product_type: (array_key_exists('product_type', $data) && is_array($data['product_type'])) ? ProductTypeDTO::fromArray($data['product_type']) : null,
            price: (float) $data['price'],
            images: (array_key_exists('images', $data) && is_array($data['images'])) ? array_map(
                fn (array $image) => ProductImageDTO::fromArray($image),
                $data['images']
            ) : null,
            is_active: (bool) $data['is_active'],
            availableAt: (string) $data['available_at'],
            variants: (array_key_exists('variants', $data) && is_array($data['variants'])) ? array_map(
                fn (array $variant) => ProductVariantDTO::fromArray($variant),
                $data['variants']
            ) : null,
            created_at: (string) $data['created_at'],
            updated_at: (string) $data['updated_at'],
        );
    }

    /**
     * Convert the DTO to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'slug' => $this->slug,
            'product_type' => $this->product_type ? $this->product_type->toArray() : null,
            'images' => $this->images ? array_map(
                fn (ProductImageDTO $image) => $image->toArray(),
                $this->images
            ) : null,
            'is_active' => $this->is_active,
            'available_at' => $this->availableAt,
            'variants' => $this->variants ? array_map(
                fn (ProductVariantDTO $variant) => $variant->toArray(),
                $this->variants
            ) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
