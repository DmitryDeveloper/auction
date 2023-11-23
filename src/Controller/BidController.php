<?php

namespace App\Controller;

use App\BidValidationException;
use App\Entity\User;
use App\Form\BidType;
use App\Service\BidService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/bids')]
class BidController extends BaseController
{
    /**
     * @throws BidValidationException
     */
    #[IsGranted('ROLE_CUSTOMER')]
    #[Route('', name: 'slot_create', methods: ['POST'])]
    public function create(
        Request $request,
        BidService $bidService,
        #[CurrentUser] User $user
    ): JsonResponse
    {
        $form = $this->createForm(BidType::class);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form->getErrors(true, false));
            return $this->json(['errors' => $errors], 400);
        }

        $bid = $bidService->placeNewBid(
            $user,
            $form->get('slot_id')->getData(),
            $form->get('value')->getData()
        );

        return $this->json($bid);
    }
}
