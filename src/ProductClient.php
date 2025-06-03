<?php

declare(strict_types=1);

namespace Lumexa\ProductSdk;

use GuzzleHttp\Client;
use Lumexa\ProductSdk\DTOs\ProductDTO;
use Lumexa\ProductSdk\DTOs\ProductTypeDTO;
use Lumexa\ProductSdk\DTOs\ProductVariantDTO;
use Lumexa\ProductSdk\Exceptions\ProductException;

class ProductClient
{
    private Client $httpClient;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $storeToken,
        ?Client $httpClient = null
    ) {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'X-Store-Token' => $this->storeToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Get all products
     *
     * @return array<ProductDTO>
     * @throws ProductException
     */
    public function getAllProducts(): array
    {
        try {
            $response = $this->httpClient->get('/api/products');
            $data = json_decode((string) $response->getBody(), true);
            return array_map(fn (array $product) => ProductDTO::fromArray($product), $data['data']);
        } catch (\Exception $e) {
            throw new ProductException("Failed to get products: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Get a product by ID
     *
     * @throws ProductException
     */
    public function getProductById(string $id): ProductDTO
    {
        try {
            $response = $this->httpClient->get("/api/products/{$id}");
            $data = json_decode((string) $response->getBody(), true);
            return ProductDTO::fromArray($data);
        } catch (\Exception $e) {
            throw new ProductException("Failed to get product: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Create a new product
     *
     * @param array{
     *     name: string,
     *     description: string
     * } $data
     * @throws ProductException
     */
    public function createProduct(array $data): ProductDTO
    {
        try {
            $response = $this->httpClient->post('/api/products', [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductDTO::fromArray($data);
        } catch (\Exception $e) {
            throw new ProductException("Failed to create product: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Update a product
     *
     * @param array{
     *     name?: string,
     *     description?: string
     * } $data
     * @throws ProductException
     */
    public function updateProduct(string $id, array $data): ProductDTO
    {
        try {
            $response = $this->httpClient->patch("/api/products/{$id}", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductDTO::fromArray($data);
        } catch (\Exception $e) {
            throw new ProductException("Failed to update product: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Delete a product
     *
     * @throws ProductException
     */
    public function deleteProduct(string $id): void
    {
        try {
            $this->httpClient->delete("/api/products/{$id}");
        } catch (\Exception $e) {
            throw new ProductException("Failed to delete product: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Get all product types
     *
     * @return array<ProductTypeDTO>
     * @throws ProductException
     */
    public function getAllProductTypes(): array
    {
        try {
            $response = $this->httpClient->get('/api/product-types');
            $data = json_decode((string) $response->getBody(), true);
            return array_map(fn (array $type) => ProductTypeDTO::fromArray($type), $data['data']);
        } catch (\Exception $e) {
            throw new ProductException("Failed to get product types: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Get a product type by ID
     *
     * @throws ProductException
     */
    public function getProductTypeById(string $id): ProductTypeDTO
    {
        try {
            $response = $this->httpClient->get("/api/product-types/{$id}");
            $data = json_decode((string) $response->getBody(), true);
            return ProductTypeDTO::fromArray($data);
        } catch (\Exception $e) {
            throw new ProductException("Failed to get product type: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Create a new product type
     *
     * @param array{name: string} $data
     * @throws ProductException
     */
    public function createProductType(array $data): ProductTypeDTO
    {
        try {
            $response = $this->httpClient->post('/api/product-types', [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductTypeDTO::fromArray($data);
        } catch (\Exception $e) {
            throw new ProductException("Failed to create product type: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Update a product type
     *
     * @param array{name?: string} $data
     * @throws ProductException
     */
    public function updateProductType(string $id, array $data): ProductTypeDTO
    {
        try {
            $response = $this->httpClient->patch("/api/product-types/{$id}", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductTypeDTO::fromArray($data);
        } catch (\Exception $e) {
            throw new ProductException("Failed to update product type: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Delete a product type
     *
     * @throws ProductException
     */
    public function deleteProductType(string $id): void
    {
        try {
            $this->httpClient->delete("/api/product-types/{$id}");
        } catch (\Exception $e) {
            throw new ProductException("Failed to delete product type: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Get all variants for a product
     *
     * @return array<ProductVariantDTO>
     * @throws ProductException
     */
    public function getProductVariants(string $productId): array
    {
        try {
            $response = $this->httpClient->get("/api/products/{$productId}/variants");
            $data = json_decode((string) $response->getBody(), true);
            return array_map(fn (array $variant) => ProductVariantDTO::fromArray($variant), $data['data']);
        } catch (\Exception $e) {
            throw new ProductException("Failed to get product variants: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Get a specific variant for a product
     *
     * @throws ProductException
     */
    public function getProductVariant(string $productId, string $variantId): ProductVariantDTO
    {
        try {
            $response = $this->httpClient->get("/api/products/{$productId}/variants/{$variantId}");
            $data = json_decode((string) $response->getBody(), true);
            return ProductVariantDTO::fromArray($data);
        } catch (\Exception $e) {
            throw new ProductException("Failed to get product variant: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Create a new variant for a product
     *
     * @param array{
     *     price: float,
     *     sku: string,
     *     stock: int,
     *     attributes: array<string, mixed>
     * } $data
     * @throws ProductException
     */
    public function createProductVariant(string $productId, array $data): ProductVariantDTO
    {
        try {
            $response = $this->httpClient->post("/api/products/{$productId}/variants", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductVariantDTO::fromArray($data);
        } catch (\Exception $e) {
            throw new ProductException("Failed to create product variant: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Update a variant for a product
     *
     * @param array{
     *     price?: float,
     *     sku?: string,
     *     stock?: int,
     *     attributes?: array<string, mixed>
     * } $data
     * @throws ProductException
     */
    public function updateProductVariant(string $productId, string $variantId, array $data): ProductVariantDTO
    {
        try {
            $response = $this->httpClient->patch("/api/products/{$productId}/variants/{$variantId}", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductVariantDTO::fromArray($data);
        } catch (\Exception $e) {
            throw new ProductException("Failed to update product variant: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    /**
     * Delete a variant from a product
     *
     * @throws ProductException
     */
    public function deleteProductVariant(string $productId, string $variantId): void
    {
        try {
            $this->httpClient->delete("/api/products/{$productId}/variants/{$variantId}");
        } catch (\Exception $e) {
            throw new ProductException("Failed to delete product variant: {$e->getMessage()}", $e->getCode(), $e);
        }
    }
}
