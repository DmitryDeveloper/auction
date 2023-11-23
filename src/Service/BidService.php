<?php

namespace App\Service;

use App\BidValidationException;
use App\Entity\Bid;
use App\Entity\User;
use App\Enum\SlotState;
use App\Repository\BidRepository;
use App\Repository\SlotRepository;

readonly class BidService
{
    public function __construct(
        private readonly BidRepository $bidRepository,
        private readonly SlotRepository $slotRepository,
        private readonly PaymentService $paymentService
    )
    {
    }

    /**
     * @throws BidValidationException
     */
    public function placeNewBid(
        User $buyer,
        int $slotId,
        float $value
    ): Bid
    {
        $bid = Bid::create(
            $buyer,
            $this->slotRepository->find($slotId),
            $value
        );

        $slot = $bid->getSlot();
        $this->validateBid($bid);
        $this->bidRepository->save($bid);

        if ($slot->canBeBoughtImmediately($bid)) {
            $slot->markAsReserved();
            $this->slotRepository->flush();
            return $bid;
        }

        try {
            $res = $this->paymentService->blockMoney($buyer, $bid->getValue());
            $res ? $bid->markAsSuccessful() : $bid->markAsFailed();
        } catch (\Throwable $e) {
            $bid->markAsFailed();
        }

        return $bid;
    }

    /**
     * @throws BidValidationException
     */
    protected function validateBid(Bid $bid): void
    {
        $slot = $this->slotRepository->find($bid->getSlot()->getId());

        if (!$slot) {
            throw new BidValidationException('Slot not found');
        }

        if ($slot->getState() !== SlotState::STARTED->value) {
            throw new BidValidationException('Slot cannot be processed');
        }

        if ($slot->getStartPrice() > $bid->getValue()) {
            throw new BidValidationException('Start price is higher');
        }
    }
}