<?php
declare(strict_types=1);

namespace App\Common\UI\Web;

use App\Faceit\Application\FaceitService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class IndexController extends AbstractController
{
    private FaceitService $faceitService;

    public function __construct(FaceitService $faceitService)
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
