<?php
declare(strict_types=1);

namespace App\Faceit\Application;

use App\Faceit\Domain\FaceitApi;
use App\Faceit\Domain\FaceitMatch;
use App\Faceit\Domain\FaceitMatchRepository;
use App\Faceit\Domain\FaceitPlayerMatchResultsCollection;

final class FaceitMatchService
{
    private FaceitMatchRepository $repository;
    private FaceitService $faceitService;
    private FaceitApi $faceitApi;

    public function __construct(
        FaceitMatchRepository $repository,
        FaceitService $faceitService,
        FaceitApi $faceitApi
    ) {
        $this->repository = $repository;
        $this->faceitService = $faceitService;
        $this->faceitApi = $faceitApi;
    }

    public function update(string $nickname): void
    {
        $player = $this->faceitService->getPlayerByNickname($nickname);
        $matchesCount = 100;

        if ($player->getStatistics() !== null) {
            $matchesCount = $player->getStatistics()->getMatches();
        }

        $limit = $this->repository->countByPlayer($player) === 0
            ? $matchesCount
            : 30;

        if ($limit > 50) {
            $rounds = (int) floor($limit / 50);

            for ($i = 0; $i <= $rounds; $i++) {
                $this->processMatches($player->getFaceitId(), 50, $i * 50);
            }
        } else {
            $this->processMatches($player->getFaceitId(), $limit, 0);
        }
    }

    private function processMatches(string $faceitPlayerId, int $limit, int $offset): void
    {
        foreach ($this->faceitApi->getMatches($faceitPlayerId, $limit, $offset) as $match) {
            if ($match['status'] === 'finished' && ! $this->repository->matchExists($match['match_id'])) {
                $this->repository->add(FaceitMatch::createFromApi($match, $this->faceitApi));
            }
        }
    }

    public function getMatchesForPlayer(string $nickname): FaceitPlayerMatchResultsCollection
    {
        $player = $this->faceitService->getPlayerByNickname($nickname);

        return $this->repository->getByPlayer($player);
    }
}
