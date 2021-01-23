<?php
declare(strict_types=1);

namespace App\Faceit\UI\Console;

use App\Faceit\Application\FaceitMatchService;
use App\Faceit\Application\FaceitService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AddFaceitMatchesCommand extends Command
{
    protected static $defaultName = 'faceit:add-matches';

    private FaceitService $faceitService;
    private FaceitMatchService $faceitMatchService;

    public function __construct(FaceitService $faceitService, FaceitMatchService $faceitMatchService)
    {
        parent::__construct(null);

        $this->faceitService = $faceitService;
        $this->faceitMatchService = $faceitMatchService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->faceitService->getAllPlayersNicknames() as $player) {
            $this->faceitMatchService->update($player['nickname']);
        }

        return Command::SUCCESS;
    }
}
