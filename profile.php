
<!DOCTYPE html>

<HTML>
	<HEAD>
		<TITLE>User Profile</TITLE>
		<LINK rel="stylesheet" type="text/css" href="profile.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto" rel="stylesheet"> 
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">		
		
	<link href="https://fonts.googleapis.com/css?family=Carme" rel="stylesheet">

	<link href="https://fonts.googleapis.com/css?family=Libre+Franklin" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Homenaje" rel="stylesheet">

		<?php
			
			session_start();
			$email=$_SESSION['email'];
			$client= new MongoClient();
			$collection = $client->airbnb->host;
			//echo $email;
			$doc=$collection->findOne(array('email' => $email));
		//	var_dump($doc);
			//var_dump($doc);
		?>


	</HEAD>
	<BODY>

	<?php
		session_start();
		if(!$_SESSION["loggedIn"])
		{
			echo '<DIV class="header_home">
			
				
				
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
			echo "<div class='welcome'>Welcome, <a href='profile.php'> ".$_SESSION["name"];
			echo "</div></a><button class='badge1' data-badge='".$unread_count."'><a href='notifications.php'>Notifications</a></button><form method=get>";
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

	<BR>
	<BR>
	<DIV class="sign_up_form">
		<BR>
		<H1 style="color:black; opacity:0.6; font-family: 'Open Sans Condensed', sans-serif;'" >Your Details</H1>
		<br>
		<FORM method="post" action="profile.php">
			<H4 class="email_pass" align="center" style="margin-left:4%;">First Name :</H4><INPUT TYPE="text" id="first_name" name="fname" class="input_box" style="margin-left:4%;" readonly value=<?php echo "'".$doc['fname']."'" ?>>
			<H4 class="email_pass" align="center" style="margin-left:4%;">Last Name :</H4><INPUT type="text" id="last_name" name="lname" class="input_box"   style="margin-left:4%;" readonly value=<?php echo "'".$doc['lname']."'" ?>>
			<H4 class="email_pass" align="center" style="margin-left:4%;">Contact no :</H4><INPUT TYPE="text" id="contact_no" name="phno" class="input_box" style="margin-left:4%;background-image: url('phone 2.png')" readonly value=<?php echo "'".$doc['phno']."'" ?>>
			<H4 class="email_pass" align="center" style="margin-left:4%;">Wallet Balance :</H4><INPUT type="text" id="wallet" name="wallet_balance" class="input_box"      style="margin-left:4%;background-image: url('wallet.png')" readonly value=<?php echo "'".$doc['wallet_balance']."'" ?>>
			<H4 class="email_pass" align="center" style="margin-left:4%;">Password :</H4><INPUT TYPE="text" id="passwd" name="passwd" class="input_box"     style="margin-left:4%;background-image: url('password.png')" readonly value=<?php echo "'".$doc['passwd']."'" ?>>
			<H4 class="email_pass" align="center" style="margin-left:4%;"><a href="activelisting.php">Active Listings</a> 
			<H4 class="email_pass" align="center" style="margin-left:4%;"><a href="bookings.php">Bookings</a> 
			<br>
			<BR>
			<BR>
			
			<INPUT TYPE="button" name="edit_details" value="EDIT DETAILS" id="submit_btn" onclick="myFunction()" ><p>  </p>
			<br>
			<INPUT TYPE="submit" name="save_changes" value="SAVE CHANGES" id="submit_btn2" alert=("Your changes have been saved.")>
				</form>
			<?php
				if(isset($_POST["save_changes"]))
				{
					$result=$collection->update(
						array("email"=>$email),
						array(
							'$set'=>
								array(
								"fname"=>$_POST["fname"],
								"lname"=>$_POST["lname"],
								"phno"=>$_POST["phno"],
								"passwd"=>$_POST["passwd"],
								)
							)
						);
					//var_dump($result);
					header('Location: profile.php');
				}
			?>

			<hr>
			<H1 style="color:black; opacity:0.6; font-family: 'Open Sans Condensed', sans-serif;'" >Wallet</H1><br><br><br>
			<FORM method="post" action="profile.php">
			
			<INPUT type="text" name="amount" class="input_box"   style="margin-left:4%;background-image: url('wallet.png')"  placeholder="Add Amount">
				
			<br><br>
			<INPUT TYPE="submit" name="add_balance" value="ADD BALANCE" id="submit_btn3">
			
			<br><br>
			
			
		</form>

		<?php

			if(isset($_POST["add_balance"]))
			{
				print_r($_POST);
				$upres=$collection->update(
						array("email" => $email),
						array('$inc'=>array("wallet_balance"=>(int)$_POST["amount"]))
					);
				//var_dump($upres);
				echo "<script type='text/javascript'>window.top.location='profile.php';</script>";
			}

		?>
	
	</DIV>
	<script>
			function myFunction() {
			document.getElementById("first_name").readOnly = false;
			document.getElementById("last_name").readOnly = false;
			document.getElementById("contact_no").readOnly = false;
			
			document.getElementById("passwd").readOnly = false;
			}
			
</script>
	</BODY>
</HTML>