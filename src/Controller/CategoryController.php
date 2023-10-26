<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_create', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());

        $category = new Category();
        $category->setName($decodedBody->name);
        $category->setDescription($decodedBody->description);

        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            return $this->json((string) $errors, 400);
        }

        // tell Doctrine you want to (eventually) save the Category (no queries yet)
        $entityManager->persist($category);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->json($category);
    }

    #[Route('/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category): JsonResponse
    {
        return $this->json($category);
    }

    #[Route('/{id}', name: 'category_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, Request $request, Category $category): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());
        $category->setDescription($decodedBody->description);
        $entityManager->flush();

        return $this->json($category);
    }

    #[Route('/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Category $category): JsonResponse
    {
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json([]);
    }
}
