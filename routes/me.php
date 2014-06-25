<?php
// date_default_timezone_set("Australia/Brisbane");
// define('PING_NEARBY_DISTANCE_METERS', 500);
// define('PING_TIMEOUT_MINUTES', 30);
// define('PING_PUSH_TIMEOUT_MINUTES', 1);
// define('LOCATION_TIMEOUT_MINUTES', 5);

$_SESSION['userId'] = 1;

// Restricted to logged in current user
$app->group('/me', $authenticate($app), function () use ($app) {
	$app->get('/hello', function() use ($app) {
		echo '{"hello": "world"}';
	});

	$app->get('/setup', function() use ($app) {
		R::nuke();

		// $_SESSION['userId'] = 1;
		$user = R::dispense('user');
		$user->name = 'Craig McNamara';
		$user->email = 'cmcnamara87@gmail.com';
		R::store($user);

		echo json_encode($user->export(), JSON_NUMERIC_CHECK);
	});
	$app->get('/user', function() {
		$user = R::load('user', $_SESSION['userId']);	
		echo json_encode($user->export(), JSON_NUMERIC_CHECK);
	});
	
	$app->get('/collections/:collectionId', function($collectionId) {
		$collection = R::load('collection', $collectionId);
		$expCollection = $collection->export();
		unset($expCollection->ownFile);
		echo json_encode($expCollection, JSON_NUMERIC_CHECK);
	});

	$app->get('/collections/:collectionId/files', function($collectionId) {
		$collection = R::load('collection', $collectionId);
		echo json_encode(R::exportAll($collection->ownFileList), JSON_NUMERIC_CHECK);
	});

	$app->post('/screenshots', function () {
	    if (!isset($_FILES['file'])) {
	        echo "No file uploaded!!";
	        return;
	    }

	    $user = R::load('user', $_SESSION['userId']);

	    $uploaddir = "/var/www/html/screenshot/uploads/";
		$uploadfile = $uploaddir . basename($_FILES['file']['name']);

		// echo '<pre>';
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
			$app->halt(400, 'Possible file upload attack');
		}

		// Collection
		$collection = R::dispense('collection');
		$collection->user = $user;
		R::store($collection);

		// File upload success
		$file = R::dispense('file');
		$file->name = basename($_FILES['file']['name']);
		$file->user = $user;
		$file->collection = $collection;
		R::store($file);

		$collection = R::load('collection', $collection->id);

		echo json_encode($collection->export(), JSON_NUMERIC_CHECK);
	});
});