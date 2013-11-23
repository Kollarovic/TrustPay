<?php

require __DIR__ . '/../vendor/autoload.php';

if (@!include __DIR__ . '/../vendor/nette/tester/Tester/bootstrap.php') {
    echo "Install Nette Tester using `composer update --dev`\n";
    exit(1);
}

Tester\Environment::setup();

function id($val) {
	return $val;
}

$configurator = new Nette\Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(__DIR__ . '/../app')
	->addDirectory(__DIR__ . '/../vendor/others')
	->register();

return $configurator->createContainer();
