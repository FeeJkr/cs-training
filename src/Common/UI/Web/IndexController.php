<?php
declare(strict_types=1);

namespace App\Common\UI\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class IndexController extends AbstractController
{
    public function dashboard(): Response
    {
        return $this->redirectToRoute('training.dashboard');
    }
}
