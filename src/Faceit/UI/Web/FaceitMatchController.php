<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web;

use App\Faceit\Application\FaceitMatchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FaceitMatchController extends AbstractController
{
    private FaceitMatchService $service;

    public function __construct(FaceitMatchService $service)
    {
        $this->service = $service;
    }

    public function update(Request $request): Response
    {
        $nickname = $request->get('nickname');

        $this->service->update($nickname);

        return $this->redirectToRoute('faceit.dashboard', ['nickname' => $nickname]);
    }
}
