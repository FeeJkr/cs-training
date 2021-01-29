<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web;

use App\Faceit\Application\Exception\ApplicationException;
use App\Faceit\Application\MatchService;
use App\Faceit\Application\PlayerService;
use App\Faceit\Application\StatisticsService;
use App\Faceit\Domain\Match\GetByPlayer\Scope;
use App\Faceit\UI\Web\Presenter\DashboardPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DashboardController extends AbstractController
{
    private PlayerService $playerService;
    private MatchService $matchService;
    private StatisticsService $statisticsService;
    private DashboardPresenter $presenter;

    public function __construct(
        PlayerService $playerService,
        MatchService $matchService,
        StatisticsService $statisticsService,
        DashboardPresenter $presenter
    ) {
        $this->playerService = $playerService;
        $this->matchService = $matchService;
        $this->statisticsService = $statisticsService;
        $this->presenter = $presenter;
    }

    public function dashboard(Request $request): Response
    {
        try {
            $nickname = $request->get('nickname');

            $player = $this->playerService->getByNickname($nickname);
            $matches = $this->matchService->getByPlayer($player->getFaceitId());
            $statistics = $this->statisticsService->getByPlayer($player->getFaceitId());

            return $this->render('faceit/dashboard.html.twig', $this->presenter->present($player, $matches, $statistics));
        } catch (ApplicationException $exception) {
            return $this->redirectToRoute('dashboard');
        }
    }
}
