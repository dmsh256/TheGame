<?php

namespace App\Service;

use App\Entity\Division;
use App\Entity\Result;
use App\Exceptions\GameSimulationException;
use App\Service\Teams\TeamCreator;
use Doctrine\ORM\EntityManagerInterface;

final class GameplayService
{
    public function __construct(
        private readonly TeamCreator $teamCreator,
        private readonly GameSimulator $gameSimulator,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws GameSimulationException
     */
    public function generateMatchesPlayAndSaveResults(): bool
    {
        $teamsA = $this->teamCreator->create();
        $divisionA = new Division($teamsA);
        $divisionA->simulateGames();
        $winnersA = $divisionA->getOnly4WinnersAsc();

        $teamsB = $this->teamCreator->create();
        $divisionB = new Division($teamsB);
        $divisionB->simulateGames();
        $winnersB = $divisionB->getOnly4WinnersDesc();

        $result = $this->gameSimulator->simulateFinals($winnersA, $winnersB, []);

        $finalTable = [];
        for ($i = count($result) - 1; $i >= 0; --$i) {
            foreach ($result[$i] as $team) {
                if (!isset($finalTable[$team->getName()])) {
                    $finalTable[$team->getName()] = $team->getScore();
                }
            }
        }

        foreach ($finalTable as $name => $score) {
            $result = new Result();
            $result->setName($name);
            $result->setScore($score);

            $this->entityManager->persist($result);
        }

        $this->entityManager->flush();

        return true;
    }
}
