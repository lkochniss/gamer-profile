<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BlogPostControllerTest extends WebTestCase
{
    /**
     * @var LoginHelper
     */
    private $loginHelper;

    public function setUp()
    {
        $this->loginHelper = new LoginHelper();
    }

    /**
     * @param string $url
     * @dataProvider frontendUrlProvider
     */
    public function testFrontendBlogActionsReturnOk(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function frontendUrlProvider(): array
    {
        $now = new \DateTime();
        return [
            ['/game-1/blog'],
            ['/game-2/blog'],
            ['/game-3/blog'],
            [sprintf('/game-1/blog/%s-post-1', $now->format('Y-m-d'))],
            [sprintf('/game-2/blog/%s-post-2', $now->format('Y-m-d'))],
            [sprintf('/game-2/blog/%s-post-3', $now->format('Y-m-d'))]
        ];
    }

    public function testMissingSlugReturnsNotFoundException(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', 'game-1/asdf');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testBackendBlogActionsReturnOk(string $url): void
    {
        $client = static::createClient();
        $this->loginHelper->logIn($client);
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testBackendBlogActionsWithoutCredentialsRedirectsToLogin(string $url): void
    {
        $this->expectException(AccessDeniedException::class);

        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', $url);
        $crawler = $client->followRedirect();

        $this->assertContains('/admin/login', $crawler->getUri());
    }

    /**
     * @return array
     */
    public function backendUrlProvider(): array
    {
        return [
            ['/admin/blog'],
            ['/admin/blog/create'],
            ['admin/blog/3/edit'],
            ['admin/game/1/blog/create'],
            ['admin/game/1/blog'],
            ['admin/game/2/blog'],
            ['admin/game/3/blog'],
        ];
    }
}
