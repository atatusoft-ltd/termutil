<?php

require_once __DIR__ . "/vendor/autoload.php";

use Atatusoft\Termutil\IO\Console\Cursor;

echo "Hello, World!";
$cursor = Cursor::getInstance();
$cursor->moveTo(10, 20);
$cursor->hide();

echo "This is a test.";
$cursor->show();
