<?php

namespace PHPServerWeb;

use PHPUnit\Framework\TestCase;

final class ConnectionTest extends TestCase
{
	function testConnectionSendResponseAndClose()
	{
		$test = "This is a sample fake response";

    	$mockedSocket = $this->getMockBuilder('\Socket\Raw\Socket')
			->disableOriginalConstructor()
            ->setMethods(['write','close'])
    		->getMock();
    	$mockedSocket->expects($this->once())
    	    ->method('write')
            ->with(
            	$this->callback(function($arg) use ($test) {
            		$this->assertEquals($arg,$test);
                    return true;
                }));
    	$mockedSocket->expects($this->once())
    	    ->method('close');

    	$stopString = "\n\n";
    	$chunckSize = 5;

    	$connection = new Connection($mockedSocket,$stopString,$chunckSize);
    	$connection->sendResponseAndClose($test);
	}

    /**
     * @dataProvider requestExamples
     */
	function testConnectionReadRequest($stopString,$chunckSize,$request)
	{
		$requestSplited = str_split($request, 6);

    	$mockedSocket = $this->getMockBuilder('\Socket\Raw\Socket')
			->disableOriginalConstructor()
            ->setMethods(['read'])
    		->getMock();
    	$mockedSocket->expects($this->exactly(count($requestSplited)))
            ->method('read')
    		->willReturnOnConsecutiveCalls(...$requestSplited);

    	$connection = new Connection($mockedSocket,$stopString,$chunckSize);
    	$requestReaded = $connection->readRequest();
        $this->assertEquals($requestReaded,$request);

	}

	function requestExamples()
	{
		return [
			["\n\n",5,"This is a sample fake response\n\n"],
			["\n\n",5,"This is a sample fake response with stop split in two\n\n"],
		];
	}



}