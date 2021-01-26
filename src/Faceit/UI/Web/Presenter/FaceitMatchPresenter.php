<?php
declare(strict_types=1);

namespace App\Faceit\UI\Web\Presenter;

use App\Faceit\Domain\FaceitPlayerMatchResult;
use App\Faceit\Domain\FaceitPlayerMatchResultsCollection;
use function array_merge;

final class FaceitMatchPresenter
{
    public function presentPlayerResults(FaceitPlayerMatchResultsCollection $resultsCollection): array
    {
        $todayMatches = $resultsCollection->getTodayMatches();
        $yesterdayMatches = $resultsCollection->getYesterdayMatches();
        $monthMatches = $resultsCollection->getThisMonthMatches();

        $data = [
            'today' => [
                'total' => $todayMatches->getTotalCount(),
                'wins' => $todayMatches->getWins(),
                'loses' => $todayMatches->getLoses(),
                'averageKd' => $todayMatches->getAverageKd(),
            ],
            'yesterday' => [
                'total' => $yesterdayMatches->getTotalCount(),
                'wins' => $yesterdayMatches->getWins(),
                'loses' => $yesterdayMatches->getLoses(),
                'averageKd' => $yesterdayMatches->getAverageKd(),
            ],
            'month' => [
                'total' => $monthMatches->getTotalCount(),
                'wins' => $monthMatches->getWins(),
                'loses' => $monthMatches->getLoses(),
                'averageKd' => $monthMatches->getAverageKd(),
            ],
        ];

        $data['matches'] = array_map(static function (FaceitPlayerMatchResult $result): array {
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
                'finishedAt' => $result->getFinishedAt()->format('d-m-Y H:i'),
                'faceitUrl' => $result->getFaceitUrl(),
            ];
        }, $resultsCollection->getMatches());

        return $data;
    }
}
