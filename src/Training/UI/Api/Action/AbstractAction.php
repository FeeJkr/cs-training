<?php
declare(strict_types=1);

namespace App\Training\UI\Api\Action;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAction extends AbstractController
{
    abstract public function __invoke(Request $request): Response;
}
