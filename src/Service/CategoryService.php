<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

readonly class CategoryService
{
    public function __construct(private CategoryRepository $categoryRepository)
    {}

    public function create(string $name, string $description): Category
    {
        $category = new Category();
        $category->setName($name);
        $category->setDescription($description);

        $this->categoryRepository->save($category);

        return $category;
    }

    public function update(Category $category, string $description): Category
    {
        $category->setDescription($description);
        $this->categoryRepository->flush();

        return $category;
    }

    public function getAll(): array
    {
        return $this->categoryRepository->findAll();
    }
}