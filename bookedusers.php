<HTML>
	<HEAD>
		<TITLE>Booked users</TITLE>
		<LINK rel="stylesheet" type="text/css" href="bookedusers.css">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
 



	</HEAD>
	<BODY>







<?php
	$client = new MongoClient;
	$db=$client->airbnb;
	$collection=$db->host;
	session_start();

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
			echo "<div class='welcome'>Welcome,<a href='profile.php'> ".$_SESSION["name"];
			echo "</div></a><button class='badge1' data-badge='".$unread_count."'><a href='notifications.php'>Notifications</a></button><form method=get>";
			echo '<input type="submit" value="Log Out" class="login" name="logout">
			</DIV>
			</form>
			</DIV>';
			if(isset($_GET["logout"]))
		{
			session_destroy();
			header("Location: home3.php");
		}



	$email=$_SESSION["email"];
	$doc=$collection->findOne(
			array(
				"email"=> $email
				)
			);
	foreach ($doc['listings'] as $listing) {
		# code...
				if($listing['listing_id']==$_GET['listing_id'])
				{
					echo '<br><br>
					<br>
					<H2  style="font-family:Open Sans Condensed, sans-serif;font-size:40">Bookings:</h2>
					';
					if(!empty($listing["booked"]))
					{
						foreach ($listing["booked"] as $booking)
						{

							//var_dump($iuser);
							$doc=$collection->findOne(array("email"=>$booking["email"]));
							echo "<p>Name: ".$doc["fname"]." ".$doc["lname"]."<br></p>";
							echo "<p>Contact no.: ".$doc["phno"]."<br></p>";
							echo "<p>From: "; 
							echo date('Y-m-d', $booking["from_date"]->sec)."</p>";
							echo "<p>To: ";
							echo date('Y-m-d', $booking["to_date"]->sec)."</p>";
							echo "<p>Rent per day: Rs. ".$listing['rent']."<br></p>";
							$amount=((int)$listing["rent"])*($booking["to_date"]->sec-$booking["from_date"]->sec)/(3600*24);
							echo "<p>Total rent: Rs. ".$amount."<br></p><br><br>";

							/*echo '
							<form method="get">
							<INPUT TYPE="submit" value="Accept " align="right"  name="accept" class="upload_ad_btn_intrested">
							<input type="hidden" name="listing_id" value="'.$listing["listing_id"].'">
							<input type="hidden" name="from_date" value="'.$iuser["from_date"].'">
							<input type="hidden" name="to_date" value="'.$iuser["to_date"].'">					
							<input type="hidden" name="email" value="'.$iuser["email"].'">
							<input type="hidden" name="rent" value="'.$listing["rent"].'">

							</form>
							
						';*/	

							# code...
						};
					}
					else
					{
						echo "<p>No booked users.</p>";
					}
				}
	}
?>

</BODY>
</HTML>