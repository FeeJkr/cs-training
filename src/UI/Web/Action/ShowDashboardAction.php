<?php
declare(strict_types=1);

namespace App\UI\Web\Action;

use App\Application\Training\TrainingService;
use App\UI\Web\Presenter\Training\TrainingPresenter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowDashboardAction extends AbstractAction
{
    public function __construct(
        private TrainingService $trainingService,
        private TrainingPresenter $presenter,
    ) {}

    public function __invoke(Request $request): Response
    {
        return $this->render('dashboard.html.twig', [
            'trainings' => $this->presenter->present($this->trainingService->getAll()),
        ]);
    }
}
