<?php
declare(strict_types=1);

namespace App\Faceit\UI\Console;

use App\Faceit\Application\FaceitService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UpdateFaceitPlayerStatisticsCommand extends Command
{
    protected static $defaultName = 'faceit:update-statistics';

    private FaceitService $faceitService;

    public function __construct(FaceitService $faceitService)
    {
        parent::__construct(null);

        $this->faceitService = $faceitService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->faceitService->getAllPlayersNicknames() as $player) {
            $this->faceitService->updatePlayerStatistics($player['nickname']);
        }

        return Command::SUCCESS;
    }
}
