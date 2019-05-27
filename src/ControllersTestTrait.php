<?php
/**
 * @author Donii Sergii <doniysa@gmail.com>.
 */

namespace sonrac\FCoverage;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Trait ControllersTestTrait.
 *
 * Controllers test trait.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
trait ControllersTestTrait
{
    /**
     * If false - disable redirects. If number - max enabled redirects count.
     *
     * @var bool|int
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $allowRedirect = false;

    /**
     * If true generate exception when count redirects more than `allowRedirect` value.
     *
     * @var bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $throwExceptionOnRedirect = true;

    /**
     * Clear redirects count.
     *
     * @var bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $clearCountRedirects = false;

    /**
     * Current count redirects.
     *
     * @var int
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    private $countRedirects = 0;

    /**
     * Get is need clear count redirects.
     *
     * @return bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function isClearCountRedirects()
    {
        return $this->clearCountRedirects;
    }

    /**
     * Get is need clear count redirects.
     *
     * @param bool $clear
     *
     * @return $this
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setClearCountRedirects($clear)
    {
        $this->clearCountRedirects = $clear;

        return $this;
    }

    /**
     * @return bool|int
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getAllowRedirect()
    {
        return $this->allowRedirect;
    }

    /**
     * @param bool|int $allowRedirect
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setAllowRedirect($allowRedirect)
    {
        $this->allowRedirect = $allowRedirect;
    }

    /**
     * Check is thrown exception on redirect.
     *
     * @return bool
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function isThrowExceptionOnRedirect()
    {
        return $this->throwExceptionOnRedirect;
    }

    /**
     * Set thrown exception on redirect.
     *
     * @param bool $throwExceptionOnRedirect
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setThrowExceptionOnRedirect($throwExceptionOnRedirect)
    {
        $this->throwExceptionOnRedirect = $throwExceptionOnRedirect;
    }

    /**
     * Get count redirects.
     *
     * @return int
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getCountRedirects()
    {
        return $this->countRedirects;
    }

    /**
     * Set count redirects.
     *
     * @param int $_countRedirects
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setCountRedirects($_countRedirects)
    {
        $this->countRedirects = $_countRedirects;
    }

    /**
     * Increment count redirects.
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function incrementCountRedirects()
    {
        ++$this->countRedirects;
    }

    /**
     * See in table count records.
     *
     * @param string     $table
     * @param int        $count
     * @param array|null $params
     *
     * @return $this
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function seeCountInTable($table, $count, $params = null)
    {
        $params     = $params ?: [];
        $connection = $this->getConnection();
        $builder    = $connection->createQueryBuilder()
                                 ->select(['count(*) as cnt'])
                                 ->from($table, $table);

        foreach ($params as $param => $value) {
            if (empty($value)) {
                $builder->andWhere("$param = '' OR $param IS NULL");
            } else {
                $builder->setParameter($param, $value, \PDO::PARAM_STR)
                        ->andWhere("$param = :{$param}");
            }
        }

        static::assertEquals($count, $builder->execute()->fetchColumn());

        return $this;
    }

    /**
     * Get database connection.
     *
     * @return \Doctrine\DBAL\Connection
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function getConnection()
    {
        return $this->app['db'];
    }

    /**
     * Get response object.
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getResponseObject()
    {
        return $this->response;
    }

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
        $this->setClearCountRedirects(true);
        $this->clear();

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
        $this->setClearCountRedirects(true);
        $this->clear();

        return $this->request('PUT', $uri, $parameters, $files, $server, $content, $changeHistory);
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
        $this->setClearCountRedirects(true);
        $this->clear();

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
        $this->setClearCountRedirects(true);
        $this->clear();

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
        $this->setClearCountRedirects(true);
        $this->clear();

        return $this->request('PATCH', $uri, $parameters, $files, $server, $content, $changeHistory);
    }

    /**
     * See json structure.
     *
     * @param array $struct
     * @param array $data
     * @param bool  $skipUndefinedProperties
     *
     * @return $this
     */
    protected function seeJsonStructure($struct, $data = null, $skipUndefinedProperties = true)
    {
        $data = $data ?: json_decode(trim($this->response->getContent()), true);

        static::assertInternalType('array', $data, 'Error get response. Not data given');
        foreach ($struct as $name => $value) {
            $withValue = !is_numeric($name);
            $_name     = $withValue ? $name : $value;

            if (is_string($_name)) {
                try {
                    static::assertArrayHasKey($_name, $data, 'Error response has key');
                } catch (\Exception $exception) {
                    if (is_array($value)) {
                        static::assertInternalType('array', $data[$_name]);
                        foreach ($data[$_name] as $datum) {
                            $this->seeJsonStructure($value, $datum);
                        }

                        continue;
                    }
                    if (!$skipUndefinedProperties) {
                        throw $exception;
                    }
                }
            } else {
                $value = $_name;
            }

            if ($withValue) {
                if (is_array($value)) {
                    $this->seeJsonStructure($value, $data[$_name]);
                } else {
                    static::assertEquals($value, $data[$_name], 'Error equals value of response item');
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
        /** @var array $headers */
        $headers = $this->response->headers->all();

        static::assertArrayHasKey(strtolower($header), $headers, 'Header '.$header.'is missing');
        if ($value !== null) {
            static::assertEquals(
                strtolower($value),
                strtolower($headers[strtolower($header)][0]),
                'Header value not match'
            );
        }

        return $this;
    }

    /**
     * See status code in response.
     *
     * @param int $statusCode
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     *
     * @return $this
     */
    protected function seeStatusCode($statusCode)
    {
        static::assertEquals($statusCode, $this->response->getStatusCode());

        return $this;
    }

    /**
     * See string in response.
     *
     * @param $string
     */
    protected function seeString($string)
    {
        self::assertContains($string, $this->getResponseObject()->getContent());
    }

    /**
     * See html element.
     *
     * @param string $selector
     */
    protected function seeHtmlElement($selector)
    {
        $crawler = new Crawler();

        $crawler->addContent($this->getResponseObject()->getContent());

        self::assertTrue(
            $crawler->filter($selector)->count() > 0
        );
    }

    /**
     * See html element.
     *
     * @param string $selector
     * @param int    $count
     */
    protected function seeCountHtmlElement($selector, $count)
    {
        $crawler = new Crawler();

        $crawler->addContent($this->getResponseObject()->getContent());

        self::assertEquals(
            $count,
            $crawler->filter($selector)->count()
        );
    }

    /**
     * Delete data in table.
     *
     * @param string $table
     * @param array  $where
     */
    protected function deleteInTable($table, $where)
    {
        $query = $this->getConnection()->createQueryBuilder()
                      ->delete($table);

        foreach ($where as $column => $value) {
            $query->andWhere("$column = :$column")
                  ->setParameter($column, $value);
        }

        $query->execute();
    }

    /**
     * Delete all data from table.
     *
     * @param string $table
     */
    protected function deleteAllInTable($table)
    {
        $this->deleteInTable($table, []);
    }

    /**
     * See in database.
     *
     * @param string       $table     Table name.
     * @param array|string $condition Where condition.
     *
     * @throws \PHPUnit\Framework\AssertionFailedError
     *
     * @return $this
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function seeInDatabase($table, $condition)
    {
        /** @var \Doctrine\DBAL\Connection $db */
        $db = $this->app['db'];

        $query = $db->createQueryBuilder()
                    ->select('count(*)')
                    ->from($table);

        $where = '';

        if (is_array($condition)) {
            foreach ($condition as $name => $value) {
                $where .= (strlen($where) > 0 ? ' AND ' : '')." `{$name}` = :{$name} ";
                $query->setParameter($name, $value);
            }
        } else {
            $where = $condition;
        }

        $query->where($where);

        $results = (int) $query->execute()->fetchColumn();

        static::assertTrue($results > 0);

        return $this;
    }

    /**
     * Clear global arrays.
     */
    protected function clear()
    {
        $_REQUEST = [];
        $_GET     = [];
        $_POST    = [];
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
