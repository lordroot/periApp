<?php
        require_once("websocket.client.php");

        $input = "Hello World!";
        $msg = WebSocketMessage::create($input);

        $client = new WebSocket("ws://127.0.0.1:12345/echo/");
        $client->open();
        $client->sendMessage($msg);

        // Wait for an incoming message
        $msg = $client->readMessage();

        $client->close();

        echo $msg->getData(); // Prints "Hello World!" when using the demo.php server
?>