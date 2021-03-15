<?php
	$ONESIGNAL_APP_ID = 'b2d54ef5-defc-47b3-a2c9-cd104d2cd11a';
	$OS_API_URL = 'https://onesignal.com/api/v1/notifications';
	$OS_REST_API_TOKEN = 'MTU2YmQzMzEtNmM1Mi00YzgzLWJlYzgtNDJkNjQzNDE3Mzkx';
	$NOTIFICATION_TITLE = "Quel magnifique lundi";
	$NOTIFICATION_MESSAGE = "Un nouveau jour, une nouvelle semaine, pleins de nouvelles choses vous attendent";	

	$request_payload = array(
		'included_segments' => array('Subscribed Users'),
		'app_id' => $ONESIGNAL_APP_ID,
		'contents' => array('en' => $NOTIFICATION_MESSAGE, 'fr' => $NOTIFICATION_MESSAGE),
		'title' => $NOTIFICATION_TITLE
	);
	$curl = curl_init($OS_API_URL);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Authorization: Basic '.$OS_REST_API_TOKEN,
		'Content-Type: application/json'
	));
    	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request_payload));
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$res = curl_exec($curl);
	curl_close($curl);
	var_dump($res);

?>
