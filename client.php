<?php

	class WebsocketClient
	{
		private $_Socket = null;
		public function __construct($host, $port, $token)
		{
			$this->_connect($host, $port, $token);
		}
		public function __destruct()
		{
			$this->_disconnect();
		}
		public function sendData($data)
		{
			// send actual data:
			fwrite($this->_Socket, "\x00" . $data . "\xff" ) or die('Error:' . $errno . ':' . $errstr);
			$wsData = fread($this->_Socket, 2000);
			$retData = trim($wsData,"\x00\xff");
			return $retData;
		}
		private function _connect($host, $port, $header)
		{
			$this->_Socket = fsockopen($host, $port, $errno, $errstr, 2);
			fwrite($this->_Socket, $header) or die('Error: ' . $errno . ':' . $errstr);
			$response = fread($this->_Socket, 2000);
			print_r($response);
			/**
			 * @todo: check response here. Currently not implemented cause "2 key handshake" is already deprecated.
			 * See: http://en.wikipedia.org/wiki/WebSocket#WebSocket_Protocol_Handshake
			 */
			return true;
		}
		private function _disconnect()
		{
			fclose($this->_Socket);
		}
	}