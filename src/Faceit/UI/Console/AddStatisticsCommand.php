<?php
declare(strict_types=1);

namespace App\Faceit\UI\Console;

use App\Faceit\Application\Exception\ApplicationException;
use App\Faceit\Application\PlayerService;
use App\Faceit\Application\StatisticsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AddStatisticsCommand extends Command
{
    protected static $defaultName = 'faceit:add-statistics';

    private StatisticsService $statisticsService;
    private PlayerService $playerService;

    public function __construct(StatisticsService $statisticsService, PlayerService $playerService)
    {
        parent::__construct();

        $this->statisticsService = $statisticsService;
        $this->playerService = $playerService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            foreach ($this->playerService->getAll()->toArray() as $player) {
                $this->statisticsService->add($player->getFaceitId());
            }

            return Command::SUCCESS;
        } catch (ApplicationException $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
