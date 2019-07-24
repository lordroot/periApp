#!/php -q
<?php

// Run from command prompt > php demo.php
require_once("websocket.server.php");

/**
 * This demo resource handler will respond to all messages sent to /echo/ on the socketserver below
 *
 * All this handler does is echoing the responds to the user
 * @author Chris
 *
 */
class DemoEchoHandler extends WebSocketUriHandler{
	protected $byte_counter = 0;
	protected $lines = NULL;
	protected $last_line =0;
	protected $total_lines = 0;


	public function onMessage(IWebSocketConnection $user, IWebSocketMessage $msg){
		$this->say("[ECHO] ".strlen($msg->getData()). " bytes");
		if ($msg->getData() != "more") {
			$msg->setData("Please type 'more' to receive a chunk of the file");
		}else{
			$str = $msg->getData(). " command received. Here's a chunk of file: \r\n".$this->getLine();
			$msg->setData($str);
		}
		// Echo
		$user->sendMessage($msg);
	}

	public function onAdminMessage(IWebSocketConnection $user, IWebSocketMessage $obj){
		$this->say("[DEMO] Admin TEST received!");

		$frame = WebSocketFrame::create(WebSocketOpcode::PongFrame);
		$user->sendFrame($frame);
	}
	
	public function getLine(){
		# if the files has not been read yet
		if(is_null($this->lines)){
			$this->lines = array();
			$this->getFile();
		}
		if($this->last_line< $this->total_lines){
			# return a line from $lines
			$line = $this->lines[$this->last_line];
			$this->last_line+=1;
			return $line;
		}else{
			return "sorry no more.";
		}
	}

	public function getFile(){
		$handle = @fopen("/tmp/sendme.txt", "r");
		if ($handle) {
		    while (($buffer = fgets($handle, 4096)) !== false) {
		        // echo $buffer;
		    	array_push($this->lines,$buffer);
		    }
		    if (!feof($handle)) {
		        echo "Error: unexpected fgets() fail\n";
		    }
		    $this->total_lines = count($this->lines);
		    fclose($handle);
		}
	}
}

/**
 * Demo socket server. Implements the basic eventlisteners and attaches a resource handler for /echo/ urls.
 *
 *
 * @author Chris
 *
 */
class DemoSocketServer implements IWebSocketServerObserver{
	protected $debug = true;
	protected $server;
	protected $uriHandler;

	public function __construct(){
		$this->server = new WebSocketServer("tcp://0.0.0.0:12345", 'superdupersecretkey');
		$this->server->addObserver($this);

		$this->uriHandler = $this->server->addUriHandler("echo", new DemoEchoHandler());
	}

	public function onConnect(IWebSocketConnection $user){
		$this->say("[DEMO] {$user->getId()} connected, yay");
	}

	public function onMessage(IWebSocketConnection $user, IWebSocketMessage $msg){
		$msg->setData( "Logging this: ".$msg->getData());
		$this->say("[DEMO] {$user->getId()} says '{$msg->getData()}'.  ");
		
	}

	public function onDisconnect(IWebSocketConnection $user){
		$this->say("[DEMO] {$user->getId()} disconnected");
	}

	public function onAdminMessage(IWebSocketConnection $user, IWebSocketMessage $msg){
		$this->say("[DEMO] Admin Message received!");

		$frame = WebSocketFrame::create(WebSocketOpcode::PongFrame);
		$user->sendFrame($frame);
	}

	public function say($msg){
		echo "$msg  \r\n";
	}

	public function run(){
		$this->server->run();
	}
}

// Start server
$server = new DemoSocketServer();
$server->run();