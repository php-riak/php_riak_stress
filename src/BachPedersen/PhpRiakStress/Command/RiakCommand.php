<?php
namespace BachPedersen\PhpRiakStress\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class RiakCommand extends \Symfony\Component\Console\Command\Command
{
    protected function configure()
    {
        $this
            ->setName('stress:load')
            ->setDescription('Prepare riak for stress test')
            ->addOption(
                'host',
                null,
                InputOption::VALUE_REQUIRED,
                'Riak hostname',
                'localhost'
            )
            ->addOption(
                'port',
                null,
                InputOption::VALUE_REQUIRED,
                'Riak protobuffport',
                '8087'
            )
            ->addOption(
                'bucket',
                null,
                InputOption::VALUE_REQUIRED,
                'Bucket to load',
                '8087'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getOption('host');
        $port = intval($input->getOption('port'));
        $bucketName = $input->getOption('bucket');
        $connection = new \Riak\Connection($host, $port);
        $bucket = $connection->getBucket($bucketName);
        $this->executeRiakCommand($input, $output, $bucket);
    }

    abstract function executeRiakCommand(InputInterface $input, OutputInterface $output, \Riak\Bucket $bucket);

} 