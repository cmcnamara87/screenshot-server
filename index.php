<?php
require 'vendor/autoload.php';
require 'rb.phar';
// header('Content-type: application/json');

$app = new \Slim\Slim(array(
    'debug' => true
));
$app->contentType("application/json");

// require 'push/push.php';

// Load all the Slim stuff
// Middleware
require 'middleware/middleware.php';

// Routes
require 'routes/session.php';
require 'routes/me.php';
require 'routes/public.php';

// DB
require 'db/db.php';

// Add session middleware
$app->add(new \Slim\Middleware\SessionCookie(
	array(
		'secret' => 'thisismysecret',
		'expires' => '7 days',
	))
);
// Add camelcase middleware
$app->add(new \CamelCaseMiddleware());

$app->run();
