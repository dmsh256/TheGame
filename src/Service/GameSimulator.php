<?php

namespace App\Service;

use App\Entity\Team;
use App\Exceptions\GameSimulationException;

final class GameSimulator
{
    public function simulateGame(Team $teamA, Team $teamB): Team
    {
        $teamNumber = rand(1, 2);
        if ($teamNumber == 1) {
            $teamA->setScore($teamA->getScore() + 1);

            return $teamA;
        } else {
            $teamB->setScore($teamB->getScore() + 1);

            return $teamB;
        }
    }

    /**
     * @throws GameSimulationException
     */
    public function simulateFinals(array $divisionA, array $divisionB, array $results): array
    {
        if (count($divisionA) != count($divisionB)) {
            throw new GameSimulationException('Division numbers do not match.');
        }

        if (count($divisionA) === 0 || count($divisionB) === 0) {
            throw new GameSimulationException('Division size should not be 0.');
        }

        $results[] = $divisionA;
        $results[] = $divisionB;

        if (count($divisionA) === 1 && count($divisionB) === 1) {
            $results[] = [$this->simulateGame($divisionA[0], $divisionB[0])];

            return $results;
        } else {
            $divisionAWinners = [];
            $divisionBWinners = [];
            $half = count($divisionA) / 2;
            $divisionSize = count($divisionA);

            for ($i = 0; $i < $half; ++$i) {
                $teamA = $divisionA[$i];
                $teamB = $divisionB[$divisionSize - $i - 1];

                $divisionAWinners[] = $this->simulateGame($teamA, $teamB);
            }

            for ($i = $half; $i < $divisionSize; ++$i) {
                $teamA = $divisionA[$i];
                $teamB = $divisionB[$divisionSize - $i - 1];

                $divisionBWinners[] = $this->simulateGame($teamA, $teamB);
            }

            return $this->simulateFinals($divisionAWinners, $divisionBWinners, $results);
        }
    }
}
