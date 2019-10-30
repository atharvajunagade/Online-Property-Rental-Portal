<!DOCTYPE html>


<HTML>
	<HEAD>
		<TITLE>Notifications</TITLE>

		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="/resources/demos/style.css">

		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<LINK rel="stylesheet" type="text/css" href="nots.css">
 		 <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
	</head>


		
		<body>
		


		<?php
		session_start();
		echo '<DIV class="header_home">
			<form method=get>
			<DIV class="right_of_header">';
			echo "<div class='welcome'>Welcome, <a href='profile.php'> ".$_SESSION["name"];
			echo "</div></a><form method=get>";
			echo '<input type="submit" value="Log Out" class="login" name="logout">
			</DIV>
			</form>
			</DIV>';	
	
		if(isset($_GET["logout"]))
		{
			session_destroy();
			header("Location: home3.php");
		}


		?>
		
<?php
	
	session_start();
	$client = new MongoClient;
	$db=$client->airbnb;
	$collection=$db->host;
	echo '<br><br><br><H1>Notifications:</H1>';		
		echo "<div class='notifications'>";

	//$doc=$collection->findOne(array("email"=>$_SESSION["email"]));
	$doc=$collection->aggregate(array('$match'=>array("email"=>$_SESSION['email'])),array('$unwind'=>'$notifications'),array('$sort'=>array("notifications.date"=>-1)));
	//	var_dump($doc);
	$i=0;
	//usort($doc, function ($a, $b) { return $b['notifications']['date']->sec - $a['notifications']['date']->sec; });
	foreach ($doc as $result)
	{
		foreach ($result as $notification) {
			# code...
			/*foreach ($notification["notifications"] as $notif) {

					//echo "<div id='to_be_read'>.".$notif["text"].".";
					var_dump($notif);
						# code...
					}*/

					$collection->update(array("email"=>$_SESSION["email"]),array('$set'=>array("notifications.".$i++.".read"=>1)));

					if($notification["notifications"]["type"]=="interested"&&$notification["notifications"]["read"]==0)
					{
						echo "<div id='to_be_read'>".$notification["notifications"]["text"]." Click <a href='activelisting.php'>here</a> for more information</div>";	
					}
					elseif($notification["notifications"]["type"]=="interested"&&$notification["notifications"]["read"]==1)
					{
						echo "<div id='read'>".$notification["notifications"]["text"]." Click <a href='activelisting.php'>here</a> for more information</div>";	
					}


					if($notification["notifications"]["type"]=="accept"&&$notification["notifications"]["read"]==0)
					{
						echo "<div id='to_be_read'>".$notification["notifications"]["text"]." Click <a href='bookings.php'>here</a> for more information</div>";	
					}
					elseif($notification["notifications"]["type"]=="accept"&&$notification["notifications"]["read"]==1)
					{
						echo "<div id='read'>".$notification["notifications"]["text"]." Click <a href='bookings.php'>here</a> for more information</div>";	
					}


					if($notification["notifications"]["type"]=="decline"&&$notification["notifications"]["read"]==0)
					{
						echo "<div id='to_be_read'>".$notification["notifications"]["text"]."</div>";	
					}
					elseif($notification["notifications"]["type"]=="decline"&&$notification["notifications"]["read"]==1)
					{
						echo "<div id='read'>".$notification["notifications"]["text"]."</div>";	
					}



					if($notification["notifications"]["type"]=="decline_balance"&&$notification["notifications"]["read"]==0)
					{
						echo "<div id='to_be_read'>".$notification["notifications"]["text"]." Click <a href='profile.php'>here</a> to add more balance to your account.</div>";	
					}
					elseif($notification["notifications"]["type"]=="decline_balance"&&$notification["notifications"]["read"]==1)
					{
						echo "<div id='read'>".$notification["notifications"]["text"]." Click <a href='profile.php'>here</a> to add more balance to your account.</div>";	
					}
		//echo $notification["text"];
		}
	}
	echo '</div>';

?>




</body>
</html>