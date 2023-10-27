<?php

namespace App\Service;

use App\Entity\Category;

class CategoryService extends BaseService
{
    public function create(string $name, string $description): Category
    {
        $category = new Category();
        $category->setName($name);
        $category->setDescription($description);

        return $category;
    }
}