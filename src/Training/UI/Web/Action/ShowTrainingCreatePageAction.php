<?php
declare(strict_types=1);

namespace App\UI\Web\Action\Training;

use App\UI\Web\Action\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ShowTrainingCreatePageAction extends AbstractAction
{
    public function __invoke(Request $request): Response
    {
        return $this->render('training/create.html.twig');
    }
}
