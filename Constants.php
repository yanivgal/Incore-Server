<?php

define("TIMEOUT", 30);

define('BASE_DIR', __DIR__ . '\\');
define('HOST', $_SERVER['HTTP_HOST']);
//define('BASE_URL', $_SERVER['REQUEST_URI']);
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/incore/');
define('SHOW_LAST_IMAGE', BASE_URL . 'inputs/last');
define('SHOW_DEFAULT_IMAGE', BASE_URL . 'inputs/default');
define('SHOW_LAST_IMAGE_RESULT', BASE_URL . 'results/last');
define('SHOW_DEFAULT_IMAGE_RESULT', BASE_URL . 'results/default');

define('DEV_ENVIROMENT', 'dev');
define('PROD_ENVIROMENT', 'prod');

define('LAST_IMAGE', 'last');
define('DEFAULT_IMAGE', 'default');
define('NEW_IMAGE', 'new');
define('LAST_IMAGE_PATH', BASE_DIR . 'inputs\\image_from_64.jpeg');
define('DEFAULT_IMAGE_PATH', BASE_DIR . 'inputs\\default\\image_from_64.jpeg');
define('NEW_IMAGE_PATH', BASE_DIR . 'inputs\\image_from_64.jpeg');
define('LAST_IMAGE_RESULT_PATH', BASE_DIR . 'results\\last.jpeg');
define('DEFAULT_IMAGE_RESULT_PATH', BASE_DIR . 'results\\default.jpeg');

define("SCRIPT", "findCircles");
define("COMMAND", 'matlab -nosplash -nojvm -r ' . SCRIPT);
define("SCRIPT_OUTPUT", BASE_DIR . 'results\\result.txt');
define("FINISHED_PROCESS", BASE_DIR . 'results\\ok');

define("DEFAULT_MIN_RADIUS", 20);
define("DEFAULT_MAX_RADIUS", 50);
define("DEFAULT_SENSITIVITY", 0.90);