<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class BlogPostControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client = null;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @param string $url
     * @dataProvider blogPostListUrlProvider
     */
    public function testDifferentBlogLists(string $url): void
    {
        $this->client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
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
        $this->client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
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
