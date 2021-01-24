<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web;

use App\Faceit\Application\FaceitMatchService;
use App\Faceit\UI\Web\Presenter\FaceitMatchPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FaceitMatchController extends AbstractController
{
    private FaceitMatchService $service;
    private FaceitMatchPresenter $faceitMatchPresenter;

    public function __construct(FaceitMatchService $service, FaceitMatchPresenter $faceitMatchPresenter)
    {
        $this->service = $service;
        $this->faceitMatchPresenter = $faceitMatchPresenter;
    }

    public function getPlayerMatches(Request $request): Response
    {
        $matchesResultsCollection = $this->service->getMatchesForPlayer($request->get('nickname'));

        return new JsonResponse($this->faceitMatchPresenter->presentPlayerResults($matchesResultsCollection));
    }
}
