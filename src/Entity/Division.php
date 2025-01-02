<?php

namespace App\Entity;

final class Division
{
    /**
     * @var Team[]
     */
    private array $teams;

    public function __construct(
        array $teams,
    ) {
        $this->teams = $teams;
    }

    public function simulateGames(): void
    {
        for ($i = 0; $i < count($this->teams); ++$i) {
            for ($j = $i; $j < count($this->teams); ++$j) {
                if ($this->teams[$i]->getName() !== $this->teams[$j]->getName()) {
                    $teamNumber = rand(1, 2);
                    if (1 == $teamNumber) {
                        $this->teams[$i]->setScore($this->teams[$i]->getScore() + 1);
                    } else {
                        $this->teams[$j]->setScore($this->teams[$j]->getScore() + 1);
                    }
                }
            }
        }
    }

    public function getOnly4WinnersAsc(): array
    {
        uasort($this->teams, function ($a, $b) {
            if ($a->getScore() == $b->getScore()) {
                return 0;
            }

            return ($a->getScore() > $b->getScore()) ? -1 : 1;
        });

        return array_slice($this->teams, 0, 4);
    }

    public function getOnly4WinnersDesc(): array
    {
        uasort($this->teams, function ($a, $b) {
            if ($a->getScore() == $b->getScore()) {
                return 0;
            }

            return ($a->getScore() < $b->getScore()) ? -1 : 1;
        });

        return array_slice($this->teams, count($this->teams) - 4, 4);
    }
}
