<?php
declare(strict_types=1);

namespace App\Faceit\UI\Console;

use App\Faceit\Application\Exception\ApplicationException;
use App\Faceit\Application\MatchService;
use App\Faceit\Application\PlayerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AddMatchesCommand extends Command
{
    private const DEFAULT_LIMIT = 30;
    protected static $defaultName = 'faceit:add-matches';

    private PlayerService $playerService;
    private MatchService $matchService;

    public function __construct(PlayerService $playerService, MatchService $matchService)
    {
        parent::__construct();

        $this->playerService = $playerService;
        $this->matchService = $matchService;
    }

    protected function configure(): void
    {
        $this->addArgument('limit', InputArgument::OPTIONAL, 'Custom matches limit', self::DEFAULT_LIMIT);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $limit = (int) $input->getArgument('limit');

            foreach ($this->playerService->getAll()->toArray() as $player) {
                $this->matchService->add($player->getFaceitId(), $limit);
            }

            return Command::SUCCESS;
        } catch (ApplicationException $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
