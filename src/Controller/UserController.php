<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/users')]
class UserController extends AbstractController
{
    #[IsGranted('ROLE_CUSTOMER', statusCode: 403)]
    #[Route('/{id}', name: 'get_user', methods: ['GET'])]
    public function showDetails(User $user): JsonResponse
    {
        return $this->json([
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName()
        ]);
    }
}
