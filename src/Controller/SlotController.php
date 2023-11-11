<?php

namespace App\Controller;

use App\Entity\Slot;
use App\Entity\User;
use App\Form\SlotType;
use App\Service\SlotService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/slots')]
class SlotController extends BaseController
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
        SlotService $slotService,
        #[CurrentUser] User $user
    )
    {
        $form = $this->createForm(SlotType::class);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form->getErrors(true, false));
            return $this->json(['errors' => $errors], 400);
        }

        $slot = $slotService->create(
            $form->get('title')->getData(),
            $form->get('description')->getData(),
            $user,
            $form->get('startPrice')->getData(),
            $form->get('buyImmediatelyPrice')->getData(),
            $form->get('finishDate')->getData(),
            $form->get('productIds')->getData()
        );

        return $this->json($slot);
    }
}
