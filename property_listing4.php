<HTML>
	<HEAD>
		<TITLE>Property details</TITLE>

		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">

		 <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


  <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
</head>


		<LINK rel="stylesheet" type="text/css" href="property_listing3.css">
		<style>
		.tab1{
			background-color: #eee;
			width:75%;
			align:center;
			border:2;
			border-color: black;
			border:width;

		}
		#img123{
			height: 250;
			background-color: #fff;
		}
		#data{
		font-size: 20;
		font-style: sans-serif;
		opacity: 0.8;
		margin-left: 30%;
		position: relative;
		}

.hunt_button
{
position:absolute;
margin-left:750px;
}
.propimg{
	position: relative;
}

#data2{
	position: absolute;
	top:20%;
	left: 30%;
	color:white;
	font-size: 30;
	font-style: sans-serif;
	opacity: 0.7;
	background-color: #393433;

}

.getting_email{
	display: none;
}








		</style>
	</HEAD>
	<BODY>
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
			echo "<div class='details'>Welcome, <a href='profile.php'> ".$_SESSION["name"];


			echo "</div></a>";
			echo "<button class='badge1' data-badge='".$unread_count."'><a href='notifications.php'>Notifications</a></button>";
			echo "<form method=get>";


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


	<br>
	<br>
	<br>
	<br>
	<DIV class="filters"  >
	<div class="container-fluid">
		<button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#demo">Filters</button>


		<div id="demo" class="collapse" style="background-color: #FAFAFA; background-size:70%">

		<br>
	<FORM method=get action=property_listing4.php>
						<select class="select_city" id="select_city" onchange="selectCity()"  style="width:20%" name="select_city">
							<option selected disabled >City</option>
							<option value="Pune" >Pune</option>
							<option value="Mumbai" >Mumbai</option>
							<option value="Delhi">New Delhi</option>
							<option value="Thane">Thane</option>
						</select>
						<select class="select_area" id="select_area" style="width:20%" name="select_area">
							<option selected disabled>Area</option>
						</select>
				
	<br>
	<br>
	<br>
	
		<font class="from"> </font><input type="date" class="fromdate" name="date_from" id="date_from" required>
		<font class="from"><span>&#8594;</span> </font><input type="date" class="todate" name="date_to" id="date_to" required>
		<br>
		<br>


		<p>
  <label for="amount">Price range:</label>
  <input type="text" id="amount" name= "slider" readonly style="border:0; color:#f6931f; font-weight:bold;">
</p>
 
<div id="slider-range"></div>
 



		<!---<font class="from">Rent from </font><input type="text" class="fromdate" name="rent_from" style="width:10%">
		<font class="from"> Rent to</font><input type="text" class="todate" name="rent_to" style="width:10%">!-->

	<!---	<input type="range" min="500" max="50000" >   -->
		<hr >
			<select class="select_age" id="sort" style="width:20%" name="sort">
				<option selected disabled >Sort</option>
				<option value="rent_asc">Rent: Low to High</option>
				<option value="rent_desc">Rent: High to Low</option>
			</select>
			<br>

			<br>
			<select class="select_age" id="rooms" style="width:20%" name="number_of_rooms">
				<option selected disabled >Number of rooms</option>
				<option value="1">1</option>
				<option value="2" >2</option>
				<option value="3">3</option>
				<option value="4" >4</option>
				<option value="5">5</option>
				<option value="6" >6</option>
				<option value="7">7</option>
				<option value="8" >8</option>
				<option value="9">9</option>
				<option value="10" >10</option>
			</select>
			<br>
			<br>
		<input type="submit" name="filter_btn" value="APPLY FILTERS" class="apply_filters" onclick="return (compare())">
	</FORM>	
	</div>
	</div>

	<div id="dummy">
		<!--WRITE PHP ECHO HERE-->

		<?php
			
			//require 'vendor/autoload.php';
			//date_default_timezone_set('UTC');

			function isBooked($listing, $from, $to)
			{
				$retval=false;
				$to=(int)date("U",strtotime($to));
				$from=(int)date("U",strtotime($from));
				foreach ($listing["booked"] as $booking)
				{
					if(($from>=$booking["from_date"]->sec&&$from<=$booking["to_date"]->sec)||($to>=$booking["from_date"]->sec&&$to<=$booking["to_date"]->sec)||($from<=$booking["from_date"]&&$to>=$booking["to_date"]))
					{
						//$retval=true;
						return true;
					}
/*
					var_dump($retval);
					
					var_dump(date('Y-m-d', $booking["from_date"]->sec));
					var_dump(date('Y-m-d', $booking["to_date"]->sec));
					var_dump(date('Y-m-d', $from));
					var_dump(date('	Y-m-d', $to));
					
					var_dump($booking["from_date"]->sec);
					var_dump($booking["to_date"]->sec);
					var_dump($from);
					var_dump($to);
*/
					# code...
				}
			}

			$client = new MongoClient;
			$db=$client->airbnb;
			$collection=$db->host;	
			echo "<TABLE class='tab1'>";
			if(isset($_GET["filter_btn"]))
			{
				if($_GET["sort"]=="rent_asc")
				{
					$sort_query='listings.rent';
					$sort_order=1;
				}
				else if($_GET["sort"]=="rent_desc")
				{
					$sort_query='listings.rent';
					$sort_order=-1;
				}

				$city=$_GET["select_city"];
				$matches=array();
				preg_match("/Rs.([0-9]+) - Rs.([0-9]+)/" , $_GET["slider"], $matches);
				$rentl=$matches[1];
				$renth=$matches[2];
				//var_dump($rentl);
				//var_dump($renth);
				//$query='{"listings.city":"'.$city.'"}';
				//echo $query;
				//var_dump($_GET);
				//preg_match('Rs.(.*) - Rs.(.*)', $_GET["slider"], $output);
				//var_dump($output);
				$doclist=$collection->find(
					array(
							"listings.city"	=> $_GET["select_city"],
							"listings.area" => $_GET["select_area"],
							'listings.from_date' => array('$lte' => new MongoDate(strtotime($_GET["date_from"]." 00:00:00"))),
							'listings.to_date' => array('$gte' => new MongoDate(strtotime($_GET["date_to"]." 00:00:00"))),
							'listings.rent' => array('$gte' => (int)$rentl),
							'listings.rent' => array('$lte' => (int)$renth),
							"listings.rooms" => $_GET["number_of_rooms"],

						)
					)->sort(array($sort_query=>$sort_order));
				//var_dump(iterator_to_array($doclist));
				foreach ($doclist as $doc)
				{
					if($doc["email"]!=$_SESSION["email"])
					{
					//var_dump($doc);
						foreach ($doc['listings'] as $listing)
						{
							if(($listing["rooms"]==$_GET["number_of_rooms"])&&($listing["area"]==$_GET["select_area"])&&($listing["city"]==$_GET["select_city"])&&!isBooked($listing, $_GET["date_from"], $_GET["date_to"]))
							{
							

								echo "<form method=get action='listing_default.php'>";
								echo "<TR>";
								$listing_id=(string)$listing['listing_id'];
								//var_dump($res);
								echo '<TD>';
								echo "<div class='propimg'>";
								
								echo "<img src='fetchimage.php?listing_id=".$listing_id."' width=300 height=300   style='margin-left:30%; opacity:0.9; position:relative;'>";
								echo "<p id='data2'>&#8377;".$listing["rent"]."</p>";
								echo "</div>";
								echo '</TD>';
								echo "</TR>";
								echo "<TR>";
							
								echo "<TD><p id='data'>".$doc["fname"]." ".$doc["lname"]."</p></TD>";
								
								echo "</TR>";
								//echo "<TR>";
								//echo "<TD><p id='data'>Rs.".$doc["listings"][0]["rent"]."per day.</p></TD>";
								//echo "</TR>";
								echo "<TR>";
								echo "<TD><p id='data'>".$listing["address"]."	</p></TD>";
								echo "</TR>";
								echo "<INPUT TYPE='hidden' name='listing_id' value=".$listing['listing_id'].">";
								echo "<INPUT TYPE='hidden' name='fromdate' value=".$_GET['date_from'].">";
								echo "<INPUT TYPE='hidden' name='todate' value=".$_GET['date_to'].">";
								echo "<TR><TD style='padding-bottom:7%'><a href='listing_default.php'><INPUT TYPE='submit' id='hunt_btn' name='hunt' value='Take me there' class='hunt_btn'></a></TD></TR>";
								echo "</form>";

							}

						}
					}
						//var_dump($doc);
						//print_r($doc);
					# code...
				}
				echo "</TABLE>";
			}
			else
			{
				
				$city=$_GET["select_city"];
				$area=
				//$query='{"listings.city":"'.$city.'"}';
				//echo $query;
				$doclist=$collection->find(
					array(
							"listings.city"	=> $_GET["select_city"],
							"listings.area" => $_GET["select_area"],
							'listings.from_date' => array('$lte' => new MongoDate(strtotime($_GET["date_from"]." 00:00:00"))),
							'listings.to_date' => array('$gte' => new MongoDate(strtotime($_GET["date_to"]." 00:00:00"))),

						)
					);
				
				foreach ($doclist as $doc) {
					if($doc["email"]!=$_SESSION["email"])
					{
						foreach ($doc['listings'] as $listing)
						{
							if(($listing["area"]==$_GET["select_area"])&&($listing["city"]==$_GET["select_city"])&&!isBooked($listing, $_GET["date_from"], $_GET["date_to"]))
							{
						
								echo "<form method=get action='listing_default.php'>";
								echo "<TR>";
								$listing_id=(string)$listing['listing_id'];
								//var_dump($res);
								echo '<TD>';
								echo "<div class='propimg'>";
								
								echo "<img src='fetchimage.php?listing_id=".$listing_id."'  width=300 height=300     style='margin-left:30%; opacity:0.9; position:relative;'>";
								echo "<p id='data2'>&#8377;".$listing["rent"]."</p>";
								echo "</div>";
								echo '</TD>';
								echo "</TR>";
								echo "<TR>";
							
								echo "<TD><p id='data'>".$doc["fname"]." ".$doc["lname"]."</p></TD>";
								
								echo "</TR>";
								//echo "<TR>";
								//echo "<TD><p id='data'>Rs.".$doc["listings"][0]["rent"]."per day.</p></TD>";
								//echo "</TR>";
								echo "<TR>";
								echo "<TD><p id='data'>".$listing["address"]."</p></TD>";
								echo "</TR>";
								echo "<INPUT TYPE='hidden' name='listing_id' value=".$listing['listing_id'].">";
								echo "<INPUT TYPE='hidden' name='fromdate' value=".$_GET['date_from'].">";
								echo "<INPUT TYPE='hidden' name='todate' value=".$_GET['date_to'].">";
								echo "<TR><TD style='padding-bottom:7%'><a href='listing_default.php'><INPUT TYPE='submit' id='hunt_btn' name='hunt' value='Take me there' class='hunt_btn'></a></TD></TR>";
								echo "</form>";
							}
							
						}
					}
						//var_dump($doc);
						//print_r($doc);
					# code...
				}
				echo "</TABLE>";
			}			


		?>
	</div>
	
	
	</DIV>

	<hr>
	<br>
	
	<script>

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
	var sort=document.getElementById("sort").value;
	var rooms=document.getElementById("rooms").value;
// 	alert(sort);
     
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
    if(city=="City" || area=="Area" || inputDateTime2==null || inputDateTime==null || rooms=="Number of rooms" || sort=="Sort")
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


	 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script>
  $( function() {
    $( "#slider-range" ).slider({
      range: true,
      min: 200,
      max: 40000,
      values: [ 300, 10000 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "Rs." + ui.values[ 0 ] + " - Rs." + ui.values[ 1 ] );
      }
    });
    $( "#amount" ).val( "Rs." + $( "#slider-range" ).slider( "values", 0 ) +
      " - Rs." + $( "#slider-range" ).slider( "values", 1 ) );
  } );
  </script>


	</BODY>
</HTML>