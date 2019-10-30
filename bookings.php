<!DOCTYPE html>
<html>
<head>

	<title>Bookings</title>
	<LINK rel="stylesheet" type="text/css" href="bookings.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
</head>
<body>

<?php

	session_start();
	$client = new MongoClient;
	$db=$client->airbnb;
	$collection=$db->host;
	$doc=$collection->findOne(array("email"=>$_SESSION["email"]));
	$unread_count=0;
	foreach ($doc["notifications"] as $notification)
	{
		if($notification["read"]==0)
		{
			$unread_count++;
		}
		# code...
	}


	echo '<DIV class="header_home">
			<form method=get>
			<DIV class="right_of_header">';
			echo "<div class='welcome'>Welcome, <a href='profile.php'> ".$_SESSION["name"];
			echo "</div></a><button class='badge1' data-badge='".$unread_count."'><a href='notifications.php'>Notifications</a></button><form method=get>";
			echo '<input type="submit" value="Log Out" class="login" name="logout">
			</DIV>
			</form>
			</DIV><br><br><br>';
			if(isset($_GET["logout"]))
		{
			session_destroy();
			header("Location: home3.php");
		}


	

	$doc=$collection->findOne(array("email"=>$_SESSION["email"]));
	//var_dump($doc);
	if(!empty($doc["stays"]))
	{
		echo '<div class="bookings">';
		echo "<p class='details'>Note: Cancelling your booking will cost you 20% of your amount paid as refund processing fee.</p>";
		echo "<br><br><H1 class='detailss'>Your bookings:</h1><br><br>";
		foreach ($doc["stays"] as $stays)
		{
			
			//var_dump($stays);
			$doc1=$collection->findOne(array("listings.listing_id"=>new MongoId($stays["listing_id"])));
			//var_dump($doc1);
			foreach ($doc1["listings"] as $listing)
			{
				if($listing["listing_id"]==$stays["listing_id"])
				{
					$amount=((int)$listing["rent"])*($stays["to_date"]->sec-$stays["from_date"]->sec)/(3600*24);
					echo "<p class='details'>Address: </p><p id='data'>".$listing["address"].'</p><br>';
					echo "<p class='details'>Area: </p><p id='data'><p id='data'>".$listing["area"].'</p><br>';
					echo "<p class='details'>City: </p><p id='data'><p id='data'>".$listing["city"].'</p><br>';
					echo "<p class='details'>Rent per day: Rs. </p><p id='data'>".$listing["rent"].'</p><br>';
					echo "<p class='details'>Number of rooms: </p><p id='data'>".$listing["rooms"].'</p><br>';
					echo "<p class='details'>Check-in date: </p><p id='data'>".date("Y-m-d",$stays["from_date"]->sec).'</p><br>';
					echo "<p class='details'>Check-out date: </p><p id='data'>".date("Y-m-d",$stays["to_date"]->sec).'</p><br>';
					echo "<p class='details'>Amount paid: Rs. </p><p id='data'>".$amount.'</p><br><br>';

					echo "<form method='get'><input name='cancel' type='submit' value='Cancel' class='cancel'>
					<input type='hidden' name='listing_id' value='".$listing['listing_id']."'>
					<input type='hidden' name='refund' value='".($amount*0.8)."'>
					</form><br><br><br><br>";

				}
				# code...
			}
			# code...
		}
	}
	else
	{
		echo "<br><br><h1 class='details' align=center>You don't have any bookings.</h1><br>";
	}
	echo '</div>';

?>

<?php

	if(isset($_GET['cancel']))
	{
		$collection->update(
						array(
								'email'=>$_SESSION['email'],
							),	
						array(
							'$pull'=>array(
								"stays"=>array(
									'listing_id'=>new MongoId($_GET['listing_id']),
									)
								)
							)
						);

		$refund=(int)$_GET['refund'];
		$res1=$collection->update(
							array("email" => $_SESSION["email"]),
							array('$inc'=>array("wallet_balance"=>$refund))
						);
		
		$refund=$refund*-1;
		
		$res1=$db->admin->update(
							array(),
							array('$inc'=>array("wallet_balance"=>$refund))
						);
		echo "<script type='text/javascript'>window.top.location='bookings.php';</script>";
	}
?>

</body>
</html>