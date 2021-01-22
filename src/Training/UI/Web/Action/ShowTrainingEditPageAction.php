<?php
declare(strict_types=1);

namespace App\Training\UI\Web\Action;

use App\Training\Application\TrainingService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowTrainingEditPageAction extends AbstractAction
{
    private TrainingService $trainingService;

    public function __construct(TrainingService $trainingService) {
        $this->trainingService = $trainingService;
    }

    public function __invoke(Request $request): Response
    {
        $training = $this->trainingService->getTrainingById((int)$request->get('id'));
        $maps = $this->trainingService->getAllMaps();

        return $this->render('training/edit.html.twig', [
            'training' => $training,
            'maps' => $maps,
        ]);
    }
}
