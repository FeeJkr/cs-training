<?php
declare(strict_types=1);

namespace App\UI\Web\Action;

use App\Application\Training\TrainingService;
use App\UI\Web\Presenter\Training\TrainingPresenter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowDashboardAction extends AbstractAction
{
    private TrainingService $trainingService;
    private TrainingPresenter $presenter;

    public function __construct(
        TrainingService $trainingService,
        TrainingPresenter $presenter
    ) {
        $this->presenter = $presenter;
        $this->trainingService = $trainingService;
    }

    public function __invoke(Request $request): Response
    {
        return $this->render('dashboard.html.twig', [
            'trainings' => $this->presenter->present($this->trainingService->getAll()),
        ]);
    }
}
