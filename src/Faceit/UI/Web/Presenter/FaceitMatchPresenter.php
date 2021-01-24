<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter;

use App\Faceit\Domain\FaceitPlayerMatchResult;
use App\Faceit\Domain\FaceitPlayerMatchResultsCollection;

final class FaceitMatchPresenter
{
    public function presentPlayerResults(FaceitPlayerMatchResultsCollection $resultsCollection): array
    {
        return array_map(static function (FaceitPlayerMatchResult $result): array {
            return [
                'team' => $result->getTeam(),
                'map' => $result->getMap(),
                'mode' => $result->getMode(),
                'score' => $result->getScore(),
                'kills' => $result->getKills(),
                'deaths' => $result->getDeaths(),
                'assists' => $result->getAssists(),
                'headshots' => $result->getHeadshots(),
                'headshotsPercentage' => $result->getHeadshotsPercentage(),
                'tripleKills' => $result->getTripleKills(),
                'quadroKills' => $result->getQuadroKills(),
                'pentaKills' => $result->getPentaKills(),
                'mvps' => $result->getMvps(),
                'kdRatio' => $result->getKdRatio(),
                'krRatio' => $result->getKrRatio(),
                'isWin' => $result->isWin(),
                'isGoodKdRatio' => $result->isGoodKdRatio(),
                'isGoodKrRatio' => $result->isGoodKrRatio(),
            ];
        }, $resultsCollection->getMatches());
    }
}
