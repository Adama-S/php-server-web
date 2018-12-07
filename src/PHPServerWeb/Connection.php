<?php

namespace PHPServerWeb;

class Connection
{
	protected $socket = null;
	protected $request = '';
	protected $stopString ;
	protected $chunckSize ;

	function __construct(\Socket\Raw\Socket $socket,string $stopString = "\r\n",$chunckSize = 5)
	{  
		if(is_int($chunckSize)== false)
		{
			throw new \Exception('$chunckSize must be of type integer ,'.gettype($chunckSize).' provided', 1);	
		}
		$this->socket = $socket;
		$this->stopString = $stopString;
		$this->chunckSize = $chunckSize;
	}

	function readRequest() : string
	{
		$this->request = "";
		do {
			$this->request .= $this->socket->read($this->chunckSize);
			$pos = strpos($this->request,$this->stopString);
			if ($pos !== false) 
			{
				$this->request = substr($this->request, 0,$pos+strlen($this->stopString));
			}
		}
		while($pos === false);

		return $this->request;
	}

	function sendResponseAndClose(string $response)
	{
		$this->socket->write($response);
		$this->socket->close();
	}

}

