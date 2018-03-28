<?php
/**
 * @author Donii Sergii <s.doniy@infomir.com>.
 */

namespace sonrac\FCoverage;

/**
 * Class MaxRedirectException
 * Maximum redirect exception.
 *
 * @author Donii Sergii <doniysa@gmail.com>
 */
class MaxRedirectException extends \Exception
{
    /**
     * Count redirects.
     *
     * @var int
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected $countRedirects = 0;

    /**
     * {@inheritdoc}
     */
    protected $message = 'Redirects stack overflow';

    /**
     * MaxRedirectException constructor.
     *
     * {@inheritdoc}
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function __construct($message = '', $code = 0, \Throwable $previous = null, $countRedirects = 0)
    {
        parent::__construct($message, $code, $previous);

        $this->countRedirects = $countRedirects;
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
     * @param int $count
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function setCountRedirects($count)
    {
        $this->countRedirects = $count;
    }

    /**
     * Get summary error message.
     *
     * @return string
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    public function getMessageSummary()
    {
        return $this->getMessage()." Count redirects: {$this->countRedirects}";
    }
}
