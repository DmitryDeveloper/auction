<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'category_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->json($categories);
    }

    #[IsGranted('ROLE_MODERATOR', statusCode: 403)]
    #[Route('', name: 'category_create', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        CategoryService $categoryService
    ): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());

        $category = $categoryService->create(
            name: $decodedBody->name,
            description: $decodedBody->description
        );

        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            return $this->json((string) $errors, 400);
        }

        $categoryService->save($category);

        return $this->json($category);
    }

    #[Route('/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category): JsonResponse
    {
        return $this->json($category);
    }

    #[IsGranted('ROLE_MODERATOR', statusCode: 403)]
    #[Route('/{id}', name: 'category_update', methods: ['PUT'])]
    public function update(EntityManagerInterface $entityManager, Request $request, Category $category): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());
        $category->setDescription($decodedBody->description);
        $entityManager->flush();

        return $this->json($category);
    }

    #[IsGranted('ROLE_MODERATOR', statusCode: 403)]
    #[Route('/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(Category $category, CategoryService $categoryService): JsonResponse
    {
        $categoryService->delete($category);

        return $this->json([]);
    }
}
