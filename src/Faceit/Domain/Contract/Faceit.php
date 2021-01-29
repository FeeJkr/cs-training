<?php
declare(strict_types=1);

namespace App\Faceit\Domain\Contract;

use App\Faceit\Domain\Contract\Match\MatchesCollectionResponse;
use App\Faceit\Domain\Contract\Player\PlayerResponse;
use App\Faceit\Domain\Contract\Statistics\StatisticsResponse;

interface Faceit
{
    /**
     * @throws FaceitException
     */
    public function getPlayerByNickname(string $nickname): PlayerResponse;

    /**
     * @throws FaceitException
     */
    public function getPlayerMatches(string $playerId, int $limit): MatchesCollectionResponse;

    /**
     * @throws FaceitException
     */
    public function getPlayerStatistics(string $playerId): StatisticsResponse;
}
