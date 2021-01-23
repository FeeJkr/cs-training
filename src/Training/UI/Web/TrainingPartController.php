<?php
declare(strict_types=1);

namespace App\Training\UI\Web;

use App\Training\Application\TrainingPartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TrainingPartController extends AbstractController
{
    private TrainingPartService $service;

    public function __construct(TrainingPartService $service)
    {
        $this->service = $service;
    }

    public function toggleIsEnded(Request $request): Response
    {
        $this->service->toggleIsEnded((int) $request->get('id'));

        return $this->redirectToRoute('index');
    }
}
