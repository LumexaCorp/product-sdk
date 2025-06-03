<?php

declare(strict_types=1);

namespace Lumexa\ProductSdk\DTOs;

readonly class ProductCategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?int $parentId = null,
        public ?string $description = null,
        public ?string $image = null,
        public ?string $metaTitle = null,
        public ?string $metaDescription = null,
        public ?int $position = null,
        public bool $isActive = true,
        public ?\DateTimeImmutable $createdAt = null,
        public ?\DateTimeImmutable $updatedAt = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            slug: $data['slug'],
            parentId: $data['parent_id'] ?? null,
            description: $data['description'] ?? null,
            image: $data['image'] ?? null,
            metaTitle: $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            position: $data['position'] ?? null,
            isActive: $data['is_active'] ?? true,
            createdAt: isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            updatedAt: isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parentId,
            'description' => $this->description,
            'image' => $this->image,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'position' => $this->position,
            'is_active' => $this->isActive,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
