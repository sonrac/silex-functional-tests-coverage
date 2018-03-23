<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

/**
 * Trait ControllersTestTrait
 * Controllers test trait.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
trait ControllersTestTrait
{
    /**
     * Calls a GET URI.
     *
     * @param string $uri           The URI to fetch
     * @param array  $parameters    The Request parameters
     * @param array  $files         The files
     * @param array  $server        The server parameters (HTTP headers are referenced with a HTTP_ prefix as PHP does)
     * @param string $content       The raw body data
     * @param bool   $changeHistory Whether to update the history or not (only used internally for back(), forward(),
     *                              and reload())
     *
     * @return $this
     */
    protected function get(
        $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    ) {
        return $this->request('GET', $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Calls a PUT URI.
     *
     * @param string $uri           The URI to fetch
     * @param array  $parameters    The Request parameters
     * @param array  $files         The files
     * @param array  $server        The server parameters (HTTP headers are referenced with a HTTP_ prefix as PHP does)
     * @param string $content       The raw body data
     * @param bool   $changeHistory Whether to update the history or not (only used internally for back(), forward(),
     *                              and reload())
     *
     * @return $this
     */
    protected function put(
        $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    ) {
        return $this->request('GET', $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Calls a POST URI.
     *
     * @param string $uri           The URI to fetch
     * @param array  $parameters    The Request parameters
     * @param array  $files         The files
     * @param array  $server        The server parameters (HTTP headers are referenced with a HTTP_ prefix as PHP does)
     * @param string $content       The raw body data
     * @param bool   $changeHistory Whether to update the history or not (only used internally for back(), forward(),
     *                              and reload())
     *
     * @return $this
     */
    protected function post(
        $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    ) {
        return $this->request('POST', $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Calls a DELETE URI.
     *
     * @param string $uri           The URI to fetch
     * @param array  $parameters    The Request parameters
     * @param array  $files         The files
     * @param array  $server        The server parameters (HTTP headers are referenced with a HTTP_ prefix as PHP does)
     * @param string $content       The raw body data
     * @param bool   $changeHistory Whether to update the history or not (only used internally for back(), forward(),
     *                              and reload())
     *
     * @return $this
     */
    protected function delete(
        $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    ) {
        return $this->request('DELETE', $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * Calls a PATCH URI.
     *
     * @param string $uri           The URI to fetch
     * @param array  $parameters    The Request parameters
     * @param array  $files         The files
     * @param array  $server        The server parameters (HTTP headers are referenced with a HTTP_ prefix as PHP does)
     * @param string $content       The raw body data
     * @param bool   $changeHistory Whether to update the history or not (only used internally for back(), forward(),
     *                              and reload())
     *
     * @return $this
     */
    protected function patch(
        $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    ) {
        return $this->request('PATCH', $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * See json structure.
     *
     * @param array $struct
     * @param array $data
     *
     * @return $this
     */
    protected function seeJsonStructure($struct, $data = null)
    {
        $data = $data ?: json_decode(trim($this->response->getContent()), true);

        static::assertInternalType('array', $data);
        foreach ($struct as $name => $value) {
            $withValue = !is_numeric($name);
            $_name     = $withValue ? $name : $value;

            static::assertArrayHasKey($_name, $data);

            if ($withValue) {
                if (is_array($value)) {
                    $this->seeJsonStructure($value, $data[$_name]);
                } else {
                    static::assertEquals($value, $data[$_name]);
                }
            }
        }

        return $this;
    }

    /**
     * See header in response.
     *
     * @param string $header
     * @param null   $value
     *
     * @return $this
     */
    protected function seeHeader($header, $value = null)
    {
        /** @var \Symfony\Component\HttpFoundation\ResponseHeaderBag $headers */
        $headers = $this->response->headers->all();

        static::assertArrayHasKey(strtolower($header), $headers, 'Header ' . $header . 'is missing');
        if ($value !== null) {
            static::assertEquals(strtolower($value), strtolower($headers[strtolower($header)][0]), 'Header value not match');
        }

        return $this;
    }

    /**
     * See status code in response.
     *
     * @param int $statusCode
     *
     * @return $this
     */
    protected function seeStatusCode($statusCode)
    {
        static::assertEquals($statusCode, $this->response->getStatusCode());

        return $this;
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
     * @return $this
     */
    abstract protected function request(
        $method,
        $uri,
        array $parameters = [],
        array $files = [],
        array $server = [],
        $content = null,
        $changeHistory = true
    );
}