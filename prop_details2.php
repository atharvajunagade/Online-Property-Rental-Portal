<HTML>
	<HEAD>
		<TITLE>Property details</TITLE>
		<LINK rel="stylesheet" type="text/css" href="prop_details.css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
		<style>
			span
			{
		        background-color: rgba(white, .15);
		        border: 2px solid rgba(0,102,255,.5);
			    color: #000;
			    display: none;
		        padding: 5px;
		        position: relative;
				width:290px;
				top:15px;
				left:40px;
			}

			span:before
			{
			    content: "";
			    border-style: solid;
			    border-width: 0 15px 15px 15px;
			    border-color:  transparent transparent rgba(0,102,255,.5) transparent;
			    height: 0;
			    position: absolute;
			    top: -17px;
			    left: 10px;
			    width: 0;
			}

			input {
			    display: block
			}

			input:hover + span {
			    display: inline-block;
			    margin: 1	0px;
			}

          </style>

	</HEAD>
	<BODY>
	<?php
	session_start();
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
			echo "<div class='welcome'>Welcome, <a href='profile.php'> ".$_SESSION["name"]."</a>";
			echo "<button class='badge1' data-badge='".$unread_count."'><a href='notifications.php'>Notifications</a></button>";
			//echo "</div></a><button class='badge1' data-badge='".$unread_count."'><form method=get>";
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
	
	
	<DIV class="signup_form_internal">

		<H1 style="color:black; opacity:0.6; margin-left:12%;font-family: 'Open Sans Condensed', sans-serif;'" >Tell us something about your property</H1><br><br>
		
		<FORM method=post action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
			
			<INPUT TYPE="text" style="margin-left:10%;background-image:url('rooms3.png');" id="how_many_rooms" class="how_many_rooms" name="no_of_rooms" placeholder="How many rooms?" required>
			<span>Number of rooms should be between 1 - 10</span>
			<br>
			<br>
			<textarea  name="address" id="address" style="margin-left:10%;background-image:url('rooms3.png');" class="where" placeholder="Address" required></textarea>
			<br>
			<br>
			<select class="select_city" id="select_city" onchange="selectCity()" style="margin-left:10%;background-image:url('city.png');" name="city" required>
				<option selected disabled>City </option>
				<option value="Pune">Pune</option>
				<option value="Mumbai">Mumbai</option>
				<option value="Delhi">New Delhi</option>
				<option value="Thane">Thane</option>
			</select>
			<br>
			<br>
		
			<select class="select_area" id="select_area" style="margin-left:10%;background-image:url('city.png');" name="area" required>
				<option selected disabled><H4 class="details">Locality ?</H4> </option>
			</select>
			<BR>
			<br>
	
			<H3 class="detailss" style="font-family:'Open Sans Condensed', sans-serif;">From ?<input type="date" class="fromdate" id="date_from" name="date_from" style="background-image:url('calendar.png');" required></H3>
			<H3 class="detailss" style="font-family:'Open Sans Condensed', sans-serif;">To ?</H3><input type="date" class="todate" id="date_to" name="date_to" style="background-image:url('calendar.png');" required> 
			<br>
			<INPUT TYPE="text" style="margin-left:10%;" class="how_many_rooms" id="rent" name="rent" placeholder="Rent per day" style="background-image:url('wallet.png');" required>
			<br>
			<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
			<H3 class="details">Upload an image of your property<input type="file" class="upload_btn" name="userfile">	</H3>
			<br><br><br>
			<input type="submit" value="Upload Info" name="info_upload" class="submit_btn" style="width:200px" onclick="return(myFunction())">
			
			</FORM>
		
	</DIV>
	
	
	
	
	
	
	
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


function myFunction() {


   var city1=document.getElementById("select_city").value;
   var area1 = document.getElementById("select_area").value;
	
	

   var x, text;
   x = document.getElementById("how_many_rooms").value;
   var v = document.getElementById("rent").value;
   var z =  document.getElementById("address").value;
    


    var startdate = document.getElementById("date_from").value;
    var endDt = document.getElementById("date_to").value;
	
     
    var date_today=new Date(); //gets the system date
    var formated_date = formatDate(date_today);//Calling formatDate Function

    var input_date=startdate;
    var input_date2=endDt;

    var currentDateTime = new Date(Date.parse(formated_date));
    var inputDateTime   = new Date(Date.parse(input_date));
    var inputDateTime2   = new Date(Date.parse(input_date2));

       if ((inputDateTime >= inputDateTime2)  ||  (inputDateTime <= currentDateTime)  || ((inputDateTime2== null) || (inputDateTime== null))) 
		{

        alert("Input date is invalid");
		return false;

        }
	
	else if(city1=="City")
	{
	alert("Select a City !");
	return false;
	}
 
	
	else if ( z.length < 1 || z.length > 30)
	 {

      	alert("Address Is Invalid");
		return false;

    	 }
   
       

    
    else if (isNaN(x) || x < 1 || x > 10)
	 	{

      	alert("Number of rooms are not valid");
	return false;

    	 }

	else if (isNaN(v) || v < 200 || x > 50000)
	 {

      	alert("Rent amount is not avlid");
	return false;

    	 }
	else
	 {
	return true;
	 }
	
   
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

	</SCRIPT>
	
	
	</BODY>
</HTML>

<?php

	session_start();
	if(!isset($_SESSION["email"]))
	{
		echo "<script>alert('You are need to log in to add a property.');</script>";
		echo "<script type='text/javascript'>window.top.location='home3.php';</script>";
	}
	if(isset($_POST["info_upload"]))
	{
		$client = new MongoClient();
		$db=$client->airbnb;
		$collection = $client->airbnb->host;
		$email=$_SESSION["email"];




		if(isset($_POST["no_of_rooms"])&&isset($_POST["address"])&&isset($_POST["city"])&&isset($_POST["area"])&&isset($_POST["date_from"])&&isset($_POST["date_to"])&&isset($_POST["rent"]))
		{
			$listing_id=new MongoId();
			$insertResult=$collection->update(
				array("email" => $email),
				array(
					'$push'=>
						array(
							'listings' => array(
									'rooms' => $_POST["no_of_rooms"],
									'address' => $_POST["address"],		
									'area' => $_POST["area"],
									'city' => $_POST["city"],
									'from_date' => new MongoDate(strtotime($_POST["date_from"]." 00:00:00")),
									'to_date' => new MongoDate(strtotime($_POST["date_to"]." 00:00:00")),
									'rent' => (int)$_POST["rent"],
									'listing_id' => $listing_id,
								)
						)
				)

			);
			if(isset($_POST['info_upload']))
			{
				$uploaddir = '/home/overlord1109/uploads/';
				$_FILES['userfile']['name']="uploads.jpg";
				$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

				echo $uploadfile."<br>";

				//echo '<pre>';
				if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
				   // echo "File is valid, and was successfully uploaded.\n";
				} else {
				    //echo "Possible file upload attack!\n";
				}
				$listing_id=(string)$listing_id;
				$grid = $db->getGridFS();
				$res=$grid->storeFile("/home/overlord1109/uploads/uploads.jpg", array('listing_id'=>$listing_id));
				//var_dump($res);

				//echo 'Here is some more debugging info:';
				//print_r($_FILES);

				//print "</pre>";
				echo "<script type='text/javascript'>window.top.location='home3.php';</script>";

			}
		}
		else
		{
		
				print "Enter all the values.";
		}
	}
	//var_dump($insertResult);

?>