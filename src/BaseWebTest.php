<?php

namespace sonrac\FCoverage;

use Silex\WebTestCase;

/**
 * Class BaseWebTest.
 */
abstract class BaseWebTest extends WebTestCase
{
    use BootTraits, ControllersTestTrait;

    /**
     * @var null|\Symfony\Component\HttpKernel\Client
     */
    protected $client = null;
    /**
     * @var null|\Symfony\Component\HttpFoundation\Response
     */
    protected $response = null;
    /**
     * @var null|\Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler = null;

    public function createApplication()
    {
        return require __DIR__.'/../../bootstrap/api.php';
    }

    /**
     * {@inheritdoc}
     *
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = null;
        $this->response = null;

        $this->_boot();
    }

    /**
     * Calls a URI.
     *
     * @param string $method        The request method
     * @param string $uri           The URI to fetch
     * @param array  $parameters    The Request parameters
     * @param array  $files         The files
     * @param array  $server        The server parameters (HTTP headers are referenced with a HTTP_ prefix as PHP does)
     * @param string $content       The raw body data
     * @param bool   $changeHistory Whether to update the history or not (only used internally for back(), forward(),
     *                              and reload())
     *
     * @return \Tests\Functional\BaseWebTest
     */
    protected function request(
        $method,
        $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    ) {
        $this->client = $this->client ?: $this->createClient();
        $this->crawler = $this->client->request($method, $uri);

        $this->response = $this->client->getResponse();

        return $this;
    }

    protected function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::tearDown();

        if (method_exists($this, '_down')) {
            $this->_down();
        }
    }
}
