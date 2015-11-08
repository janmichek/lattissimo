<?php

use Nette\Application\Routers\Route;


// Load libraries
require __DIR__ . '/libs/autoload.php';

$configurator = new Nette\Configurator;

$configurator->setDebugMode(true);
$configurator->enableDebugger(__DIR__ . '/log');
$configurator->setTempDirectory(__DIR__ . '/temp');

$container = $configurator->createContainer();

// very simple router - NAME FROM URL - NO PRESENTER
$router = $container->getService('router');
$router[] = new Route('[<name/>]', function ($presenter) use ($container) {

    $httpRequest = $container->getByType('Nette\Http\Request');

    //get name from url
    $path = substr($httpRequest->getUrl()->path, 1);

    if (!$path) {
        $path = 'welcome';
    }

    $filename = __DIR__ . '/templates/' . $path . '.latte';

    if (!is_file($filename)) {
        $filename = __DIR__ . '/templates/error.latte';
    }

    $template = $presenter->createTemplate()->setFile($filename);
    $template->templateName = $path;

    return $template;
});

// Run the application!
$container->getService('application')->run();