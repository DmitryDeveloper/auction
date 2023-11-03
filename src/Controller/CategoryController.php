<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    public function __construct(readonly CategoryService $categoryService)
    {
    }

    #[Route('', name: 'category_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->categoryService->getAll();
        return $this->json($categories);
    }

    #[IsGranted('ROLE_MODERATOR', statusCode: 403)]
    #[Route('', name: 'category_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());
        $category = $this->categoryService->create(
            name: $decodedBody->name,
            description: $decodedBody->description
        );

        return $this->json($category);
    }

    #[Route('/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category): JsonResponse
    {
        return $this->json($category);
    }

    #[IsGranted('ROLE_MODERATOR', statusCode: 403)]
    #[Route('/{id}', name: 'category_update', methods: ['PUT'])]
    public function update(Request $request, Category $category): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());
        $this->categoryService->update($category, $decodedBody->description);

        return $this->json($category);
    }

    #[IsGranted('ROLE_MODERATOR', statusCode: 403)]
    #[Route('/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(Category $category): JsonResponse
    {
        $this->categoryService->delete($category);
        return $this->json([]);
    }
}
