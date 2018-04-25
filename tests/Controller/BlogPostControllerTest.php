<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BlogPostControllerTest extends WebTestCase
{

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

    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testBackendBlogActionsReturnOk(string $url): void
    {
        $client = static::createClient();
        LoginHelper::logIn($client);
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @param string $url
     * @dataProvider backendUrlProvider
     */
    public function testBackendBlogActionsWithoutCredentialsRedirect(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
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
