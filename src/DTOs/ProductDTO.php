<?php

declare(strict_types=1);

namespace Lumexa\ProductSdk\DTOs;

class ProductDTO
{
    /**
     * @param array<ProductVariantDTO> $variants
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly array $variants,
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
            description: (string) $data['description'],
            variants: array_map(
                fn (array $variant) => ProductVariantDTO::fromArray($variant),
                $data['variants'] ?? []
            ),
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
            'variants' => array_map(
                fn (ProductVariantDTO $variant) => $variant->toArray(),
                $this->variants
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
