<?php
declare(strict_types=1);

namespace App\Faceit\UI\Api;

use App\Faceit\Application\MatchService;
use App\Faceit\Application\PlayerService;
use App\Faceit\UI\Api\Presenter\Match\MatchPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class MatchController extends AbstractController
{
    public function __construct(
        private PlayerService $playerService,
        private MatchService $service,
        private MatchPresenter $presenter
    ){}

    public function getByPlayer(Request $request): Response
    {
        $player = $this->playerService->getByNickname($request->get('nickname'));

        return new JsonResponse(
            $this->presenter->presentCollection(
                $this->service->getByPlayer($player->getFaceitId()),
                $player->getFaceitId()
            )
        );
    }
}
