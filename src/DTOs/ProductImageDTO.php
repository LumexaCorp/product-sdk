<?php

declare(strict_types=1);

namespace Lumexa\ProductSdk\DTOs;

class ProductImageDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $path,
        public readonly int $order,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['id'],
            name: (string) $data['name'],
            path: (string) $data['path'],
            order: (int) $data['order'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            'order' => $this->order,
        ];
    }
}
