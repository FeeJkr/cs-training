<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure\Adapter;

use App\Faceit\Domain\Contract\Faceit;
use App\Faceit\Domain\Contract\FaceitException;
use App\Faceit\Domain\Contract\Match\MatchesCollectionResponse;
use App\Faceit\Domain\Contract\Player\PlayerResponse;
use App\Faceit\Domain\Contract\Statistics\StatisticsResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

final class FaceitAdapter implements Faceit
{
    private const PLAYER_INFO_URI = 'https://open.faceit.com/data/v4/players?nickname=%s';
    private const PLAYER_MATCHES_URI = 'https://open.faceit.com/data/v4/players/%s/history?game=csgo&offset=%d&limit=%d';
    private const MATCH_INFO_URI = 'https://open.faceit.com/data/v4/matches/%s/stats';
    private const PLAYER_STATISTICS_URI = 'https://open.faceit.com/data/v4/players/%s/stats/csgo';

    public function __construct(private Client $httpClient, private string $faceitApiToken){}

    /**
     * @throws FaceitException
     */
    public function getPlayerByNickname(string $nickname): PlayerResponse
    {
        $response = $this->get(sprintf(self::PLAYER_INFO_URI, $nickname));

        return PlayerResponse::createFromResponse($response);
    }

    /**
     * @throws FaceitException
     */
    public function getPlayerStatistics(string $playerId): StatisticsResponse
    {
        $response = $this->get(sprintf(self::PLAYER_STATISTICS_URI, $playerId));

        return StatisticsResponse::createFromResponse($response);
    }

    /**
     * @throws FaceitException
     */
    public function getPlayerMatches(string $playerId, int $limit): MatchesCollectionResponse
    {
        $matchesResponse = $this->get(sprintf(self::PLAYER_MATCHES_URI, $playerId, 0, $limit))['items'];
        $matches = [];

        foreach ($matchesResponse as $match) {
            $match['match_information'] = $this->get(sprintf(self::MATCH_INFO_URI, $match['match_id']))['rounds'][0];

            $matches[] = $match;
        }

        return MatchesCollectionResponse::createFromResponse($matches);
    }

    /**
     * @throws FaceitException
     */
    private function get(string $url): array
    {
        try {
            $response = $this->httpClient->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->faceitApiToken,
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException | GuzzleException $exception) {
            throw FaceitException::invalidFaceitResponse();
        }
    }
}
