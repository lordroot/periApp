<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
<?php

	require_once("/phpws-master/websocket.client.php");
	require_once("client.php");
	error_reporting(E_ALL);
	class auth
	{
		public $oauth_token = null;
		public $oauth_verifier = null;
		public $session_key = null;
		public $session_secret = null;
		public $loginTwitter = null;
		public $consumer_secret = null;
		
		public $twitter_username = null;
		public $twitter_password = null;
		
		public $params = null;
		
		public function __construct()
		{
			$this->consumer_secret = ""; // consumer secret key...
		}
		
		public function setup()
		{
			if($this->loginTwitter != null)
			{
				$this->loginTwitter = json_decode($this->loginTwitter);
			}
			elseif($this->session_key != null && $this->session_secret != null)
			{
				SignIn3($this->session_key, $this->session_secret);
			}
			elseif($this->oauth_token != null && $this->oauth_verifier != null)
			{
				$this->SignIn2($this->oauth_token, $this->oauth_verifier);
				//$this->streamCreate();
				
				//$this->streamRemove("****");
				
				//var_dump($this->Api("follow",array("user_id" => "****")));
				
				var_dump($this->oauth_token);
				var_dump($this->oauth_verifier);
				var_dump($this->session_key);
				var_dump($this->session_secret);
				var_dump($this->loginTwitter);
				var_dump($this->consumer_secret);
			}
			else
			{
				$this->SignIn1();
			}
		}
		
		#TODO
		public function saveSession()
		{
			
		}
		
		public function processChannel($id)
		{
			$access = json_decode($this->Api("accessChannel",array("broadcast_id" => $id)));
			
			### -- ###
			
			$this->payload = array(
				1 => '{"kind":3,"payload":"{\"access_token\":\"' . $access->access_token . '\"}"}'
				,2 => '{"kind":2,"payload":"{\"body\":\"{\\\"room\\\":\\\"' . $access->room_id . '\\\"}\",\"kind\":1}"}'
				,3 => '{'.
						'"kind":1,'.
						'"payload":'.
							'"{\"body\":'.
								'\"{\\\"ntpForBroadcasterFrame\\\":***,'.
								'\\\"displayName\\\":\\\"' . $this->loginTwitter->user->display_name . '\\\",'.
								'\\\"initials\\\":\\\"\\\",'.
								'\\\"moderationReportType\\\":0,'.
								'\\\"moderationType\\\":0,'.
								'\\\"ntpForLiveFrame\\\":***,'.
								'\\\"participant_index\\\":' . $access->participant_index . ','.
								'\\\"profileImageURL\\\":\\\"https://abs.twimg.com/sticky/default_profile_images/default_profile_0_200x200.png\\\",'.
								'\\\"signer_token\\\":\\\"' . $access->signer_token . '\\\",'.
								'\\\"timestamp\\\":' . time() . ','.
								'\\\"type\\\":3,'.
								'\\\"remoteID\\\":\\\"' . $this->loginTwitter->user->id . '\\\",'. // twitter user->id
								'\\\"username\\\":\\\"' . $this->loginTwitter->user->username . '\\\",'. // twitter name
								'\\\"uuid\\\":\\\"' . $this->guid() . '\\\",'. // file_get_contents('/proc/sys/kernel/random/uuid')
								'\\\"v\\\":2}\",'.
							'\"room\":\"' . $access->room_id . '\",'.
							'\"timestamp\":0}"'.
						'}'
				,4 => '{"kind":2,"payload":"{\"body\":\"{\\\"room\\\":\\\"' . $access->room_id . '\\\"}\",\"kind\":3}"}'
				,"heart" => '{"kind":2,"payload":"{\"body\":\"{\\\"room\\\":\\\"' . $access->room_id . '\\\"}\",\"kind\":2}"}',
				"message" => '{'.
								'"kind":1,'.
								'"payload":'.
									'"{\"body\":'.
										'\"{\\\"body\\\":\\\" test\\\",'.
										'\\\"ntpForBroadcasterFrame\\\":***,'.
										'\\\"displayName\\\":\\\"' . $this->loginTwitter->user->display_name . '\\\",'.
										'\\\"initials\\\":\\\"\\\",'.
										'\\\"moderationReportType\\\":0,'.
										'\\\"moderationType\\\":0,'.
										'\\\"ntpForLiveFrame\\\":***,'.
										'\\\"participant_index\\\":' . $access->participant_index . ','.
										'\\\"profileImageURL\\\":\\\"https://abs.twimg.com/sticky/default_profile_images/default_profile_0_200x200.png\\\",'.
										'\\\"signer_token\\\":\\\"' . $access->signer_token . '\\\",'.
										'\\\"timestamp\\\":' . time() . ','.
										'\\\"type\\\":1,'.
										'\\\"remoteID\\\":\\\"' . $this->loginTwitter->user->id . '\\\",'. // twitter user->id
										'\\\"username\\\":\\\"' . $this->loginTwitter->user->username . '\\\",'. // twitter name
										'\\\"uuid\\\":\\\"' . $this->guid() . '\\\",'. // file_get_contents('/proc/sys/kernel/random/uuid')
										'\\\"v\\\":2}\",'.
									'\"room\":\"' . $access->room_id . '\",'.
									'\"timestamp\":0}"}'
			);
			### -- ###
			$headers = "GET " . parse_url($access->endpoint, PHP_URL_PATH) . "HTTP/1.1\r\n".
			"User-Agent: ChatMan/1 (Android) tv.periscope.android/1.3.4.3 (1900197)\r\n".
			"Upgrade: websocket\r\n".
			"Connection: Upgrade\r\n".
			"Sec-WebSocket-Key : " . base64_encode(openssl_random_pseudo_bytes(16)) . "\r\n".
			"Sec-WebSocket-Version: 13\r\n".
			"Host: " . parse_url($access->endpoint, PHP_URL_HOST) . "\r\n".
			"Accept-Encoding: gzip\r\n\r\n"
			;
			$headerz = array(
				"User-Agent" => "ChatMan/1 (Android) tv.periscope.android/1.3.4.3 (1900197)",
				"Upgrade" => "websocket",
				"Connection" => "Upgrade",
				"Sec-WebSocket-Key" => base64_encode(openssl_random_pseudo_bytes(16)),
				"Sec-WebSocket-Version" => "13",
				"Host" => parse_url($access->endpoint, PHP_URL_HOST),
				"Accept-Encoding" => "gzip"
			);
			
			$WebSocketClient = new WebsocketClient(parse_url($access->endpoint, PHP_URL_HOST), 443, $headers);
			echo $WebSocketClient->sendData($this->payload[1]);
			unset($WebSocketClient);
			//$msg = WebSocketMessage::create($this->payload);
			//$client = new WebSocket(str_replace("https://", "wss://", $access->endpoint . "/chatapi/v1/chatnow"));
			//$client->open();
			//$client->sendMessage($msg);
			//// Wait for an incoming message
			//$msg = $client->readMessage();
			//$client->close();
			//echo $msg->getData(); 
			
			//\Ratchet\Client\connect(str_replace("https://", "wss://", $access->endpoint . "/chatapi/v1/chatnow"),array(),$headerz)->then(function($conn) {
			//	$conn->on('message', function($msg) use ($conn) {
			//		echo "Received: {$msg}\n";
			//		$conn->close();
			//	});
			//	
			//	//$conn->send($this->payload[1]);
			//	//$conn->send($this->payload[2]);
			//	//$conn->send($this->payload[3]);
			//	//$conn->send($this->payload[4]);
			//	//$conn->send($this->payload['message']);
			//}, function ($e) {
			//	echo "Could not connect: {$e->getMessage()}\n";
			//});
			
			//try{
			//    $loop = React\EventLoop\Factory::create();
			//	$connector = new Ratchet\Client\Connector($loop);
            //
			//	$connector(str_replace("https://", "wss://", $access->endpoint . "/chatapi/v1/chatnow"), array(), $headerz)
			//	->then(function(Ratchet\Client\WebSocket $conn) {
			//		$conn->on('message', function(\Ratchet\RFC6455\Messaging\MessageInterface $msg) use ($conn) {
			//			echo "Received: {$msg}\n";
			//			$conn->close();
			//		});
            //
			//		$conn->on('close', function($code = null, $reason = null) {
			//			//echo "Connection closed ({$code} - {$reason})\n";
			//			var_dump($code);
			//		});
            //
			//		$conn->send($this->payload[1]);
			//		$conn->send($this->payload[2]);
			//		$conn->send($this->payload[3]);
			//		$conn->send($this->payload[4]);
			//	}, function(\Exception $e) use ($loop) {
			//		echo "Could not connect: {$e->getMessage()}\n";
			//		$loop->stop();
			//	});
            //
			//	$loop->run();
			//}
			//catch(Exception $e)
			//{
			//	var_dump($e);
			//}
			//$client = new Client();
			//$client->send();
			//$client->send($payload[2]);
			//$client->send($payload[3]);
			//$client->send($payload[4]);
			//$client->send($payload['message']);
			//echo $client->receive() . "<br>";
		}
		
		public function guid()
		{
			$randomString = openssl_random_pseudo_bytes(16);
			$time_low = bin2hex(substr($randomString, 0, 4));
			$time_mid = bin2hex(substr($randomString, 4, 2));
			$time_hi_and_version = bin2hex(substr($randomString, 6, 2));
			$clock_seq_hi_and_reserved = bin2hex(substr($randomString, 8, 2));
			$node = bin2hex(substr($randomString, 10, 6));

			/**
			 * Set the four most significant bits (bits 12 through 15) of the
			 * time_hi_and_version field to the 4-bit version number from
			 * Section 4.1.3.
			 * @see http://tools.ietf.org/html/rfc4122#section-4.1.3
			*/
			$time_hi_and_version = hexdec($time_hi_and_version);
			$time_hi_and_version = $time_hi_and_version >> 4;
			$time_hi_and_version = $time_hi_and_version | 0x4000;

			/**
			 * Set the two most significant bits (bits 6 and 7) of the
			 * clock_seq_hi_and_reserved to zero and one, respectively.
			 */
			$clock_seq_hi_and_reserved = hexdec($clock_seq_hi_and_reserved);
			$clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved >> 2;
			$clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved | 0x8000;

			return sprintf('%08s-%04s-%04x-%04x-%012s', $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $node);
		}
		public function sendMessage($text)
		{
			$access = json_decode($this->Api("accessChannel",array("broadcast_id" => "1OdKrMBYYZYxX")));
			
			### -------------------------------- ###
			{
				$prev_time = 0;
				
				$opts = array(
					'http' => array(
						'method' => "GET",
						'header' => "Host: pubsub.pubnub.com\r\n".
									"User-Agent: \r\n".
									"Accept: application/json, text/javascript, */*; q=0.01\r\n".
									"Content-Type: application/json; charset=UTF-8\r\n",
					)
				);
				
				$context = stream_context_create($opts);
						
				$query = fopen(
					'http://pubsub.pubnub.com/subscribe/' . 
						$access->subscriber . '/' . 
						$access->channel . '-pnpres,' . 
						$access->channel . '/0/' . $prev_time .
						'?auth=' . $access->auth_token, 
						'r', false, $context
				);
				
				$answer = @stream_get_contents($query);
				$answer = str_replace('[','',$answer);
				$answer = str_replace(']','',$answer);
				$answer = str_replace(',','',$answer);
				$answer = str_replace('"','',$answer);
				
				//$time = explode(" ",microtime());
				var_dump($answer);
				$ntpstamp = intval(dechex((intval($answer) - 2208988800 )),16);
			}
			### -------------------------------- ###
			{
				$params = array(
					"body" => $text,
					"signer_token" => $access->signer_token,
					"participant_index" => $access->participant_index,
					// remoteID - uuid - v
					"type" => 1, // "text message"
					"ntpForBroadcasterFrame" => "f25e.9231",
					"ntpForLiveFrame" => "f25e.9231",
				);
				
				$opts = array(
					'http' => array(
						'method' => "POST",
						'header' => "Host: signer.periscope.tv\r\n".
									"User-Agent: \r\n".
									"Accept: application/json, text/javascript, */*; q=0.01\r\n".
									"Content-Type: application/json; charset=UTF-8\r\n",
						'content' => json_encode($params)
					)
				);
				
				$context = stream_context_create($opts);
				
				$query = fopen('https://signer.periscope.tv/sign', 'r', false, $context);
				
				$answer = @stream_get_contents($query);
				
				$signed = json_decode($answer);
				
				var_dump($signed);
				
			}
			### -------------------------------- ###
			
			$opts = array(
				'http' => array(
					'method' => "GET",
					'header' => "Host: pubsub.pubnub.com\r\n".
								"User-Agent: \r\n".
								"Accept: application/json, text/javascript, */*; q=0.01\r\n".
								"Content-Type: application/json; charset=UTF-8\r\n",
				)
			);
			
			$context = stream_context_create($opts);
					
			$query = fopen(
				'http://pubsub.pubnub.com/publish/' . 
					$access->publisher . '/' . 
					$access->subscriber . '/0/' . 
					$access->channel . '/0/' . 
					urlencode(json_encode($signed->message)) . 
					'?auth=' . $access->auth_token, 
					'r', false, $context
			);
			
			$answer = @stream_get_contents($query);
			
			return $answer;
		}
		
		public function sendHeart($amount)
		{
			
		}
		
		#ToFinish
		public function streamRemove($id)
		{
			$this->Api("deleteBroadcast",array("broadcast_id" => $id));
		}
		
		public function streamCreate()
		{
			$stream_data = json_decode($this->Api("createBroadcast",array(
				"lat" => 0,
				"lng" => 0,
				"region" => "eu-central-1",
				"width" => 320,
				"height" => 568
			))); // REGION : us-west-1 | eu-central-1
			
			$temp = $this->Api("publishBroadcast",array(
				"broadcast_id" => $stream_data->broadcast->id,
				"friend_chat" => false,
				"has_location" => false,
				"status" => "YO"
			));
			$input_options = ' -i movie.mp4';
			//$input_name = time() . ".ts";
			//shell_exec("ffmpeg -i \"\" -vf scale=320:568 -c copy -qscale 0 $input_name");
			
			$bashCommand = array(
				'FFOPTS="-vcodec libx264 -b:v 200k -profile:v main -level 2.1 -s ' . $stream_data->broadcast->width . 'x' . $stream_data->broadcast->height . ' -aspect ' . $stream_data->broadcast->width . ':' . $stream_data->broadcast->height . '"',
				'ffmpeg -loglevel quiet ' . $input_options . ' $FFOPTS -vbsf h264_mp4toannexb -t 1 -an out.h264',
				'SPROP=$(h264_analyze out.h264 2>&1 | grep -B 6 SPS | head -n1 | cut -c 4- | xxd -r -p | base64)","$(h264_analyze out.h264 2>&1 | grep -B 5 PPS | head -n1 | cut -c 4- | xxd -r -p | base64)',
				'rm -f out.h264',
				'ffmpeg ' . $input_options . ' -r 1 -s ' . $stream_data->broadcast->width . 'x' . $stream_data->broadcast->height . ' -vframes 1 -y -f image2 orig.jpg',
				'curl -s -T orig.jpg "' . $stream_data->thumbnail_upload_url . '"',
				'rm -f orig.jpg',
					'ffmpeg -re ' . $input_options . ' $FFOPTS -metadata sprop-parameter-sets="$SPROP"' . 
					' -strict experimental -acodec aac -b:a 128k -ar 44100 -ac 1 -f flv' .
					' rtmp://' . $stream_data->host . ':' . $stream_data->port . '/'.$stream_data->application.'?t=' . $stream_data->credential . '/' . $stream_data->stream_name . ' < /dev/null &',
				'  echo -e "\\033[0;32m[OpenPeriscope] `curl -s --form "cookie=' . $this->loginTwitter->cookie . '" --form "broadcast_id=' . $stream_data->broadcast->id . '" https://api.periscope.tv/api/v2/pingBroadcast`\\033[0m"',
				'curl --form "cookie=' . $this->loginTwitter->cookie . '" --form "broadcast_id=' . $stream_data->broadcast->id . '" https://api.periscope.tv/api/v2/endBroadcast'
			);
                $code =
                    '#!/bin/bash' . "\n" .
                    'FFOPTS="-vcodec libx264 -b:v 200k -profile:v main -level 2.1 -s ' . $stream_data->broadcast->width . 'x' . $stream_data->broadcast->height . ' -aspect ' . $stream_data->broadcast->width . ':' . $stream_data->broadcast->height . '"' . "\n" .
                    'ffmpeg -loglevel quiet ' . $input_options . ' $FFOPTS -vbsf h264_mp4toannexb -t 1 -an out.h264' . "\n" . '' . // converting to Annex B mode for getting right NALs
                    'SPROP=$(h264_analyze out.h264 2>&1 | grep -B 6 SPS | head -n1 | cut -c 4- | xxd -r -p | base64)","$(h264_analyze out.h264 2>&1 | grep -B 5 PPS | head -n1 | cut -c 4- | xxd -r -p | base64)' . "\n" . // generating "sprop..."
                    'rm -f out.h264' . "\n" . '' .    // delete temp file
                    'ffmpeg ' . $input_options . ' -r 1 -s ' . $stream_data->broadcast->width . 'x' . $stream_data->broadcast->height . ' -vframes 1 -y -f image2 orig.jpg' . "\n" .
                    'curl -s -T orig.jpg "' . $stream_data->thumbnail_upload_url . '"' . "\n" . '' .
                    'rm -f orig.jpg' . "\n" . '' .
                    'ffmpeg -re ' . $input_options . ' $FFOPTS -metadata sprop-parameter-sets="$SPROP"' .
                    ' -strict experimental -acodec aac -b:a 128k -ar 44100 -ac 1 -f flv' .
                    ' rtmp://' . $stream_data->host . ':' . $stream_data->port . '/'.$stream_data->application.'?t=' . $stream_data->credential . '/' . $stream_data->stream_name . ' < /dev/null &' . "\n" .
					'durated=0' . "\n" .
					'duration=$(ffmpeg -i movie.mp4 2>&1 | grep "Duration"| cut -d \' \' -f 4 | sed s/,// | sed \'s@\..*@@g\' | awk \'{ split($1, A, ":"); split(A[3], B, "."); print 3600*A[1] + 60*A[2] + B[1] }\')' . "\n" .
					'while [ $durated -le $duration ]' . "\n" .
                    ' do' . "\n" .
                    '  echo -e "\\033[0;32m[OpenPeriscope] `curl -s --form "cookie=' . $this->loginTwitter->cookie . '" --form "broadcast_id=' . $stream_data->broadcast->id . '" https://api.periscope.tv/api/v2/pingBroadcast`\\033[0m"' . "\n" .
                    '  sleep 20s' . "\n" .
					'  durated=$(( $durated + 20 ))' . "\n" .
                    ' done' . "\n" .
                    'done';
				$finishStream = 'curl --form "cookie=' . $this->loginTwitter->cookie . '" --form "broadcast_id=' . $stream_data->broadcast->id . '" https://api.periscope.tv/api/v2/endBroadcast';
				
			$file = time() . '.sh';
			file_put_contents($file, $code);
			file_put_contents("finishStream.sh", $code);
			shell_exec("chmod +x $file");
			shell_exec("chmod +x finishStream.sh");
			
			//for($i = 0 ; $i < 5 ; $i++)
			//{
			//	$handle = popen("./$file", 'r');
			//	pclose($handle);
			//	
			//	echo "STREAM #$i @ " . time() . "<br>";
			//}
			//
			//shell_exec($finishStream);
		}
		
		public function Api($method, $params)
		{
			if (is_null($this->params))
			{
				$this->params = array();
			}
			
			//$this->params = json_encode($params);
			
			if(isset($this->loginTwitter) && !empty($this->loginTwitter) && isset($this->loginTwitter->cookie) && !empty($this->loginTwitter->cookie))
			{
				$params['cookie'] = $this->loginTwitter->cookie;
			}
			
			$opts = array(
				'http' => array(
					'method' => "POST",
					'header' => "Host: api.periscope.tv\r\n".
								"User-Agent: \r\n".
								"Accept: application/json, text/javascript, */*; q=0.01\r\n".
								"Content-Type: application/json; charset=UTF-8\r\n",
								//"X-Requested-With: XMLHttpRequest\r\n".
								//"Cookie: \r\n",
					'content' => json_encode($params)
				)
			);
			
			$context = stream_context_create($opts);
			
			$query = fopen('https://api.periscope.tv/api/v2/' . $method, 'r', false, $context);
			
			$answer = @stream_get_contents($query);
			
			return $answer;
		}
		
		public function SignIn3($session_key, $session_secret) 
		{
			$result = $this->Api('loginTwitter',array("session_key" => $session_key, "session_secret" => $session_secret));
			
			$this->loginTwitter = json_decode($result);
			
			if(!$this->loginTwitter->user->username)
			{
				$result = $this->Api('verifyUsername',array(
					"username" => $this->loginTwitter->suggested_username, 
					"display_name" => $this->loginTwitter->user->display_name)
				);
				
				$result = json_decode($result);
				
				if($result['success'])
				{
					$this->loginTwitter->user = $result['user'];
				}
			}
		}
		
		public function SignIn2($oauth_token, $oauth_verifier) 
		{
			$this->OAuth("access_token", function ($oauth) {
				$this->session_key = $oauth['oauth_token'];
				$this->session_secret = $oauth['oauth_token_secret'];
				$this->SignIn3($this->session_key, $this->session_secret);
			},'{"oauth_token": "'.$this->oauth_token.'", "oauth_verifier": "'.$this->oauth_verifier.'"}');
		}
		
		public function SignIn1() 
		{
			if (!is_null($this->consumer_secret)) 
			{
				$this->OAuth("request_token",function ($oauth) {
					$this->allowAccount("https://api.twitter.com/oauth/authorize?oauth_token=" . $oauth['oauth_token']);
				},"{\"oauth_callback\": ".urlencode("404")."}"); // \"http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]\"
			}
		}
		
		public function allowAccount($url)
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/'. time() . '.txt');
			curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/'. time() . '.txt');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_REFERER, "https://127.0.0.1");
			curl_setopt($ch, CURLOPT_USERAGENT,
				"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			if (curl_errno($ch)) die(curl_error($ch));
			
			
			$doc = new DOMDocument();
			@$doc->loadHTML($response);
			$xpath = new DOMXpath($doc);
			
			$input_list = $xpath->query("//input");
			$param = array(
				"authenticity_token" => "",
				"redirect_after_login" => "",
				"oauth_token" => "",
				"session[username_or_email]" => $this->twitter_username,
				"session[password]" => $this->twitter_password,
				"remember_me" => 1
			);
			foreach($input_list as $node)
			{
				if($node->getAttribute("name") == "authenticity_token")
					$param['authenticity_token'] = $node->getAttribute("value");
				if($node->getAttribute("name") == "redirect_after_login")
					$param['redirect_after_login'] = $node->getAttribute("value");
				if($node->getAttribute("name") == "oauth_token")
					$param['oauth_token'] = $node->getAttribute("value");
			}
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
			curl_setopt($ch, CURLOPT_REFERER, $url);
			
			$authed = curl_exec($ch);
			
			if (curl_errno($ch)) print curl_error($ch);
			$info_auth = preg_match('/url=404\?(.+)["]/',$authed,$matches);

			$auth_brut = explode('&',$matches[1]);
			
			$auth_token = explode('=',$auth_brut[0]);
			$auth_verif = explode('=',$auth_brut[1]);
			
			$this->oauth_token = $auth_token[1];
			$this->oauth_verifier = $auth_verif[1];
		}
		
		public function OAuth($endpoint, $callback, $extra) 
		{
			$params = array(
								"oauth_consumer_key" => "***", // put ur own ...
								"oauth_nonce"		 => time(),
								"oauth_signature_method" => "HMAC-SHA1",
								"oauth_timestamp" => time(),
								"oauth_version" => "1.0"
			);
			
			$extra = json_decode($extra);
			
			if(!is_null($extra))
			{
				foreach($extra as $ex => $tra)
				{
					$params[$ex] = $tra;
				}
			}
			
			$signatureBase = array();
			
			$keys = $params;
			ksort($keys);
			
			foreach($keys as $ex => $tra)
			{
				$signatureBase[] = $ex . "=" . $tra;
			}
			
			$signatureBaseString = "POST&" . urlencode('https://api.twitter.com/oauth/' . $endpoint) . "&" . urlencode(implode('&',$signatureBase));

			if($this->session_secret == null) $this->session_secret = "";
			
			$params['oauth_signature'] = urlencode(base64_encode(hash_hmac('sha1', $signatureBaseString, $this->consumer_secret . "&" . ($this->session_secret || ''), TRUE)));
			ksort($params);
			$params_prepared = array();
			
			foreach($params as $par => $ams)
			{
				$params_prepared[] = $par . '="' . $ams . '"'; 
			}
			
			$opts = array(
				'http' => array(
					'method' => "POST",
					'header' => "Authorization: OAuth " . implode(', ', $params_prepared) . "\r\n".
								"Accept: \r\n".
								"User-Agent: \r\n".
								"Expect: \r\n"
				)
			);
			$context = stream_context_create($opts);
			
			$query = fopen('https://api.twitter.com/oauth/' . $endpoint, 'r', false, $context);
			
			$answer = @stream_get_contents($query);
			$answer = explode("&",$answer);
			$fallback = array();
			
			for($i = 0; $i < count($answer);$i++)
			{
				$answer[$i] = explode("=",$answer[$i]);
				$fallback[$answer[$i][0]] = $answer[$i][1];
			}
			
			$callback($fallback);
		}
	}
	exit();
	$instance = new auth();
	$instance->twitter_username = "**";
	$instance->twitter_password = "**";
	$instance->setup();
	$instance->setup();
	var_dump($instance->processChannel("***"));
	//$files = scandir(".");
	//var_dump($files);
?>
	</body>
</html>