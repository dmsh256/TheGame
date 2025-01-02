<?php

namespace App\Entity;

class Team
{
    private string $name;
    private int $score;

    public function __construct()
    {
        $this->name = substr(md5(uniqid(rand(), true)), 0, 8);
        $this->score = 0;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Team
    {
        $this->name = $name;

        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): Team
    {
        $this->score = $score;

        return $this;
    }
}
