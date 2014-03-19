<?php

namespace BachPedersen\PhpRiakStress;


use Riak\Connection;
use Riak\Exception\RiakException;
use Riak\PoolInfo;

class GetThread extends \Thread
{

    /**
     * @var string
     */
    public $output = "";

    /**
     * @var string
     */
    public $bucketName;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $host;

    /**
     * @param string $host
     * @param int $port
     * @param string $bucketName
     */
    public function __construct($host, $port, $bucketName)
    {
        $this->bucketName = $bucketName;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * The run method of a Thread is executed in a Thread when a call to Thread::start is made
     * @return mixed The methods return value, if used, will be ignored
     * @since 0.34
     */
    public function run()
    {
        $threadId = $this->getThreadId();
        $this->output = "Starting run at ".time().PHP_EOL;
        $this->output .= "ThreadID: ". $threadId .PHP_EOL;

        $connection = new Connection($this->host, $this->port);
        $bucket = $connection->getBucket($this->bucketName);
        for ($i=0; $i<1000; ++$i) {
            try {
                $thisContent = ($threadId + $i) % 1000;
                $getOutput = $bucket->get("$thisContent");
                $content = $getOutput->getObject()->getContent();
                if (strcmp($content, "$thisContent") !== 0) {
                    $this->output .= "! Difference key: $thisContent, content: $content".PHP_EOL;
                    $this->output .= PoolInfo::getNumActiveConnection()."/".PoolInfo::getNumActivePersistentConnection()."/".PoolInfo::getNumReconnect().PHP_EOL;
                }
            } catch (RiakException $ex) {
                $this->output .= "* ".$ex->getMessage().PHP_EOL;
            }
        }
    }
}