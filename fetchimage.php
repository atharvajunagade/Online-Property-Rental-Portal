<?php

	session_start();
	$listing_id=$_GET['listing_id'];
	$client = new MongoClient;
	$db=$client->airbnb;
	$grid = $db->getGridFS();
	$res=$grid->findOne(
		array(	
			"listing_id" => $listing_id
			)
		);
	header("Content-Type: image/jpeg");
	echo $res->getBytes();
?>