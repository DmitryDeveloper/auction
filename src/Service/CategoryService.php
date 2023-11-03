<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService extends BaseService
{
    private readonly CategoryRepository $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        parent::__construct($entityManager);
        $this->categoryRepository = $categoryRepository;
    }

    public function create(string $name, string $description): Category
    {
        $category = new Category();
        $category->setName($name);
        $category->setDescription($description);

        $this->save($category);

        return $category;
    }

    public function update(Category $category, string $description): Category
    {
        $category->setDescription($description);
        $this->entityManager->flush();

        return $category;
    }

    public function getAll()
    {
        return $this->categoryRepository->findAll();
    }
}