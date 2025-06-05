<?php

declare(strict_types=1);

namespace Lumexa\ProductSdk\DTOs;

class ProductVariantDTO
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        public readonly string $id,
        public readonly string $sku,
        public readonly int $stock,
        public readonly array $attributes,
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
            sku: (string) $data['sku'],
            stock: (int) $data['stock'],
            attributes: (array) $data['attributes'],
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
            'sku' => $this->sku,
            'stock' => $this->stock,
            'attributes' => $this->attributes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
