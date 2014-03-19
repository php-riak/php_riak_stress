<?php
namespace BachPedersen\PhpRiakStress\Command;

use BachPedersen\PhpRiakStress\GetThread;
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
            )
            ->addOption(
                'threads',
                null,
                InputOption::VALUE_REQUIRED,
                'Run gets time (S)',
                '50'
            );
    }

    function executeRiakCommand(InputInterface $input, OutputInterface $output, $host, $port, $bucketName)
    {
        $timeMs = intval($input->getOption('time'));
        $wantedThreads = intval($input->getOption('threads'));
        $startTime = time();

        /** @var $threads GetThread[] */
        $threads = [];
        for ($i = 0; $i<$wantedThreads; ++$i) {
            $threads[$i] = new GetThread($host, $port, $bucketName);
        }
        while ((time() - $startTime) < $timeMs) {
            for ($i = 0; $i<$wantedThreads; ++$i) {
                if (!$threads[$i]->isRunning()) {
                    if (strlen($threads[$i]->output) > 0) {
                        echo $threads[$i]->output;
                        $threads[$i]->output = "";
                    }
                    $threads[$i]->join();
                    $threads[$i] = new GetThread($host, $port, $bucketName);
                    $threads[$i]->start();
                }
            }
        }
        for ($i = 0; $i<$wantedThreads; ++$i) {
            $threads[$i]->join();
        }
    }
}