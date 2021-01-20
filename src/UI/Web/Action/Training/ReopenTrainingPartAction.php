<?php
declare(strict_types=1);

namespace App\UI\Web\Action\Training;

use App\Application\Training\TrainingService;
use App\UI\Web\Action\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ReopenTrainingPartAction extends AbstractAction
{
    private TrainingService $trainingService;

    public function __construct(TrainingService $trainingService) {
        $this->trainingService = $trainingService;
    }

    public function __invoke(Request $request): Response
    {
        $this->trainingService->reopenTrainingPart((int) $request->get('id'));

        return $this->redirectToRoute('index');
    }
}
