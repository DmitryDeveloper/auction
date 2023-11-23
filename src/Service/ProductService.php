<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

readonly class ProductService
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function create(
        string $name,
        string $shortDescription,
        string $description,
        array $categoryIds,
        User $user
    ): Product
    {
        $categories = $this->categoryRepository->findBy(['id' => $categoryIds]);

        $product = new Product();
        $product->setName($name);
        $product->setShortDescription($shortDescription);
        $product->setDescription($description);
        $product->addCategories($categories);
        $product->setSeller($user);

        $this->productRepository->save($product);

        return $product;
    }

    public function update(
        Product $product,
        string $name,
        string $shortDescription,
        string $description,
        array $categoryIds,
    ): Product
    {
        $categories = $this->categoryRepository->findBy(['id' => $categoryIds]);
        $product->setName($name);
        $product->setShortDescription($shortDescription);
        $product->setDescription($description);
        $product->addCategories($categories);

        $this->productRepository->flush();

        return $product;
    }

    public function getAll(): array
    {
        return $this->productRepository->findAll();
    }
}