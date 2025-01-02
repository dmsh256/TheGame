<?php

namespace App\Controller;

use App\Service\GameplayService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameplayController extends AbstractController
{
    public function __construct(
        private readonly GameplayService $gameplayService,
    ) {
    }

    #[Route('/api/simulateGames', name: 'simulateGames')]
    public function simulateGames(): Response
    {
        try {
            $this->gameplayService->generateMatchesPlayAndSaveResults();
        } catch (\Exception $exception) {
            return new Response('Simulation unsuccessful. The error is: '.$exception->getMessage(), 500);
        }

        return new Response('Simulation successful. See database for details.', 200);
    }
}
