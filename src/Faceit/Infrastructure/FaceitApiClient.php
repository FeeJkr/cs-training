<?php
declare(strict_types=1);

namespace App\Faceit\Infrastructure;

use App\Faceit\Domain\FaceitApi;
use GuzzleHttp\Client;

final class FaceitApiClient implements FaceitApi
{
    private const PLAYER_INFO_URI = 'https://open.faceit.com/data/v4/players?nickname=%s';
    private const PLAYER_MATCHES_URI = 'https://open.faceit.com/data/v4/players/%s/history?game=csgo&offset=0&limit=%d';
    private const MATCH_INFO_URI = 'https://open.faceit.com/data/v4/matches/%s/stats';
    private const PLAYER_STATISTICS_URI = 'https://open.faceit.com/data/v4/players/%s/stats/csgo';

    private Client $httpClient;
    private string $faceitApiToken;

    public function __construct(Client $httpClient, string $faceitApiToken)
    {
        $this->httpClient = $httpClient;
        $this->faceitApiToken = $faceitApiToken;
    }

    public function getPlayerInformationByNickname(string $nickname): array
    {
        $response = $this->httpClient->get(sprintf(self::PLAYER_INFO_URI, $nickname), [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->faceitApiToken,
            ]
        ]);


        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function getMatches(string $faceitId, int $limit): array
    {
        $response = $this->httpClient->get(sprintf(self::PLAYER_MATCHES_URI, $faceitId, $limit), [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->faceitApiToken,
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR)['items'];
    }

    public function getMatchInformation(string $matchId): array
    {
        $response = $this->httpClient->get(sprintf(self::MATCH_INFO_URI, $matchId), [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->faceitApiToken,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function getPlayerStatistics(string $faceitId): array
    {
        $response = $this->httpClient->get(sprintf(self::PLAYER_STATISTICS_URI, $faceitId), [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->faceitApiToken,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}
