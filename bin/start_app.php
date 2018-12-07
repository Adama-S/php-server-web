<?php
require __DIR__.'/../vendor/autoload.php';

try
{
	\PHPServerWeb\Factory::createHttpServer()->run();	
}
catch(\Exception $e)
{
	echo $e->getMessage();
}
