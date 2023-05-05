<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Escaper;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Http\Response\Cookies;
use Phalcon\Session\Manager;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use MyApp\Handlers\Listner;





$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);
$loader->registerNamespaces(
    [
        'MyApp\Handlers' => APP_PATH . '/handlers/',
        'MyApp\Controllers' => APP_PATH . '/controllers/',
        'MyApp\Models' => APP_PATH . '/models/',
        'Tests' => APP_PATH . '/../tests/',
    ]
);

$loader->registerClasses(
    [
        'Listner'         => APP_PATH .'/handler/Listner.php/',
        
    ]
);

$loader->register();

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);
$container->set(
    'escaper',
    function () {
        return new Escaper();
    }
);

$application = new Application($container);

$eventsManager = new EventsManager();
$eventsManager->attach(
    'application:beforeHandleRequest',
    new Listner()
);

$container->set(
    'EventsManager',
    $eventsManager
);
$application->setEventsManager($eventsManager);

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'store',
            ]
        );
    }
);
$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        
        $session
        ->setAdapter($files)
        ->start();
        return $session;
    }
);
$container->set(
    'cookies',
    function () {
        $cookies = new Cookies();

        $cookies->useEncryption(false);

        return $cookies;
    }
);

$container->set(
    'dispatcher',
    function () {
        $dispatcher = new Dispatcher();

        $dispatcher->setDefaultNamespace(
            'MyApp\Controllers'
        );

        return $dispatcher;
    }
);
$application = new Application($container);
try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
