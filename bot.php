<?php
require_once('./line_class.php');
require_once('./unirest-php-master/src/Unirest.php');
$channelAccessToken = 'nBOZlu9u30ITxAt1tZXkbvAHsgb2/EIHhBo8mwuzg/dqIAhJNjqW/A97MBf2lX2B+5L7NicAQYMLSJh6vw/MZ6Gpsbbj1am/jIHH18e9azTknd/6Jxi2qFEMMFlmrrjHixXEE4hQKCkJw/DbNW7z9gdB04t89/1O/w1cDnyilFU='; //sesuaikan 
$channelSecret = '7bc5b547eb74a52213c4e88af08da151';//sesuaikan
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
$userId     = $client->parseEvents()[0]['source']['userId'];
$groupId    = $client->parseEvents()[0]['source']['groupId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp  = $client->parseEvents()[0]['timestamp'];
$type       = $client->parseEvents()[0]['type'];
$message    = $client->parseEvents()[0]['message'];
$messageid  = $client->parseEvents()[0]['message']['id'];
$profil = $client->profil($userId);
$pesan_datang = explode(" ", $message['text']);
$msg_type = $message['type'];
$command = $pesan_datang[0];
$options = $pesan_datang[1];
if (count($pesan_datang) > 2) {
    for ($i = 2; $i < count($pesan_datang); $i++) {
        $options .= '+';
        $options .= $pesan_datang[$i];
    }
}
#-------------------------[Close]-------------------------#
function connect($end_point, $post) {
	$_post = array();
	if (is_array($post)) {
		foreach ($post as $name => $value) {
			$_post[] = $name.'='.urlencode($value);
		}
	}
	$ch = curl_init($end_point);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	if (is_array($post)) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
	}
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	$result = curl_exec($ch);
	if (curl_errno($ch) != 0 && empty($result)) {
		$result = false;
	}
	curl_close($ch);
	return $result;
}
function order($keyword) { 
$split = split ("\.", $keyword); 
$api_url = 'url'; // api url
$post_data = array(
	'api_key' => 'key',
	'action' => 'order',
	'service' => $split[0], 
	'data' => $split[1],
	'quantity' => $split[2],
);
$api = json_decode(connect($api_url, $post_data));
print_r($api);
}
//show menu, saat join dan command,menu
if($message['type']=='text') {
        if ($command == '/order') {
        $result = order($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array( 
                    'type' => 'text',
                    'text' => 'Successs, please wait a few minute'
                )
            )
        );
    }
}
if (isset($balas)) {
    $result = json_encode($balas);
//$result = ob_get_clean();
    file_put_contents('./balasan.json', $result);
    $client->replyMessage($balas);
}
?>
