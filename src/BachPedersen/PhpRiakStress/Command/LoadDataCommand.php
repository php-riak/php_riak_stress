<?php
namespace BachPedersen\PhpRiakStress\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadDataCommand extends RiakCommand
{

    protected function configure()
    {
        parent::configure();
        $this->setName('stress:load');;
    }

    function executeRiakCommand(InputInterface $input, OutputInterface $output, $host, $port, $bucketName)
    {
        $connection = new \Riak\Connection($host, $port);
        $bucket = $connection->getBucket($bucketName);
        for ($i=0; $i<1000; ++$i) {
            $o = new \Riak\Object($i);
            $o->setContent('plain/text');
            $o->setContent($i);
            $bucket->put($o);
        }
    }
}
