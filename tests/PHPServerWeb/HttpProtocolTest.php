<?php

namespace PHPServerWeb;

use PHPUnit\Framework\TestCase;

final class HttpProtocolTest extends TestCase
{

    /**
     * @dataProvider requestExamples
     */
	function testHttpProtocolHandleRequest($requestFilename,$expectedResponseFilename)
	{

		$request          = file_get_contents(__DIR__.'/'.$requestFilename);
		$expectedResponse = file_get_contents(__DIR__.'/'.$expectedResponseFilename);

    	$connection = new HttpProtocol();
    	$response = $connection->handleRequest($request);
        $this->assertEquals($response,$expectedResponse);
	}

	function requestExamples()
	{
		return [
			["HttpRequestExample/1.txt","HttpResponseExpected/1.txt"],
			["HttpRequestExample/2.txt","HttpResponseExpected/2.txt"],
			["HttpRequestExample/3.txt","HttpResponseExpected/3.txt"],
			["HttpRequestExample/4.txt","HttpResponseExpected/4.txt"],
			["HttpRequestExample/5.txt","HttpResponseExpected/5.txt"],
			["HttpRequestExample/6.txt","HttpResponseExpected/6.txt"],
			["HttpRequestExample/7.txt","HttpResponseExpected/7.txt"],
		];
	}

}