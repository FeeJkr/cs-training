<?php
declare(strict_types=1);

namespace App\Faceit\Application;

use App\Faceit\Domain\FaceitApi;
use App\Faceit\Domain\FaceitMatch;
use App\Faceit\Domain\FaceitMatchRepository;

final class FaceitMatchService
{
    private FaceitMatchRepository $repository;
    private FaceitService $faceitService;
    private FaceitApi $faceitApi;

    public function __construct(FaceitMatchRepository $repository, FaceitService $faceitService, FaceitApi $faceitApi)
    {
        $this->repository = $repository;
        $this->faceitService = $faceitService;
        $this->faceitApi = $faceitApi;
    }

    public function update(string $nickname): void
    {
        $player = $this->faceitService->getPlayerByNickname($nickname);
        $limit = $this->repository->countByPlayer($player) === 0 ? 60 : 30;

        foreach ($this->faceitApi->getMatches($player->getFaceitId(), $limit) as $match) {
            if ($match['status'] === 'finished' && ! $this->repository->matchExists($match['match_id'])) {
                $this->repository->add(FaceitMatch::createFromApi($match, $this->faceitApi));
            }
        }
    }
}
