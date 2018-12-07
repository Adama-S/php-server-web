<?php

namespace PHPServerWeb;

use PHPUnit\Framework\TestCase;

final class ServerTest extends TestCase
{

    public function testServerConstructorShouldCreateSocket(): void
    {
    	$mockedSocket = $this->getMockBuilder('\Socket\Raw\Socket')
			->disableOriginalConstructor()
    		->getMock();
       	$mockedFactory = $this->getMockBuilder('\PHPServerWeb\Factory')
            ->setMethods(['createServer'])
            ->getMock();
		$mockedFactory->expects($this->once())
     		->method('createServer')
     		->will($this->returnValue($mockedSocket));

 	    $server = new Server($mockedFactory);
    }

    public function testServerConstructorShouldCreateProtocol(): void
    {
        $mockedSocket = $this->getMockBuilder('\Socket\Raw\Socket')
            ->disableOriginalConstructor()
            ->getMock();
        $mockedFactory = $this->getMockBuilder('\PHPServerWeb\Factory')
            ->setMethods(['createServer','createHttpProtocol'])
            ->getMock();
        $mockedFactory->method('createServer')
            ->will($this->returnValue($mockedSocket));
		$mockedFactory->expects($this->once())
     		->method('createHttpProtocol')
     		->will($this->returnValue(new HttpProtocol()));

 	    $server = new Server($mockedFactory);
    }

    public function testServerRunShouldLoopAsManyAsAsked(): void
    {
    	$numberOfLoopExcpected = 3;

    	$mockedSocket = $this->getMockBuilder('\Socket\Raw\Socket')
			->disableOriginalConstructor()
            ->setMethods(['accept'])
    		->getMock();
		$mockedSocket->expects($this->exactly($numberOfLoopExcpected))
     		->method('accept')
     		->will($this->returnValue($mockedSocket));


     	$mocketProtocol = $this->getMockBuilder('\PHPServerWeb\HttpProtocol')
            ->setMethods(['handleRequest'])
    		->getMock();	
    	$mocketProtocol->expects($this->exactly($numberOfLoopExcpected))
     		->method('handleRequest')
     		->will($this->returnValue(''));


     	$mocketConnection = $this->getMockBuilder('\PHPServerWeb\Connection')
			->disableOriginalConstructor()
            ->setMethods(['readRequest','sendResponseAndClose'])
    		->getMock();	
    	$mocketConnection->expects($this->exactly($numberOfLoopExcpected))
     		->method('readRequest')
     		->will($this->returnValue(''));	
    	$mocketConnection->expects($this->exactly($numberOfLoopExcpected))
     		->method('sendResponseAndClose');


       	$mockedFactory = $this->getMockBuilder('\PHPServerWeb\Factory')
            ->setMethods(['createServer','createHttpProtocol','createConnectionFromSocket'])
            ->getMock();
		$mockedFactory->method('createServer')
     		->will($this->returnValue($mockedSocket));	
     	$mockedFactory->method('createHttpProtocol')
     		->will($this->returnValue($mocketProtocol));
     	$mockedFactory->expects($this->exactly($numberOfLoopExcpected))
     		->method('createConnectionFromSocket')
     		->will($this->returnValue($mocketConnection));

 	    $server = new Server($mockedFactory);
 	    $server->run($numberOfLoopExcpected);
    }

    public function testServerRunShouldPassResponseFromReadRequestToHandldeRequest(): void
    {

    	$test = "some strange string created by readRequest";

    	$mockedSocket = $this->getMockBuilder('\Socket\Raw\Socket')
			->disableOriginalConstructor()
            ->setMethods(['accept'])
    		->getMock();
		$mockedSocket->method('accept')
     		->will($this->returnValue($mockedSocket));


     	$mocketProtocol = $this->getMockBuilder('\PHPServerWeb\HttpProtocol')
            ->setMethods(['handleRequest'])
    		->getMock();	
    	$mocketProtocol->method('handleRequest')
            ->with(
            	$this->callback(function($arg) use ($test) {
            		$this->assertEquals($arg,$test);
                    return true;
                }))
     		->will($this->returnValue(''));


     	$mocketConnection = $this->getMockBuilder('\PHPServerWeb\Connection')
			->disableOriginalConstructor()
            ->setMethods(['readRequest','sendResponseAndClose'])
    		->getMock();	
    	$mocketConnection->method('readRequest')
     		->will($this->returnValue($test));	


       	$mockedFactory = $this->getMockBuilder('\PHPServerWeb\Factory')
            ->setMethods(['createServer','createHttpProtocol','createConnectionFromSocket'])
            ->getMock();
		$mockedFactory->method('createServer')
     		->will($this->returnValue($mockedSocket));	
     	$mockedFactory->method('createHttpProtocol')
     		->will($this->returnValue($mocketProtocol));
     	$mockedFactory->method('createConnectionFromSocket')
     		->will($this->returnValue($mocketConnection));

 	    $server = new Server($mockedFactory);
 	    $server->run($nbLoop = 1);
    } 

    public function testServerRunShouldPassResponseFromHandldeRequestTosendResponseAndClose(): void
    {

    	$test = "some strange string created by handleRequest";

    	$mockedSocket = $this->getMockBuilder('\Socket\Raw\Socket')
			->disableOriginalConstructor()
            ->setMethods(['accept'])
    		->getMock();
		$mockedSocket->method('accept')
     		->will($this->returnValue($mockedSocket));


     	$mocketProtocol = $this->getMockBuilder('\PHPServerWeb\HttpProtocol')
            ->setMethods(['handleRequest'])
    		->getMock();	
    	$mocketProtocol->method('handleRequest')
     		->will($this->returnValue($test));


     	$mocketConnection = $this->getMockBuilder('\PHPServerWeb\Connection')
			->disableOriginalConstructor()
            ->setMethods(['readRequest','sendResponseAndClose'])
    		->getMock();	
    	$mocketConnection->method('readRequest')
     		->will($this->returnValue($test));
    	$mocketConnection->method('sendResponseAndClose')
            ->with(
            	$this->callback(function($arg) use ($test) {
            		$this->assertEquals($arg,$test);
                    return true;
                }));


       	$mockedFactory = $this->getMockBuilder('\PHPServerWeb\Factory')
            ->setMethods(['createServer','createHttpProtocol','createConnectionFromSocket'])
            ->getMock();
		$mockedFactory->method('createServer')
     		->will($this->returnValue($mockedSocket));	
     	$mockedFactory->method('createHttpProtocol')
     		->will($this->returnValue($mocketProtocol));
     	$mockedFactory->method('createConnectionFromSocket')
     		->will($this->returnValue($mocketConnection));

 	    $server = new Server($mockedFactory);
 	    $server->run($nbLoop = 1);
    }

}


