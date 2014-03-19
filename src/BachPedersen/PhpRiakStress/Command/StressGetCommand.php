<?php
namespace BachPedersen\PhpRiakStress\Command;


use Riak\Connection;
use Riak\PoolInfo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StressGetCommand extends RiakCommand
{

    protected function configure()
    {
        parent::configure();
        $this
            ->setName('stress:get')
            ->addOption(
                'time',
                null,
                InputOption::VALUE_REQUIRED,
                'Run gets time (S)',
                '300'
            );
    }

    function executeRiakCommand(InputInterface $input, OutputInterface $output, $host, $port, $bucketName)
    {
        // TODO Start new threads
        $timeMs = intval($input->getOption('time'))*1000;
        $startTime = time();
        $i = 0;
        while ((time() - $startTime) < $timeMs) {
            $connection = new \Riak\Connection($host, $port);
            $bucket = $connection->getBucket($bucketName);
            $getOutput = $bucket->get("$i");
            $content = $getOutput->getObject()->getContent();
            if (strcmp($content, "$i") !== 0) {
                echo "! Difference key: $i, content: $content".PHP_EOL;
                echo "! Num active connections $content".PoolInfo::getNumActiveConnection().PHP_EOL;
                echo "! Num active persistent connections $content".PoolInfo::getNumActivePersistentConnection().PHP_EOL;
                echo "! Num reconnects $content".PoolInfo::getNumReconnect().PHP_EOL;
            }
            $i++;
            if ($i >= 1000) {
                $i = 0;
            }
        }
    }
}