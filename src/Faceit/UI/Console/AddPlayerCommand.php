<?php
declare(strict_types=1);

namespace App\Faceit\UI\Console;

use App\Faceit\Application\Exception\ApplicationException;
use App\Faceit\Application\PlayerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class AddPlayerCommand extends Command
{
    protected static $defaultName = 'faceit:add-player';

    public function __construct(private PlayerService $playerService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('nickname', null, InputOption::VALUE_REQUIRED, 'Player nickname on Faceit');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->playerService->add($input->getOption('nickname'));

            return Command::SUCCESS;
        } catch (ApplicationException $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
