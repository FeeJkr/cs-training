<?php
declare(strict_types=1);

namespace App\Training\UI\Web;

use App\Training\Application\TrainingMapService;
use App\Training\Application\TrainingService;
use App\Training\Domain\TrainingMode;
use App\Training\UI\Web\Presenter\TrainingMapPresenter;
use App\Training\UI\Web\Presenter\TrainingPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TrainingController extends AbstractController
{
    private TrainingService $service;
    private TrainingPresenter $presenter;
    private TrainingMapService $trainingMapService;
    private TrainingMapPresenter $trainingMapPresenter;

    public function __construct(
        TrainingService $service,
        TrainingPresenter $presenter,
        TrainingMapService $trainingMapService,
        TrainingMapPresenter $trainingMapPresenter
    ) {
        $this->service = $service;
        $this->presenter = $presenter;
        $this->trainingMapService = $trainingMapService;
        $this->trainingMapPresenter = $trainingMapPresenter;
    }

    public function dashboard(): Response
    {
        return $this->render('training/dashboard.html.twig', [
            'trainings' => $this->presenter->present($this->service->getAll()),
        ]);
    }

    public function createPage(): Response
    {
        return $this->render('training/create.html.twig', [
            'maps' => $this->trainingMapPresenter->present($this->trainingMapService->getAll()),
            'modes' => $this->presenter->presentTrainingMods(TrainingMode::values()),
        ]);
    }

    public function create(Request $request): Response
    {
        $this->service->create($request->get('training_date'), $request->get('parts'));

        return $this->redirectToRoute('training.dashboard');
    }

    public function editPage(Request $request): Response
    {
        $training = $this->service->getById((int) $request->get('id'));

        return $this->render('training/edit.html.twig', [
            'training' => $this->presenter->presentTraining($training),
            'modes' => $this->presenter->presentTrainingMods(TrainingMode::values()),
            'maps' => $this->trainingMapPresenter->present($this->trainingMapService->getAll()),
        ]);
    }

    public function update(Request $request): Response
    {
        $this->service->update(
            (int)$request->get('id'),
            $request->get('training_date'),
            $request->get('parts')
        );

        return $this->redirectToRoute('training.dashboard');
    }
}
