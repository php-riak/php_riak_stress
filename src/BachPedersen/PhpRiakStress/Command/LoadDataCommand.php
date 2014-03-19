<?php
namespace BachPedersen\PhpRiakStress\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadDataCommand extends RiakCommand
{

    function executeRiakCommand(InputInterface $input, OutputInterface $output, \Riak\Bucket $bucket)
    {
        for ($i=0; $i<1000; ++$i) {
            $o = new \Riak\Object($i);
            $o->setContent('plain/text');
            $o->setContent($i);
            $bucket->put($o);
        }
    }
}
