<?php

namespace App\Controller;

use App\Entity\Slot;
use App\Entity\User;
use App\Service\SlotService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/slots')]
class SlotController extends AbstractController
{
    #[Route('', name: 'slot_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $slots = $entityManager->getRepository(Slot::class)->findAll();

        return $this->json($slots);
    }

    #[IsGranted('ROLE_CUSTOMER')]
    #[Route('', name: 'slot_create', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        SlotService $slotService,
        #[CurrentUser] User $user
    )
    {
        $decodedBody = json_decode($request->getContent());

        $slot = $slotService->create(
            title: $decodedBody['title'],
            description: $decodedBody['description'],
            seller: $user,
            startPrice: $decodedBody['start_price'],
            buyImmediatelyPrice: $decodedBody['buy_immediately_price'],
            finishDate: $decodedBody['finish_date'],
            productIds: $decodedBody['product_ids']
        );

        $errors = $validator->validate($slot);
        if (count($errors) > 0) {
            return $this->json((string) $errors, 400);
        }

        $slotService->save($slot);

        return $this->json($slot);
    }
}
