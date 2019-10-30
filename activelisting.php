<HTML>
	<HEAD>
		<TITLE>Active listings</TITLE>
		<LINK rel="stylesheet" type="text/css" href="activelisting.css">

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

			//date_default_timezone_set('UTC');
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
			?>



			<br>
			<H1 class="details_of">Active Listings :</H1>	
			
			<?php

				session_start();
				$client = new MongoClient;
				$db=$client->airbnb;
				$collection=$db->host;

				$email=$_SESSION["email"];

				$doc=$collection->findOne(
					array(
						"email"=> $email
						)
				);
				//var_dump($doc);
			?>


		
			<?php

				$i=1;

				foreach ($doc["listings"] as $listing) {

					echo '



				
					<TABLE id="prop_det" align=center border="2">

					<form method="get">

					<TR >
					<TD  colspan=3><img src="fetchimage.php?listing_id='.(string)$listing['listing_id'].'"  style="width:50%;height:40%; margin-left:25%; opacity:0.9; position:relative;"></TD>
					</TR>
					<TR>
					<TD colspan=2> <div class="left_t"><H4 class="details">Number of rooms:</H4><input type="text" id="no_of_rooms'.$i.'" name="no_of_rooms" value="'.$listing["rooms"].'" readonly> </div></td><td>
					<div class="righ_t">
					<H4 class="details" >Rent per day:</H4><input type="text" id="rent'.$i.'" name="rent" value="'.$listing["rent"].'" readonly>
					</div>
					</TD>
					</TR>
					<TR>
					<TD>
					<H4 class="details">Address:</H4><input type="text" id="address'.$i.'" class="address" name="address" value="'.$listing["address"].'" readonly>
					</TD>
					<TD>
					<H4 class="details">Area:</H4><input type="text" id="area'.$i.'" name="area" value="'.$listing["area"].'" readonly>
					</TD>
					
					<TD>
					<H4 class="details">City</H4><input type="text" id="city'.$i.'" name="city" value="'.$listing["city"].'" readonly>
					</TD>
					<tr>
					<TD colspan=2 >
					<H4 class="details">Available from :</H4><input type="date" id="available_from'.$i.'" name="from_date" value="'.date('Y-m-d',$listing["from_date"]->sec).'" readonly>
					</TD>
					
					
					<TD>
					<H4 class="details">Available till :</H4><input type="date" id="available_till'.$i.'" name="to_date" value="'.date('Y-m-d',$listing["to_date"]->sec).'" readonly>
					</TD>
					</tr>
					<TD>';
					if(empty($listing["booked"]))
					{
						echo '
						<INPUT TYPE="button" value="Edit " name="Edit" class="upload_ad_btn_edit" onclick="myFunction(' . $i . ')">
						</TD>
					
						<br><br><br>
						<td>
						<INPUT TYPE="submit" value="Remove" name="remove" class="upload_ad_btn_edit">
						</td>
				
						
						
						<BR>
						<BR>
						<BR>
						<BR>
						<BR>

						';

						echo
						'
						<input type="hidden" name="listing_id" value="'.$listing["listing_id"].'">
						<td>
						<INPUT TYPE="submit" name="save" value="Save " name="upload_ad" class="upload_ad_btn" onclick="return checkEdit()">
						</td>
						</TABLE>
						<br><br>';
					}
					else
					{
						;
						# code...
					}

				echo '			<H4 class="details" style="margin-left:11%"><a href="interested.php?listing_id='.(string)$listing["listing_id"].'">Click here to see interested users</a></h4>';
				echo '			<H4 class="details" style="margin-left:11%"><a href="bookedusers.php?listing_id='.(string)$listing["listing_id"].'">Click here to see booked users</a></h4>';
				}

			?>

			<br>
		<script>

			function checkEdit()
			{
				if(!clicked)
				{
					alert("Button not clicked");
					return false;
				}
			}


			function myFunction(x) {
			//alert("no_of_rooms"+x);
			var clicked=true;
			document.getElementById("no_of_rooms"+x).readOnly = false;
			document.getElementById("rent"+x).readOnly = false;
			//document.getElementById("address"+x).readOnly = false;
			//document.getElementById("area"+x).readOnly = false;
			//document.getElementById("city"+x).readOnly = false;
			document.getElementById("available_from"+x).readOnly = false;
			document.getElementById("available_till"+x).readOnly = false;
			}
			
</script>
		
		<?php

			if(isset($_GET["save"]))
			{
				//print_r($_GET);
				//echo $_GET["from_date"]." 00:00:00";

				//echo $_GET['listing_id'];
				$result=$collection->update(
					array(
						'listings.listing_id'=>new MongoId($_GET['listing_id'])
						),
					array(
							'$set'=>array(
									"listings.$.rooms"=>$_GET["no_of_rooms"],
									"listings.$.address"=>$_GET["address"],
									"listings.$.area"=>$_GET["area"],
									"listings.$.from_date"=>new MongoDate(strtotime($_GET["from_date"]." 00:00:00")),
									"listings.$.to_date"=>new MongoDate(strtotime($_GET["to_date"]." 00:00:00")),
									"listings.$.rent"=>$_GET["rent"],
			

								)
						)	
					);
					//header('Location: profile.php');
					echo "<script type='text/javascript'>window.top.location='activelisting.php';</script>";
			}

			if(isset($_GET["remove"]))
			{
				$result=$collection->update(
					array(
						'listings.listing_id'=>new MongoId($_GET['listing_id'])
						),
					array(
							'$pull'=>array(

								'listings'=>array(

									'listing_id' => new MongoId($_GET['listing_id'])
									)
								)
						)
					
					);
				// /var_dump($result);
				//var_dump(new MongoId($_GET['listing_id']));	
				//print_r($_GET);
				echo "<script type='text/javascript'>window.top.location='activelisting.php';</script>";
			}

			
			//echo "<script type='text/javascript'>window.top.location='activelisting.php';</script>";

		?>




	</BODY>
</HTML>