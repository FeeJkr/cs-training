<?php
declare(strict_types=1);

namespace App\UI\Api\Action\Training;

use App\Application\Training\TrainingService;
use App\UI\Api\Action\AbstractAction;
use App\UI\Api\Presenter\Training\GetAllMapsPresenter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetAllMapsAction extends AbstractAction
{
    private TrainingService $trainingService;

    public function __construct(TrainingService $trainingService) {
        $this->trainingService = $trainingService;
    }

    public function __invoke(Request $request): Response
    {
        $maps = $this->trainingService->getAllMaps();

        return new JsonResponse(GetAllMapsPresenter::present($maps), 200, ['Access-Control-Allow-Origin' => '*']);
    }
}
