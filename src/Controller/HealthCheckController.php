<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api", name="api_")
 */

class HealthCheckController extends AbstractController
{
    /**
     * @Route("/health", name="dashboard")
     */
    public function index(): Response
    {
        return $this->json('ok');
    }
}