<HTML>
	<HEAD>
		<TITLE>HOME</TITLE>
		<LINK rel="stylesheet" type="text/css" href="home3.css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">

  

  	
	</HEAD>
	<BODY>



		<video  id="bgvid" playsinline autoplay muted loop>
 
	<source src="venice.mp4" type="video/mp4">

	</video>

	
	<?php
		session_start();
		if(!$_SESSION["loggedIn"])
		{
			echo '<DIV class="header_home">
			
				<BUTTON value="HOST!" class="host_btn"  style="width:10%"><a href="prop_details2.php" style="font-size:75%;">Become a Host</a></BUTTON>
				<DIV class="right_of_header">
				
				<BUTTON class="login"><a href="login2.php" style="font-size:75%;">LOG IN</a></BUTTON>   
				<BUTTON class="login"><a href="sign_up3.php" style="font-size:75%;">SIGN UP</a></BUTTON>
				</DIV>
				</DIV>
				<BR>';
		}
		else
		{
			echo '<DIV class="header_home">
			<BUTTON value="HOST!" class="host_btn"  style="width:10%"><a href="prop_details2.php" style="font-size:75%;">Become a Host</a></BUTTON>
			<form method=get>
			<DIV class="right_of_header">';
			echo "<div class='details'>Welcome, <a href='profile.php'> ".$_SESSION["name"];

			$client = new MongoClient;
			$db=$client->airbnb;
			$collection=$db->host;

			$doc1=$collection->findOne(array("email"=>$_SESSION["email"]));
			$unread_count=0;
			foreach ($doc1["notifications"] as $notification)
			{
				if($notification["read"]==0)
				{
					$unread_count++;
				}
				# code...
			}
			echo "<button class='badge1' data-badge='".$unread_count."'><a href='notifications.php'>Notifications</a></button>";

			

			$doc=$collection->findOne(array("email"=>$_SESSION['email']));

			foreach ($doc as $key) {
				# code...
			}

			echo "</a></div>	<form method=get>";
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
			/*$client = new MongoClient;
			$db=$client->airbnb;
			$collection=$db->host;
			$doc=$collection(array("email"=>$_SESSION["email"]));
			foreach ($doc["stays"] as $stay)
			{
				if((($doc["stays"]["from_date"]->sec)-3600*24)>date("U"))
				{
					//Transfer

					$list=$collection->findOne(array("listings.listing_id"=>new MongoId($doc["stays"]["listing"])));
					$rent=$list["listings"][0]["rent"];
					$tot=$rent*($doc["stays"]["to_date"]->sec-$doc["stays"]["from_date"]->sec)/(3600*24);
					
					$res1=$collection->update(
						array("email" => $doc["email"]),
						array('$inc'=>array("wallet_balance"=>$tot))
					);

					$tot=$tot*(-1);
					$res2=$db->admin->update(
						array(),
						array('$inc'=>array("wallet_balance"=>$tot))
					);
				}
				# code...
			}*/


		}
		if(isset($_GET["logout"]))
		{
			session_destroy();
			header("Location: home3.php");
		}

	?>
<BR>
<BR>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
<div class="search_panel">
		<DIV class="search">
		<FORM method=get action="property_listing4.php">
		<select class="select_city" id="select_city" onchange="selectCity()"  style="width:320px" name="select_city">
			<option selected disabled >Where are you going?</option>
			<option value="Pune" >Pune</option>
			<option value="Mumbai" >Mumbai</option>	
			<option value="Delhi">New Delhi</option>
			<option value="Thane">Thane</option>
		</select>
		<select class="select_area" id="select_area" style="width:300px"  name="select_area">
			<option selected disabled>Preferred locality?</option>
		</select>
		<font class="from">From? </font><input type="date" class="fromdate" name="date_from" id="date_from" required>
		<font class="from">To? </font><input type="date" class="todate" name="date_to" id="date_to" required>
		
		
		<br><br><br>
		<DIV class="hunt_button">
		<input type="submit" name="hunt" value="HUNT" class="hunt_btn" onclick="return (compare())">
		</DIV>
		<BR>
		<BR>
		</FORM>
		</DIV>
</div>

	
		<BR><BR><BR><BR>
		
<?php
/*

	if(isset($_GET["hunt"]))
	{
		if(!isset($_GET["select_area"]) || !isset($_GET["select_city"]) || !isset($_GET["date_from"]) || !isset($_GET["date_to"]))
		{
			echo '<script> alert("Please enter all values."); 
			type="text/javascript">window.top.location="activelisting.php";
			</script>';
		}
	}*/
?>
	
	
	<SCRIPT>
		function selectCity()
		{
			var city=document.getElementById("select_city").value;
			var area = document.getElementById("select_area");
			area.options.length=0;
			if(city=="Pune")
			{
				 var areanames = ["FC Road","Aundh","Viman Nagar"];
				 for(var i=0;i<areanames.length;i++)
				 {
					var opt=areanames[i];
					var el = document.createElement("option");
					el.textContent=opt;
					el.value=opt;
					area.appendChild(el);
				 } 
			}
			if(city=="Mumbai")
			{
				 var areanames = ["Dadar","Santa Cruz","Churchgate"];
				 for(var i=0;i<areanames.length;i++)
				 {
					var opt=areanames[i];
					var el = document.createElement("option");
					el.textContent=opt;
					el.value=opt;
					area.appendChild(el);
				 } 
			}
			if(city=="Delhi")
			{
				 var areanames = ["Civil lines","Dwarka","CP"];
				 for(var i=0;i<areanames.length;i++)
				 {
					var opt=areanames[i];
					var el = document.createElement("option");
					el.textContent=opt;
					el.value=opt;
					area.appendChild(el);
				 } 
			}
			if(city=="Thane")
			{
				 var areanames = ["Mira road","Ghatkopar","DEF"];
				 for(var i=0;i<areanames.length;i++)
				 {
					var opt=areanames[i];
					var el = document.createElement("option");
					el.textContent=opt;
					el.value=opt;
					area.appendChild(el);
				 } 
			}
		}

	function compare()
	{

    var startdate = document.getElementById("date_from").value;
    var endDt = document.getElementById("date_to").value;
	var city=document.getElementById("select_city").value;
	var area = document.getElementById("select_area");
     
    var date_today=new Date(); //gets the system date
    var formated_date = formatDate(date_today);//Calling formatDate Function

    var input_date=startdate;
    var input_date2=endDt;

    var currentDateTime = new Date(Date.parse(formated_date));
    var inputDateTime   = new Date(Date.parse(input_date));
    var inputDateTime2   = new Date(Date.parse(input_date2));

    if ((inputDateTime <= currentDateTime)  || (inputDateTime2<inputDateTime))
    {

        alert("Please enter valid dates.");
		return false;

    }
    if(city=="Where are you going?" || area=="Preferred locality?" || inputDateTime2==null || inputDateTime==null)
    {
    	alert("Please enter all values.");
    	return false;
    }
function formatDate(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        hours   = hours < 10 ? '0'+hours : hours ;

        minutes = minutes < 10 ? '0'+minutes : minutes;

        var strTime = hours+":"+minutes+ ' ' + ampm;


return  date.getFullYear()+ "/" + ((date.getMonth()+1) < 10 ? "0"+(date.getMonth()+1) : (date.getMonth()+1) ) + "/" + (date.getDate() < 10 ? "0"+date.getDate() : date.getDate()) + " " + strTime;
            }



 
	}
	</SCRIPT>
	
	
	</BODY>
</HTML>
