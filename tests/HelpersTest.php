<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>
 */

namespace sonrac\FCoverage\Tests;

use PHPUnit\Framework\TestCase;
use sonrac\FCoverage\ControllersTestTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HelpersTest.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class HelpersTest extends TestCase
{
    use ControllersTestTrait;

    protected $response;

    public function testEmptyJson()
    {
        $response = new Response();
        $response->setContent(json_encode([
        ]));

        $this->request($response);
        $this->seeJsonStructure([]);
    }

    /**
     * {@inheritdoc}
     *
     * @param Response $method
     */
    protected function request(
        $method,
        $uri = null,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    ) {
        $this->response = $method;

        return $this;
    }

    public function testErrorJson()
    {
        $response = new Response();
        $response->setContent(json_encode([
            'status'  => true,
            'message' => 'message',
        ]));

        $this->request($response);
        $this->seeJsonStructure([
            'status' => true,
            'message',
        ]);
    }

    public function testStructWithItems()
    {
        $response = new Response();
        $response->setContent(json_encode([
            'status'  => true,
            'message' => 'message',
            'items'   => [
                [
                    'test' => 1,
                    'b'    => 2,
                ],
                [
                    'test' => 1,
                    'b'    => 2,
                ],
                [
                    'test' => 1,
                    'b'    => 2,
                ],
                [
                    'test' => 1,
                    'b'    => 2,
                ],
                [
                    'test' => 1,
                    'b'    => 2,
                ],
            ],
        ]));

        $this->request($response);
        $this->seeJsonStructure([
            'status' => true,
            'message',
            'items' => ['test', 'b'],
        ]);
    }

    public function testStructWithArray()
    {
        $response = new Response();
        $response->setContent(json_encode([
            'status'  => true,
            'message' => 'message',
            'items'   => [
                'test' => 1,
                'b'    => 2,
            ],
        ]));

        $this->request($response);
        $this->seeJsonStructure([
            'status' => true,
            'message',
            'items' => ['test', 'b'],
        ]);
    }
}
