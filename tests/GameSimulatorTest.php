<?php

namespace App\Tests;

use App\Entity\Team;
use App\Exceptions\GameSimulationException;
use App\Service\GameSimulator;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class GameSimulatorTest extends TestCase
{
    private GameSimulator $gameSimulator;

    protected function setUp(): void
    {
        $this->gameSimulator = new GameSimulator();
    }

    public function testSimulateGameIncrementsTeamScore(): void
    {
        $teamA = new Team();
        $teamB = new Team();

        $teamA->setName('A');
        $teamB->setName('B');

        $winner = $this->gameSimulator->simulateGame($teamA, $teamB);

        if ($winner->getName() === 'A') {
            $this->assertEquals(1, $teamA->getScore());
            $this->assertEquals(0, $teamB->getScore());
        } else {
            $this->assertEquals(1, $teamB->getScore());
            $this->assertEquals(0, $teamA->getScore());
        }
    }

    /**
     * @throws Exception
     */
    public function testSimulateFinalsThrowsExceptionForUnequalDivisions(): void
    {
        $this->expectException(GameSimulationException::class);
        $this->expectExceptionMessage('Division numbers do not match.');

        $divisionA = [$this->createMock(Team::class)];
        $divisionB = [$this->createMock(Team::class), $this->createMock(Team::class)];

        $this->gameSimulator->simulateFinals($divisionA, $divisionB, []);
    }

    public function testSimulateFinalsThrowsExceptionForEmptyDivisions(): void
    {
        $this->expectException(GameSimulationException::class);
        $this->expectExceptionMessage('Division size should not be 0.');

        $divisionA = [];
        $divisionB = [];

        $this->gameSimulator->simulateFinals($divisionA, $divisionB, []);
    }

    /**
     * @throws Exception
     * @throws GameSimulationException
     */
    public function testSimulateFinalsHandlesSingleGame(): void
    {
        $teamA = $this->createMock(Team::class);
        $teamB = $this->createMock(Team::class);

        $teamA->method('getScore')->willReturn(0);
        $teamB->method('getScore')->willReturn(0);

        $divisionA = [$teamA];
        $divisionB = [$teamB];
        $results = [];

        $finalResults = $this->gameSimulator->simulateFinals($divisionA, $divisionB, $results);

        $this->assertCount(3, $finalResults);
        $this->assertContains($divisionA, $finalResults);
        $this->assertContains($divisionB, $finalResults);
        $this->assertCount(1, $finalResults[2]);
    }

    /**
     * @throws Exception
     * @throws GameSimulationException
     */
    public function testSimulateFinalsHandlesMultipleRounds(): void
    {
        $divisionA = [
            $this->createMock(Team::class),
            $this->createMock(Team::class),
        ];
        $divisionB = [
            $this->createMock(Team::class),
            $this->createMock(Team::class),
        ];
        $results = [];

        $finalResults = $this->gameSimulator->simulateFinals($divisionA, $divisionB, $results);

        $this->assertNotEmpty($finalResults);
        $this->assertGreaterThanOrEqual(1, count($finalResults));
    }
}
