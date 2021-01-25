<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web;

use App\Faceit\Application\FaceitMatchService;
use App\Faceit\Application\FaceitService;
use App\Faceit\UI\Console\AddFaceitMatchesCommand;
use App\Faceit\UI\Web\Presenter\FaceitMatchPresenter;
use App\Faceit\UI\Web\Presenter\FaceitPlayerPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

final class FaceitController extends AbstractController
{
    private FaceitService $service;
    private FaceitPlayerPresenter $playerPresenter;
    private FaceitMatchService $faceitMatchService;
    private FaceitMatchPresenter $faceitMatchPresenter;

    public function __construct(
        FaceitService $service,
        FaceitPlayerPresenter $playerPresenter,
        FaceitMatchService $faceitMatchService,
        FaceitMatchPresenter $faceitMatchPresenter
    ) {
        $this->service = $service;
        $this->playerPresenter = $playerPresenter;
        $this->faceitMatchService = $faceitMatchService;
        $this->faceitMatchPresenter = $faceitMatchPresenter;
    }

    public function dashboard(Request $request): Response
    {
        $player = $this->service->getPlayerByNickname($request->get('nickname'));

        return $this->render('faceit/dashboard.html.twig', [
            'player' => $this->playerPresenter->present($player),
            'matchesStatistics' => $this->faceitMatchPresenter->presentPlayerResults(
                $this->faceitMatchService->getMatchesForPlayer($request->get('nickname'))
            ),
        ]);
    }

    public function addPlayer(Request $request): Response
    {
        $nickname = $request->get('nickname');

        $this->service->addPlayer($nickname);

        return $this->redirectToRoute('faceit.dashboard', ['nickname' => $nickname]);
    }
}
