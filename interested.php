<HTML>
	<HEAD>
		<TITLE>Interested Users</TITLE>
		<LINK rel="stylesheet" type="text/css" href="interested.css">

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
		if($listing['listing_id']==$_GET["listing_id"])
		{
				echo '<br><br>
					<H2 style="font-family:Open Sans Condensed, sans-serif;font-size:40">Interested users:</h2><br><br>
					';

					$j=1;
					if(!empty($listing["interested"]))
					{
						foreach ($listing["interested"] as $iuser)
						{

							//var_dump($iuser);
							$doc=$collection->findOne(array("email"=>$iuser["email"]));
							echo "Name: ".$doc["fname"]." ".$doc["lname"]."<br>";
							echo "Contact no.: ".$doc["phno"]."<br>";
							echo " From: "; 
							echo date('Y-m-d', $iuser["from_date"]->sec)."<br>";
							echo " To: ";
							echo date('Y-m-d', $iuser["to_date"]->sec);
							echo '
							<form method="get">
							<INPUT TYPE="submit" value="Accept " align="right"  name="accept" class="upload_ad_btn_intrested">
							<input type="hidden" name="listing_id" value="'.$listing["listing_id"].'">
							<input type="hidden" name="from_date" value="'.$iuser["from_date"].'">
							<input type="hidden" name="to_date" value="'.$iuser["to_date"].'">					
							<input type="hidden" name="email" value="'.$iuser["email"].'">
							<input type="hidden" name="rent" value="'.$listing["rent"].'">

							</form>
							
						';

							# code...
						}
					}
					else
					{
						echo "<p >No interested users.</p><br>";
					}
		}
	}




	if(isset($_GET["accept"]))
			{

				
				//print_r($_GET);
					$fdate=$_GET["from_date"];
					$tdate=$_GET["to_date"];
					//var_dump($fdate);
					$days=((int)substr($tdate,11)-(int)substr($fdate,11))/(3600*24);
					//echo $days." ";
					$tot_rent=((int)$_GET["rent"])*($days);

					$doc_tenant=$collection->findOne(array("email"=>$_GET["email"]));
										

				if($doc_tenant["wallet_balance"]>=$tot_rent)
				{
					$tot_rent=$tot_rent*-1;
					$res1=$collection->update(
							array("email" => $_GET["email"]),
							array('$inc'=>array("wallet_balance"=>$tot_rent))
						);
					$tot_rent=$tot_rent*-1;
					//echo $tot_rent;

					$res1=$db->admin->update(
							array(),
							array('$inc'=>array("wallet_balance"=>$tot_rent))
						);

					$result=$collection->update(
						array(
							"email" => $_GET['email'],
							),
						array(
								'$push'=>array(

										"stays"=>array(

											'listing_id'=>new MongoId($_GET['listing_id']),
											'from_date' => new MongoDate(strtotime(date('Y-m-d', substr($_GET["from_date"], 11)))),
											'to_date' => new MongoDate(strtotime(date('Y-m-d', substr($_GET["to_date"], 11)))),
											)
									)
							)	
						);




					
					//NEW COMMENT
					$nresult=$collection->update(
						array(
								'listings.listing_id'=>new MongoId($_GET['listing_id']),
							),
						array(
							'$push'=>array(
								"listings.$.booked"=>array(
										'from_date' => new MongoDate(strtotime(date('Y-m-d', substr($_GET["from_date"], 11)))),
										'to_date' => new MongoDate(strtotime(date('Y-m-d', substr($_GET["to_date"], 11)))),
										'email'	=>	$_GET['email'],
									)
								)
							)
						);
					//var_dump($nresult);
					$n1result=$collection->update(
							array('email'=>$_GET["email"]),
							array(
									'$push'=>array(

										"notifications"=>array(
												"date"=> new MongoDate(),
												"read"=> 0,
												"text"=> "Your requested booking has been approved.",
												"type"=> "accept"
												)
										)
								)
							);



					$collection->update(
						array(
								'email'=>$_SESSION['email'],
								'listings.listing_id'=>new MongoId($_GET['listing_id']),
							),	
						array(
							'$pull'=>array(
								"listings.$.interested"=>array(
									'email'=>$_GET['email'],
									)
								)
							)
						);
					
					$document=$collection->findOne(array('listings.listing_id'=>new MongoId($_GET['listing_id'])));
					$from=(int)substr($_GET["from_date"], 11);
					$to=(int)substr($_GET["to_date"], 11);
					
					foreach ($document["listings"] as $listing)
					{
						if($listing["listing_id"]==$_GET['listing_id'])
						{
							foreach ($listing["interested"] as $interested)
							{
								if($interested["email"]!=$_GET['email'])
								{
								//var_dump($from);
								//var_dump($to);
								//var_dump($interested["from_date"]->sec);
								//var_dump($interested["to_date"]->sec);

								if(($interested["from_date"]->sec>=$from&&$interested["from_date"]->sec<=$to)||($interested["to_date"]->sec>=$from&&$interested["to_date"]->sec<=$to)||($interested["from_date"]->sec<=$from&&$interested["to_date"]->sec>=$to))
								{
									//echo 'clash';
									$collection->update(
										array(
												'email'=>$_SESSION['email'],
												'listings.listing_id'=>new MongoId($_GET['listing_id']),
											),	
										array(
											'$pull'=>array(
												"listings.$.interested"=>array(
													'email'=>$interested["email"],
													)
											)
										)
									);

									$n2result=$collection->update(
										array('email'=>$interested["email"]),
											array(
											'$push'=>array(

												"notifications"=>array(
														"date"=> new MongoDate(),
														"read"=> 0,
														"text"=> "Your requested booking has been declined over another clashing booking request. Please look for another listing.",
														"type"=> "decline"
														)
													)
												)
									);

									//return true;
								}
							}
							}
						}
					}
				}
				else
				{
					$collection->update(
						array(
								'email'=>$_SESSION['email'],
								'listings.listing_id'=>new MongoId($_GET['listing_id']),
							),	
						array(
							'$pull'=>array(
								"listings.$.interested"=>array(
									'email'=>$_GET['email'],
									)
								)
							)
						);

					$n2result=$collection->update(
											array('email'=>$_GET['email']),
											array(
											'$push'=>array(

												"notifications"=>array(
														"date"=> new MongoDate(),
														"read"=> 0,
														"text"=> "Your requested booking has been declined because you didn't have enough balance in your wallet.",
														"type"=> "decline_balance"
														)
													)
												)
									);

				}
				//$_GET["email"];
				echo '<script>alert("You have accepted booking request. More info can be found by clicking booked users link on active listings page.");</script>';
				echo header('Location:activelistings.php');
			}

?>