<?php

namespace App\Service\Teams;

use App\Entity\Team;

final class TeamCreator
{
    public function create(): array
    {
        $teams = [];
        for ($i = 0; $i < 8; ++$i) {
            $teams[$i] = new Team();
        }

        return $teams;
    }
}
