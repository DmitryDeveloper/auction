<?php

namespace App\Service;

use DateTime;
use App\Entity\Slot;
use App\Entity\User;

class SlotService extends BaseService
{
    public function create(
        string $title,
        string $description,
        User $seller,
        float $startPrice,
        ?float $buyImmediatelyPrice,
        string $finishDate,
        array $productIds
    ): Slot
    {
        $slot = new Slot();
        $slot->setSeller($seller);
        $slot->setTitle($title);
        $slot->setDescription($description);
        $slot->setStartPrice($startPrice);
        $slot->setBuyImmediatelyPrice($buyImmediatelyPrice);
        $slot->setFinishDate(new DateTime($finishDate));

        $products = $this->entityManager->getRepository(Slot::class)->findBy([
            'id' => $productIds,
            'seller' => $seller
        ]);

        foreach ($products as $product) {
            $slot->addProduct($product);
        }

        return $slot;
    }
}