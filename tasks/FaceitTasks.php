<?php
declare(strict_types=1);

use App\Faceit\UI\Console\AddMatchesCommand;
use App\Faceit\UI\Console\AddStatisticsCommand;
use App\Faceit\UI\Console\UpdatePlayersCommand;
use Crunz\Schedule;

$schedule = new Schedule();

const COMMAND_PATTERN = PHP_BINARY . ' bin/console %s';

$schedule->run(sprintf(COMMAND_PATTERN, AddMatchesCommand::getDefaultName() . ' 1'))
    ->everyMinute()
    ->description('Update matches lists');

$schedule->run(sprintf(COMMAND_PATTERN, UpdatePlayersCommand::getDefaultName()))
    ->everyMinute()
    ->description('Update players level and elo');

$schedule->run(sprintf(COMMAND_PATTERN, AddStatisticsCommand::getDefaultName()))
    ->everyMinute()
    ->description('Update players statistics (global and month)');

return $schedule;
