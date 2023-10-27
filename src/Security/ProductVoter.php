<?php
// src/Security/PostVoter.php
namespace App\Security;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    // these strings are just invented: you can use anything
    private const DELETE = 'delete';
    private const EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::DELETE, self::EDIT])) {
            return false;
        }

        // only vote on `Product` objects
        if (!$subject instanceof Product) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Product object, thanks to `supports()`
        /** @var Product $product */
        $product = $subject;

        return match($attribute) {
            self::EDIT, self::DELETE => $this->canEdit($product, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canEdit(Product $product, User $user): bool
    {
        // this assumes that the Post object has a `getOwner()` method
        return $user === $product->getSeller();
    }
}
