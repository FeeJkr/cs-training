<?php
declare(strict_types=1);

namespace App\Training\UI\Web\Action;

use App\Training\Application\TrainingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class EndTrainingPartAction extends AbstractAction
{
    private TrainingService $trainingService;

    public function __construct(TrainingService $trainingService) {
        $this->trainingService = $trainingService;
    }

    public function __invoke(Request $request): Response
    {
        $this->trainingService->endTrainingPart((int) $request->get('id'));

        return $this->redirectToRoute('index');
    }
}
