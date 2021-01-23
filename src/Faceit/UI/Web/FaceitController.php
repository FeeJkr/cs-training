<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web;

use App\Faceit\Application\FaceitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FaceitController extends AbstractController
{
    private FaceitService $service;

    public function __construct(FaceitService $service)
    {
        $this->service = $service;
    }

    public function dashboard(Request $request): Response
    {
        dd($this->service->getPlayerByNickname($request->get('nickname')));
    }

    public function addPlayer(Request $request): Response
    {
        $nickname = $request->get('nickname');

        $this->service->addPlayer($nickname);

        return $this->redirectToRoute('faceit.dashboard', ['nickname' => $nickname]);
    }

    public function updatePlayerStatistics(Request $request): Response
    {
        $nickname = $request->get('nickname');

        $this->service->updatePlayerStatistics($nickname);

        return $this->redirectToRoute('faceit.dashboard', ['nickname' => $nickname]);
    }
}
