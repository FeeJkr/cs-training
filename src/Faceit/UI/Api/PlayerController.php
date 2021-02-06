<?php
declare(strict_types=1);

namespace App\Faceit\UI\Api;

use App\Faceit\Application\PlayerService;
use App\Faceit\UI\Api\Presenter\Player\PlayerPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PlayerController extends AbstractController
{
    private PlayerService $playerService;
    private PlayerPresenter $playerPresenter;

    public function __construct(PlayerService $playerService, PlayerPresenter $playerPresenter)
    {
        $this->playerService = $playerService;
        $this->playerPresenter = $playerPresenter;
    }

    public function getAll(): Response
    {
        return new JsonResponse(
            $this->playerPresenter->presentCollection($this->playerService->getAll())
        );
    }

    public function getPlayerInformation(Request $request): Response
    {
        $nickname = $request->get('nickname');

        return new JsonResponse(
            $this->playerPresenter->present($this->playerService->getByNickname($nickname))
        );
    }
}
