<HTML>
	<HEAD>
		<TITLE>Confirmation of ad</TITLE>
		<LINK rel="stylesheet" type="text/css" href="listing_default.css">

		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
	</HEAD>
	<BODY>
		<!--<DIV class="header_home">
		</DIV>
		<div class="display_ad">-->
			
			
			<!--<H4 class="details">Number of rooms:</H4><textarea size="100" id="no_of_rooms"></textarea>
			<H4 class="details">Address:</H4><textarea size="100" id="address"></textarea>
			<H4 class="details">Area:</H4><textarea size="100" id="area"></textarea>
			<H4 class="details">City</H4><textarea size="100" id="city"></textarea>
			<H4 class="details">Rent per day :</H4><textarea size="100" id="available_tills"></textarea>-->
		<?php
		session_start();
		if(!$_SESSION["loggedIn"])
		{
			echo '<DIV class="header_home">
			
				
				<DIV class="right_of_header">
				
				<BUTTON class="login"><a href="login2.php" style="color:darkblue;font-size:75%;">LOG IN</a></BUTTON>   
				<BUTTON class="login"><a href="sign_up3.php" style="color:darkblue;font-size:75%;">SIGN UP</a></BUTTON>
				</DIV>
			</DIV>
				<BR>';
		}
		else
		{

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
			echo "<div class='welcome'>
			Welcome, <a href='profile.php'> ".$_SESSION["name"];
			echo "</div></a><form method=get>";
			echo "<button class='badge1' data-badge='".$unread_count."'><a href='notifications.php'>Notifications</a></button>";

			echo '<input type="submit" value="Log Out" class="login" name="logout">
			</DIV>
			</form>	
			</DIV>';	


			//INSERT LOGOUT BUTTON HERE WITH FORM (NO ACTION AND METHOD=GET AND NAME=logout)
			//^^MAKE IT PRETTY
			/*
				if($_GET["logout"])
				{
					session_destroy();
				}
			*/
		}
		if(isset($_GET["logout"]))
		{
			session_destroy();
			header("Location: home3.php");
		}
	?>
			
			<?php

			//require 'vendor/autoload.php';
			//session_start();
			$client = new MongoClient;
			$db=$client->airbnb;
			$collection=$db->host;

			//print_r($_SESSION);
			//$email=$_GET['email'];
			

			if($_GET["listing_id"])
			{
				$doclist=$collection->findOne(
					array(
						"listings.listing_id"=> new MongoId($_GET['listing_id'])
					)
				);



				$_SESSION["host_email"]=$doclist["email"];
				$_SESSION["phno"]=$doclist["phno"];
				$tenant=$_SESSION["email"];
				if(!isset($_SESSION['listing_id']))
					$_SESSION['listing_id']=$_GET['listing_id'];
				//echo $_SESSION["host_email"].$tenant;

				
				//add html and php echo here

				foreach ($doclist["listings"] as $listing)
				{
					# code...
					if($listing["listing_id"]==$_SESSION["listing_id"])
					{
						echo "<div class='img123'><img src='fetchimage.php?listing_id=".$_SESSION['listing_id']."' height=60% width=75% alt='prop image'  id='img1' ></div>";
						
						echo "<div class='detailss'>";
						echo "<p id='about'>About this listing:</p>";
						echo "<p id='field'>Host Name:</p><p id='data'> ".$doclist["fname"]." ".$doclist["lname"]."</p><br><br>";
						echo "<p id='field'>Number of rooms:</p><p id='data'> ".$listing["rooms"]."</p><br><br>";
						echo "<p id='field'>Rent:</p><p id='data'> &#8377; ".$listing["rent"]."</p><br><br>";
						echo "<p id='field'>Address:</p><p id='data'> ".$listing["address"]."</p><br><br>";
						echo "<p id='field'>Area:</p><p id='data'> ".$listing["area"]."</p><br><br>";
					}
				//echo "<p id='field'>City:</p><p id='data'> ".$doclist["listings"][0]["city"]."</p><br><br>";
				}

				// /var_dump($doclist);
			}
			?>
		
			<FORM method=get action="listing_default.php">
			<p id='field'>Do you want to request for these dates?</p>
			<?php echo '
			<input type="date" class="fromdate" name="date_from" value="'.$_GET["fromdate"].'">
			<input type="date" class="todate" name="date_to" value="'.$_GET["todate"].'">'
			?>
			<br>
			<br>
			<br>
			<INPUT TYPE="submit" value="Interested?" name="upload_ad" class="upload_ad_btn" >
			<br>
			<br>
			
			<a href="property_listing4.php"><INPUT TYPE="button" value="Go back" name="back" class="upload_ad_btn" ></a>	
			</FORM>
			</div>

			<?php

			if($_GET["upload_ad"])
			{
				if(isset($_SESSION["email"]))
				{
					$result=$collection->update(
						array(
							'listings.listing_id'=>new MongoId($_SESSION['listing_id'])
							),
						array(
								'$push'=>array(

										"listings.$.interested"=>array(

											"email" => $_SESSION['email'],
											'from_date' => new MongoDate(strtotime($_GET["date_from"]." 00:00:00")),
											'to_date' => new MongoDate(strtotime($_GET["date_to"]." 00:00:00")),


											),
									)
							)
						);
					//var_dump($result);
					//echo '<script>alert('.$_SESSION["host_email"].');</script>';

					$nresult=$collection->update(
						array('email'=>$_SESSION["host_email"]),
						array(
								'$push'=>array(

									"notifications"=>array(
											"date"=> new MongoDate(),
											"read"=> 0,
											"text"=> "You have a new interested user for your listings.",
											"type"=> "interested"
											)
									)
							)
						);
					//var_dump($nresult);
					echo '<script>alert("Host has been notified of your request. Please get in touch with the host. Contact number of the host: '.$_SESSION["phno"].'");</script>';
					echo "<script type='text/javascript'>window.top.location='home3.php';</script>";
				}
				else
				{
					echo "<script>alert('You need to be logged in to request booking for a listing.');</script>";
					echo "<script type='text/javascript'>window.top.location='login2.php';</script>";	
				}
				//var_dump($result);
			}

			?>
			<!--</div>
		</div>-->	
	</BODY>
</HTML>	