<?php
declare(strict_types=1);

namespace App\Faceit\Application;

use App\Faceit\Domain\FaceitPlayer;
use App\Faceit\Domain\FaceitPlayerRepository;
use App\Faceit\Domain\FaceitPlayerStatistics;
use App\Faceit\Infrastructure\FaceitApiClient;

final class FaceitService
{
    private FaceitApiClient $apiClient;
    private FaceitPlayerRepository $playerRepository;

    public function __construct(FaceitApiClient $apiClient, FaceitPlayerRepository $playerRepository)
    {
        $this->apiClient = $apiClient;
        $this->playerRepository = $playerRepository;
    }

    public function getPlayerByNickname(string $nickname): FaceitPlayer
    {
        return $this->playerRepository->getByNickname($nickname);
    }

    public function getAllPlayersNicknames(): array
    {
        return $this->playerRepository->getAllPlayersNicknames();
    }

    public function addPlayer(string $nickname): void
    {
        if ($this->playerRepository->userExists($nickname)) {
            return;
        }

        $body = $this->apiClient->getPlayerInformationByNickname($nickname);
        $player = FaceitPlayer::createFromApi($body);

        $this->playerRepository->add($player);

        $this->updatePlayerStatistics($nickname);
    }

    public function updatePlayerStatistics(string $nickname): void
    {
        $player = $this->playerRepository->getByNickname($nickname);
        $statistics = FaceitPlayerStatistics::createFromApi(
            $this->apiClient->getPlayerStatistics($player->getFaceitId())
        );

        $this->playerRepository->updateStatistics($player->getId(), $statistics);
    }
}
