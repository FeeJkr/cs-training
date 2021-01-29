<?php
declare(strict_types=1);

namespace App\Common\UI\Web;

use App\Faceit\Application\PlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class IndexController extends AbstractController
{
    private PlayerService $faceitService;

    public function __construct(PlayerService $faceitService)
    {
        $this->faceitService = $faceitService;
    }

    public function dashboard(): Response
    {
        return $this->render('common/dashboard.html.twig', [
            'players' => $this->faceitService->getAllPlayersNicknames(),
        ]);
    }
}
