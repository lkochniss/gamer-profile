<?php

namespace tests\App\Service\Transformation;

use App\Entity\JSON\JsonGame;
use App\Service\Api\GameApiClientService;
use App\Service\Transformation\GameInformationService;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class GameInformationServiceTest
 */
class GameInformationServiceTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $steamGameApiServiceMock;

    public function setUp(): void
    {
        $this->steamGameApiServiceMock = $this->createMock(GameApiClientService::class);
    }

    public function testGetInformationForAppId(): void
    {
        $this->setSteamGameApiClientMock();

        $gameInformationService = new GameInformationService($this->steamGameApiServiceMock);
        $gameInformation = $gameInformationService->getGameInformationForSteamAppId(1);

        $this->assertEquals($this->getGameArray(), $gameInformation);
    }

    public function testGetInformationForAppIdWithFailure(): void
    {
        $this->setFailingSteamGameApiClientMock();

        $gameInformationService = new GameInformationService($this->steamGameApiServiceMock);
        $gameInformation = $gameInformationService->getGameInformationForSteamAppId(1);

        $this->assertEquals([], $gameInformation);
    }

    public function testGetGameInformationEntityForSteamAppId(): void
    {
        $this->setSteamGameApiClientMock();

        $gameInformationService = new GameInformationService($this->steamGameApiServiceMock);
        $gameInformation = $gameInformationService->getGameInformationEntityForSteamAppId(1);

        $expectedGameInformation = new JsonGame($this->getGameArray());

        $this->assertEquals($expectedGameInformation, $gameInformation);
    }

    private function setSteamGameApiClientMock(): void
    {
        $this->steamGameApiServiceMock->expects($this->any())
            ->method('get')
            ->with('/api/appdetails?appids=1')
            ->willReturn(new Response(200, [], json_encode($this->getGameResponseData())));
    }

    private function setFailingSteamGameApiClientMock(): void
    {
        $this->steamGameApiServiceMock->expects($this->any())
            ->method('get')
            ->with('/api/appdetails?appids=1')
            ->willReturn(new Response(200, [], json_encode($this->getErrorResponseData())));
    }

    /**
     * @return array
     */
    private function getGameResponseData(): array
    {
        return [
            '1' => [
                'success' => true,
                'data' => $this->getGameArray()
            ]
        ];
    }

    /**
     * @return array
     */
    private function getErrorResponseData(): array
    {
        return [
            '1' => [
                'success' => false
            ]
        ];
    }

    /**
     * @return array
     */
    private function getGameArray(): array
    {
        return[
            'type' => 'game',
            'name' => 'Demo game',
            'steam_appid' => 1,
            'required_age' => 0,
            'header_image' => 'demo.img',
            'release_date' => [
                'date' => '10 Oct, 2017'
            ]
        ];
    }
}
