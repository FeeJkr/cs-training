<?php
declare(strict_types=1);

namespace App\Faceit\Application;

use App\Faceit\Domain\FaceitPlayer;
use App\Faceit\Domain\FaceitPlayerGame;
use App\Faceit\Domain\FaceitPlayerRepository;
use App\Faceit\Domain\FaceitPlayerStatistics;
use App\Faceit\Infrastructure\FaceitApiClient;
use JsonException;

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

        $this->addPlayerStatistics($nickname);
    }

    public function addPlayerStatistics(string $nickname): void
    {
        $player = $this->playerRepository->getByNickname($nickname);
        $statistics = FaceitPlayerStatistics::createFromApi(
            $this->apiClient->getPlayerStatistics($player->getFaceitId())
        );
        $playerGame = FaceitPlayerGame::createFromApi(
            $this->apiClient->getPlayerInformationByNickname($nickname)['games']['csgo']
        );

        $this->playerRepository->addGameInformation($player->getId(), $playerGame);
        $this->playerRepository->addStatistics($player->getId(), $statistics);
    }

    public function updatePlayerStatistics(string $nickname): void
    {
        $player = $this->playerRepository->getByNickname($nickname);
        $player->getStatistics()->updateFromApi($this->apiClient->getPlayerStatistics($player->getFaceitId()));
        $player->getGame()->updateFromApi($this->apiClient->getPlayerInformationByNickname($nickname)['games']['csgo']);

        $this->playerRepository->updateGameInformation($player);
        $this->playerRepository->updateStatistics($player);
    }

    public function getMonthStatistics(FaceitPlayer $player): FaceitPlayerStatistics
    {
        return $this->playerRepository->getMonthStatistics($player);
    }
}
