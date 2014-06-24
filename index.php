<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();

$app->get('/hello/:name', function ($name) {
    echo "Hello, $name";
});

$app->post('/screenshots', function () {
    if (!isset($_FILES['file'])) {
        echo "No file uploaded!!";
        return;
    }

    $imgs = array();

    // $files = $_FILES['uploads'];
    // 
    $uploaddir = '/uploads';
	$uploadfile = $uploaddir . basename($_FILES['file']['name']);

	echo '<pre>';
	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
    	echo "File is valid, and was successfully uploaded.\n";
	} else {
    	echo "Possible file upload attack!\n";
	}

	echo 'Here is some more debugging info:';
	print_r($_FILES);

	print "</pre>";

});
    // $cnt = count($files['name']);
    // for($i = 0 ; $i < $cnt ; $i++) {
    //     if ($files['error'][$i] === 0) {
    //         $name = uniqid('img-'.date('Ymd').'-');
    //         if (move_uploaded_file($files['tmp_name'][$i], 'uploads/' . $name) === true) {
    //             $imgs[] = array('url' => '/uploads/' . $name, 'name' => $files['name'][$i]);
    //         }

    //     }
    // }

    // $imageCount = count($imgs);
    // if ($imageCount == 0) {
    //     echo 'No files uploaded!!  <p><a href="/">Try again</a>';
    //     return;
    // }

    // $plural = ($imageCount == 1) ? '' : 's';

$app->run();