<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BlogPostControllerTest extends WebTestCase
{

    /**
     * @param string $url
     * @dataProvider blogPostListUrlProvider
     */
    public function testDifferentBlogLists(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function blogPostListUrlProvider(): array
    {
        return [
            ['/game-1/blog'],
            ['/game-2/blog'],
            ['/game-3/blog'],
        ];
    }

    /**
     * @param string $url
     * @dataProvider blogPostUrlProvider
     */
    public function testDifferentBlogPosts(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function blogPostUrlProvider(): array
    {
        $now = new \DateTime();
        return [
            [sprintf('/game-1/blog/%s-post-1', $now->format('Y-m-d'))],
            [sprintf('/game-2/blog/%s-post-2', $now->format('Y-m-d'))],
            [sprintf('/game-2/blog/%s-post-3', $now->format('Y-m-d'))]
        ];
    }
}
