<?php
require_once dirname(__DIR__)."/vendor/autoload.php";
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new \BachPedersen\PhpRiakStress\Command\LoadDataCommand());
$application->run();