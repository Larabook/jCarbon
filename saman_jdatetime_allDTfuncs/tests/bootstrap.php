<?php
// phpunit --bootstrap bootstrap.php JDateTimeTest.php
function loader($class)
{
	$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . $class . '.php';
	if (file_exists($file)) {
		require $file;
	}
}
spl_autoload_register('loader');