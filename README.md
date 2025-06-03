# Lumexa Product SDK

Ce SDK permet d'interagir facilement avec le service de gestion des produits de Lumexa.

## Installation

```bash
composer require lumexa/product-sdk
```

## Configuration

Pour utiliser le SDK, vous devez créer une instance du client avec votre token de store :

```php
use Lumexa\ProductSdk\ProductClient;

$client = new ProductClient(
    baseUrl: 'https://api.lumexa.com',
    storeToken: 'votre-store-token' // Token unique de votre store
);
```

## Gestion des produits

### Liste des produits

```php
$products = $client->getAllProducts();
```

### Récupérer un produit

```php
$product = $client->getProductById('product_id');
```

### Créer un produit

```php
$product = $client->createProduct([
    'name' => 'Mon produit',
    'description' => 'Description du produit'
]);
```

### Mettre à jour un produit

```php
$product = $client->updateProduct('product_id', [
    'name' => 'Nouveau nom',
    'description' => 'Nouvelle description'
]);
```

### Supprimer un produit

```php
$client->deleteProduct('product_id');
```

## Gestion des types de produits

### Liste des types

```php
$types = $client->getAllProductTypes();
```

### Récupérer un type

```php
$type = $client->getProductTypeById('type_id');
```

### Créer un type

```php
$type = $client->createProductType([
    'name' => 'Nom du type'
]);
```

### Mettre à jour un type

```php
$type = $client->updateProductType('type_id', [
    'name' => 'Nouveau nom'
]);
```

### Supprimer un type

```php
$client->deleteProductType('type_id');
```

## Gestion des variantes

### Liste des variantes d'un produit

```php
$variants = $client->getProductVariants('product_id');
```

### Récupérer une variante

```php
$variant = $client->getProductVariant('product_id', 'variant_id');
```

### Créer une variante

```php
$variant = $client->createProductVariant('product_id', [
    'price' => 19.99,
    'sku' => 'PROD-001-VAR',
    'stock' => 100,
    'attributes' => [
        'color' => 'Rouge',
        'size' => 'M'
    ]
]);
```

### Mettre à jour une variante

```php
$variant = $client->updateProductVariant('product_id', 'variant_id', [
    'price' => 24.99,
    'stock' => 50
]);
```

### Supprimer une variante

```php
$client->deleteProductVariant('product_id', 'variant_id');
```

## DTOs disponibles

Le SDK utilise des DTOs (Data Transfer Objects) pour représenter les données :

### ProductDTO

```php
$product->id;          // string
$product->name;        // string
$product->description; // string
$product->variants;    // array<ProductVariantDTO>
$product->created_at;  // string
$product->updated_at;  // string
```

### ProductVariantDTO

```php
$variant->id;         // string
$variant->price;      // float
$variant->sku;        // string
$variant->stock;      // int
$variant->attributes; // array<string, mixed>
$variant->created_at; // string
$variant->updated_at; // string
```

### ProductTypeDTO

```php
$type->id;         // string
$type->name;       // string
$type->created_at; // string
$type->updated_at; // string
```

## Gestion des erreurs

Le SDK utilise des exceptions typées pour la gestion des erreurs. Toutes les erreurs liées aux produits lèvent une `ProductException` :

```php
use Lumexa\ProductSdk\Exceptions\ProductException;

try {
    $product = $client->getProductById('product_id');
} catch (ProductException $e) {
    // Gérer l'erreur
    echo $e->getMessage();
}
```

## Support

Pour toute question ou problème, veuillez ouvrir une issue sur le dépôt GitHub du projet.
