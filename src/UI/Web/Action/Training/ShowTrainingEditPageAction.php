<?php
declare(strict_types=1);

namespace App\UI\Web\Action\Training;

use App\Application\Training\TrainingService;
use App\UI\Web\Action\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowTrainingEditPageAction extends AbstractAction
{
    public function __construct(private TrainingService $trainingService) {}

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
