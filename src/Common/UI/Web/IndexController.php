<?php
declare(strict_types=1);

namespace App\Common\UI\Web;

use App\Faceit\Application\PlayerService;
use App\Faceit\UI\Web\Presenter\Player\PlayerPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class IndexController extends AbstractController
{
    private PlayerService $playerService;
    private PlayerPresenter $playerPresenter;

    public function __construct(PlayerService $playerService, PlayerPresenter $playerPresenter)
    {
        $this->playerService = $playerService;
        $this->playerPresenter = $playerPresenter;
    }

    public function dashboard(): Response
    {
        return $this->render('common/dashboard.html.twig', [
            'players' => $this->playerPresenter->presentCollection($this->playerService->getAll()),
        ]);
    }
}
