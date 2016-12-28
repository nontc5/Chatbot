<?php
$access_token = 'DB8I5JoQOS3JPLAlIqEhZD35rozSYBvSLlKwXvw+73vOruhQ/nOf3xOwju0zON/uMrmfPYPnJ0PSkmO09sLUhmFP8xo0M5HJpmDpwC5sC4JRGFJzr8+9L953NGyJ6cvNc2l+MwEaABjB393SqnO1MwdB04t89/1O/w1cDnyilFU=';


// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];

			//get userID
			$userId = $event['source']['userId'];
			
			$message = str_replace(' ', '%20', $text);
			if($message == 'ไม่' OR $message == 'ไม่ครับ' OR $message == 'ไม่ค่ะ')
			{
				$message = 'survey';
			}
			$url = "http://nontc5.utcc-ict.com/Chatbot/api/line_call.php?word=$message&userId=$userId";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($ch);
			curl_close($ch);
			$json = json_decode($data, true);
			//echo $data;
			
			
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				//'text' => 'Bot Response: '.$text.$json['message']
				'text' => $json['message']
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);


			// Check if reply message is blank (No Answer)
			if($json['message']!= null OR $json['message'] != '')
			{

				sleep(10);
				// Make a POST Request to Messaging API to Push to sender
				$url = 'https://api.line.me/v2/bot/message/push';
				$surveyQuestion = "ต้องการสอบถามข้อมูลเพิ่มเติมไหมครับ";
				$abc = $json['userid'];
				$messages = [
					'type' => 'text',
					//'text' => 'Bot Response: '.$text.$json['message']
					'text' => $surveyQuestion
				];

				$data = [
					'to' => $userId,
					'messages' => [$messages],
				];
				$post = json_encode($data);
				$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);

				echo $result . "\r\n";

			}

			
		}
	}
}
echo "OK";