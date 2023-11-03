<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProductService extends BaseService
{
    private readonly ProductRepository $productRepository;
    private readonly CategoryRepository $categoryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    )
    {
        parent::__construct($entityManager);
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

        $this->save($product);

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
        $this->entityManager->flush();

        return $product;
    }

    public function getAll()
    {
        return $this->productRepository->findAll();
    }
}