<?php


namespace App\Tests\Controller;

use App\Tests\DataPrimer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class BlogPostControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        $kernel = self::bootKernel();
        DataPrimer::setUp($kernel);
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

    public function blogPostUrlProvider(): array
    {
        $now = new \DateTime();
        return [
            [sprintf('/game-1/blog/%s-post-1', $now->format('Y-m-d'))],
            [sprintf('/game-2/blog/%s-post-2', $now->format('Y-m-d'))],
            [sprintf('/game-2/blog/%s-post-3', $now->format('Y-m-d'))]
        ];
    }

    /**
     * @throws \Exception
     */
    public function tearDown(): void
    {
        DataPrimer::drop(self::bootKernel());
    }
}
