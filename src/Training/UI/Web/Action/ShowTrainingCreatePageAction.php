<?php
declare(strict_types=1);

namespace App\Training\UI\Web\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowTrainingCreatePageAction extends AbstractAction
{
    public function __invoke(Request $request): Response
    {
        return $this->render('training/create.html.twig', [
            'maps' => [['id' => 1, 'name' => 'test'], ['id' => 2, 'name' => 'Test2']]
        ]);
    }
}
