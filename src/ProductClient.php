<?php

declare(strict_types=1);

namespace Lumexa\ProductSdk;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Lumexa\ProductSdk\DTOs\ProductDTO;
use GuzzleHttp\Exception\ClientException;
use Lumexa\ProductSdk\DTOs\ProductTypeDTO;
use Lumexa\ProductSdk\DTOs\ProductImageDTO;
use Lumexa\ProductSdk\DTOs\ProductVariantDTO;
use Lumexa\ProductSdk\Exceptions\ProductException;
use Lumexa\ProductSdk\Exceptions\ValidationException;

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
     * Handle API errors and transform them into appropriate exceptions
     *
     * @throws ValidationException|ProductException
     */
    private function handleApiError(\Throwable $e): never
    {
        if ($e instanceof ClientException) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = json_decode((string) $response->getBody(), true);

            if ($statusCode === 422 && isset($body['errors'])) {
                throw new ValidationException(
                    $body['message'] ?? 'Validation failed',
                    $body['errors'],
                    $statusCode,
                    $e
                );
            }

            if (isset($body['message'])) {
                throw new ProductException($body['message'], $statusCode, $e);
            }
        }

        throw new ProductException($e->getMessage(), (int) $e->getCode(), $e);
    }

    /**
     * Get all products
     *
     * @return array<ProductDTO>
     * @throws ProductException|ValidationException
     */
    public function getAllProducts(): array
    {
        try {
            $response = $this->httpClient->get('/api/products');
            $data = json_decode((string) $response->getBody(), true);
            return array_map(fn (array $product) => ProductDTO::fromArray($product), $data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    public function getProductAvailable(): array
    {
        try {
            $response = $this->httpClient->get("/api/products?is_active=1");
            $data = json_decode((string) $response->getBody(), true);
            return array_map(fn (array $product) => ProductDTO::fromArray($product), $data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    public function getProductBySlug(string $slug): ProductDTO
    {
        try {
            $response = $this->httpClient->get("/api/products/slug/{$slug}");
            $data = json_decode((string) $response->getBody(), true);
            return ProductDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    public function getProductsFuture(): array
    {
        try {
            $response = $this->httpClient->get("/api/products/future");
            $data = json_decode((string) $response->getBody(), true);
            return array_map(fn (array $product) => ProductDTO::fromArray($product), $data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Get a product by ID
     *
     * @throws ProductException|ValidationException
     */
    public function getProductById(string $id): ProductDTO
    {
        try {
            $response = $this->httpClient->get("/api/products/{$id}");
            $data = json_decode((string) $response->getBody(), true);
            return ProductDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Create a new product
     *
     * @param array{
     *     name: string,
     *     description: string,
     *     price: float
     * } $data
     * @throws ProductException|ValidationException
     */
    public function createProduct(array $data): ProductDTO
    {
        try {
            $response = $this->httpClient->post('/api/products', [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Update a product
     *
     * @param array{
     *     name?: string,
     *     description?: string,
     *     price?: float
     * } $data
     * @throws ProductException|ValidationException
     */
    public function updateProduct(string $id, array $data): ProductDTO
    {
        try {
            $response = $this->httpClient->put("/api/products/{$id}", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Delete a product
     *
     * @throws ProductException|ValidationException
     */
    public function deleteProduct(string $id): void
    {
        try {
            $this->httpClient->delete("/api/products/{$id}");
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Get all product types
     *
     * @return array<ProductTypeDTO>
     * @throws ProductException|ValidationException
     */
    public function getAllProductTypes(): array
    {
        try {
            $response = $this->httpClient->get('/api/product-types');
            $data = json_decode((string) $response->getBody(), true);
            return array_map(fn (array $type) => ProductTypeDTO::fromArray($type), $data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Get a product type by ID
     *
     * @throws ProductException|ValidationException
     */
    public function getProductTypeById(string $id): ProductTypeDTO
    {
        try {
            $response = $this->httpClient->get("/api/product-types/{$id}");
            $data = json_decode((string) $response->getBody(), true);
            return ProductTypeDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Create a new product type
     *
     * @param array{name: string} $data
     * @throws ProductException|ValidationException
     */
    public function createProductType(array $data): ProductTypeDTO
    {
        try {
            $response = $this->httpClient->post('/api/product-types', [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductTypeDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Update a product type
     *
     * @param array{name?: string} $data
     * @throws ProductException|ValidationException
     */
    public function updateProductType(string $id, array $data): ProductTypeDTO
    {
        try {
            $response = $this->httpClient->put("/api/product-types/{$id}", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductTypeDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Delete a product type
     *
     * @throws ProductException|ValidationException
     */
    public function deleteProductType(string $id): void
    {
        try {
            $this->httpClient->delete("/api/product-types/{$id}");
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Get all variants for a product
     *
     * @return array<ProductVariantDTO>
     * @throws ProductException|ValidationException
     */
    public function getProductVariants(int $productId): array
    {
        try {
            $response = $this->httpClient->get("/api/products/{$productId}/variants");
            $data = json_decode((string) $response->getBody(), true);
            return array_map(fn (array $variant) => ProductVariantDTO::fromArray($variant), $data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Get a specific variant for a product
     *
     * @throws ProductException|ValidationException
     */
    public function getProductVariant(string $productId, string $variantId): ProductVariantDTO
    {
        try {
            $response = $this->httpClient->get("/api/products/{$productId}/variants/{$variantId}");
            $data = json_decode((string) $response->getBody(), true);
            return ProductVariantDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Create a new variant for a product
     *
     * @param array{
     *     sku: string,
     *     stock: int,
     *     attributes: array<string, mixed>
     * } $data
     * @throws ProductException|ValidationException
     */
    public function createProductVariant(string $productId, array $data): ProductVariantDTO
    {
        try {
            $response = $this->httpClient->post("/api/products/{$productId}/variants", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductVariantDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Update a variant for a product
     *
     * @param array{
     *     sku?: string,
     *     stock?: int,
     *     attributes?: array<string, mixed>
     * } $data
     * @throws ProductException|ValidationException
     */
    public function updateProductVariant(string $productId, string $variantId, array $data): ProductVariantDTO
    {
        try {
            $response = $this->httpClient->put("/api/products/{$productId}/variants/{$variantId}", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductVariantDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    /**
     * Delete a variant from a product
     *
     * @throws ProductException|ValidationException
     */
    public function deleteProductVariant(string $productId, string $variantId): void
    {
        try {
            $this->httpClient->delete("/api/products/{$productId}/variants/{$variantId}");
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    public function createProductImage(string $productId, array $data): ProductImageDTO
    {
        try {
            $response = $this->httpClient->post("/api/products/{$productId}/images", [
                'json' => $data,
            ]);
            $data = json_decode((string) $response->getBody(), true);
            return ProductImageDTO::fromArray($data['data']);
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }

    public function deleteProductImage(string $productId, string $imageId): void
    {
        try {
            $this->httpClient->delete("/api/products/{$productId}/images/{$imageId}");
        } catch (\Throwable $e) {
            $this->handleApiError($e);
        }
    }
}
