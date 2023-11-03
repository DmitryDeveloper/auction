<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    public function __construct(readonly ProductService $productService)
    {
    }

    #[IsGranted('ROLE_CUSTOMER')]
    #[Route('', name: 'create_product', methods: ['POST'])]
    public function create(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());

        $product = $this->productService->create(
            $decodedBody->name,
            $decodedBody->short_description,
            $decodedBody->description,
            $decodedBody->categories,
            $user
        );

        return $this->json([
            'name' => $product->getName(),
            'short_description' => $product->getShortDescription(),
            'description' => $product->getDescription()
        ]);
    }

    #[Route('/{id}', name: 'product_update', methods: ['PUT'])]
    #[IsGranted('edit', subject: 'product', message: 'Products can only be edited by their owner.')]
    public function update(Request $request, Product $product): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());

        $product = $this->productService->update(
            $product,
            $decodedBody->name,
            $decodedBody->short_description,
            $decodedBody->description,
            $decodedBody->categories
        );

        return $this->json([
            'name' => $product->getName(),
            'short_description' => $product->getShortDescription(),
            'description' => $product->getDescription()
        ]);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): JsonResponse
    {
        return $this->json([
            'name' => $product->getName(),
            'short_description' => $product->getShortDescription(),
            'description' => $product->getDescription()
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['DELETE'])]
    #[IsGranted('delete', subject: 'product', message: 'Products can only be deleted by their owner.')]
    public function delete(Product $product): JsonResponse
    {
        $this->productService->delete($product);

        return $this->json([]);
    }
}
