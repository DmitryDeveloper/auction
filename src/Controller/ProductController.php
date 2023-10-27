<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    #[IsGranted('ROLE_CUSTOMER')]
    #[Route('', name: 'create_product', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());
        $categories = $entityManager->getRepository(Category::class)->findBy(['id' => $decodedBody->categories]);

        $product = new Product();
        $product->setName($decodedBody->name);
        $product->setShortDescription($decodedBody->short_description);
        $product->setDescription($decodedBody->description);
        $product->addCategories($categories);
        $product->setSeller($user);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->json([
            'name' => $product->getName(),
            'short_description' => $product->getShortDescription(),
            'description' => $product->getDescription()
        ]);
    }

    #[Route('/{id}', name: 'product_update', methods: ['PUT'])]
    #[IsGranted('edit', subject: 'product', message: 'Products can only be edited by their owner.')]
    public function update(EntityManagerInterface $entityManager, Request $request, Product $product): JsonResponse
    {
        $decodedBody = json_decode($request->getContent());
        $categories = $entityManager->getRepository(Category::class)->findBy(['id' => $decodedBody->categories]);

        $product->setShortDescription($decodedBody->short_description);
        $product->setDescription($decodedBody->description);
        $product->addCategories($categories);
        $entityManager->flush();

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
    public function delete(EntityManagerInterface $entityManager, Product $product, #[CurrentUser] User $user): JsonResponse
    {
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json([]);
    }
}
