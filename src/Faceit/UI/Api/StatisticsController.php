<?php
declare(strict_types=1);

namespace App\Faceit\UI\Api;

use App\Faceit\Application\PlayerService;
use App\Faceit\Application\StatisticsService;
use App\Faceit\UI\Web\Presenter\Statistics\StatisticsPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class StatisticsController extends AbstractController
{
    private StatisticsService $service;
    private StatisticsPresenter $presenter;
    private PlayerService $playerService;

    public function __construct(PlayerService $playerService, StatisticsService $service, StatisticsPresenter $presenter)
    {
        $this->playerService = $playerService;
        $this->service = $service;
        $this->presenter = $presenter;
    }

    public function getByPlayer(Request $request): Response
    {
        $player = $this->playerService->getByNickname($request->get('nickname'));

        return new JsonResponse(
            $this->presenter->presentCollection($this->service->getByPlayer($player->getFaceitId()))
        );
    }
}
