<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\CreateProductType;
use App\Form\UpdateProductType;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/products')]
class ProductController extends BaseController
{
    public function __construct(readonly ProductService $productService)
    {
    }

    #[IsGranted('ROLE_CUSTOMER')]
    #[Route('', name: 'create_product', methods: ['POST'])]
    public function create(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $form = $this->createForm(CreateProductType::class);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form->getErrors(true, false));
            return $this->json(['errors' => $errors], 400);
        }

        $product = $this->productService->create(
            $form->get('name')->getData(),
            $form->get('short_description')->getData(),
            $form->get('description')->getData(),
            $form->get('categories')->getData(),
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
        $form = $this->createForm(UpdateProductType::class);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form->getErrors(true, false));
            return $this->json(['errors' => $errors], 400);
        }

        $product = $this->productService->update(
            $product,
            $form->get('name')->getData(),
            $form->get('short_description')->getData(),
            $form->get('description')->getData(),
            $form->get('categories')->getData()
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
