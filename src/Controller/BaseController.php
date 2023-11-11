<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormErrorIterator;

class BaseController extends AbstractController
{
    protected function getErrorsFromForm(FormErrorIterator $formErrors): array
    {
        $errors = [];

        foreach ($formErrors as $error) {
            $errors[] = [
                'field' => $error->getOrigin()->getName(),
                'message' => $error->getMessage(),
            ];
        }

        return $errors;
    }
}
