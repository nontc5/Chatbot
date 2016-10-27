<?php
$access_token = 'DB8I5JoQOS3JPLAlIqEhZD35rozSYBvSLlKwXvw+73vOruhQ/nOf3xOwju0zON/uMrmfPYPnJ0PSkmO09sLUhmFP8xo0M5HJpmDpwC5sC4JRGFJzr8+9L953NGyJ6cvNc2l+MwEaABjB393SqnO1MwdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;