<?php

namespace App\Service;

use App\Repository\ProductRepository;
use App\Repository\SlotRepository;
use DateTime;
use App\Entity\Slot;
use App\Entity\User;

readonly class SlotService
{
    public function __construct(
        private readonly SlotRepository    $slotRepository,
        private readonly ProductRepository $productRepository
    )
    {
    }

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

        $products = $this->productRepository->findBy([
            'id' => $productIds,
            'seller' => $seller
        ]);

        foreach ($products as $product) {
            $slot->addProduct($product);
        }

        $this->slotRepository->save($slot);

        return $slot;
    }
}