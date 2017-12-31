<?php

namespace tests\App\Controller;

use App\Service\TranslationService;
use App\Service\Twig\HomepageTransformatorService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Client;

class AppControllerTest extends WebTestCase
{
    /**
     * @var Client $client
     */
    private $client;

    /**
     * @var MockObject
     */
    private $translationServiceMock;

    /**
     * @var MockObject
     */
    private $homepageTransformationServiceMock;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->translationServiceMock = $this->createMock(TranslationService::class);
        $this->homepageTransformationServiceMock = $this->createMock(HomepageTransformatorService::class);
    }

    public function testIndexActionIsAccessable(): void
    {
        $this->setTranslationServiceMock();
        $this->client->getContainer()->set('App\Service\TranslationService', $this->translationServiceMock);

        $this->setHomepageTransformationServiceMock();
        $this->client->getContainer()->set(
            'App\Service\Twig\HomepageTransformatorService',
            $this->homepageTransformationServiceMock
        );

        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    private function setHomepageTransformationServiceMock():void
    {
        $this->homepageTransformationServiceMock->expects($this->any())
            ->method('transformRecentlyPlayedGames')
            ->willReturn([]);

        $this->homepageTransformationServiceMock->expects($this->any())
            ->method('transformTopPlayedGames')
            ->willReturn([]);
    }

    private function setTranslationServiceMock()
    {
        $this->translationServiceMock->expects($this->any())
            ->method('trans')
            ->willReturn('');
        $this->translationServiceMock->expects($this->any())
            ->method('transChoice')
            ->willReturn('');
    }
}
