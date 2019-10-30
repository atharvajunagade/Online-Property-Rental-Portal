<HTML>
	<HEAD>
		<TITLE>SIGN UP</TITLE>
		<LINK rel="stylesheet" type="text/css" href="sign_up3.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">


	</HEAD>
	<BODY>

		
		


	<BR>
	<BR>
	<DIV class="sign_up_form">
		<BR>
		<H1 style="color:black; opacity:0.6; margin-left:12%;font-family: 'Open Sans Condensed', sans-serif;'" >SIGN UP</H1>
		<br>
		<FORM method=post name="signup3" id="regform" action="sign_up3.php">
			<INPUT TYPE="text"  class="input_box" style="margin-left:12%;" name="fname" placeholder="First name" required><br>
			<INPUT type="text"  class="input_box"   style="margin-left:12%;" name="lname" placeholder="Last name"><br>
			<INPUT TYPE="text" class="input_box" style="margin-left:12%;background-image: url('phone 2.png');" name="phno" placeholder="Contact No."><br>
			<INPUT type="text" name="username" class="input_box"      style="margin-left:12%; background-image: url('email.png');" name="username" placeholder="Username"><br>

			<INPUT TYPE="password" name="password" class="input_box" style="margin-left:12%; background-image: url('password.png');"  placeholder="Password"><br>
			<span style="font-family:arial;">Password must be have at least 6 characters.It  must be alphanumeric containing at least 1 Capital letter.</span>
			<INPUT type="password" name="password2" class="input_box" style="margin-left:12%; background-image: url('password.png');" name="rpassw" placeholder="Retype Password">

			<BR>
			
			<p style="font-size:14px; font-family:'Ubuntu Condensed',sans-serif;;margin-left:5%;">By signing up, I agree to the Terms of Service, Payments Terms of Service, Privacy Policy, Guest Refund Policy, and Host Guarantee Terms.</p>
			<INPUT TYPE="submit" name="login" value="SIGN UP" id="submit_btn" name="signup_submit" onclick="myFunction(document.signup3.password,document.signup3.email,document.signup3.phno,document.signup3.fname,document.signup3.lname)">
		</FORM>
	</DIV>


	<SCRIPT>
	function myFunction(inputtxt,inputText,inputphone,inputfname,inputlname) {
        var pass1 = document.getElementById("password").value;
        var pass2 = document.getElementById("password2").value;
	var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/; 
	var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/; 
	var phoneno = /^[789]\d{9}$/;

	var name = /[a-zA-Z]+/;


    
  






        if ( (pass1 != pass2) || !(inputtxt.value.match(passw)) || !(inputText.value.match(mailformat))   ||  !(inputphone.value.match(phoneno))  || !(inputfname.value.match(name)) || !(inputlname.value.match(name)) ) {
            alert("something wrong");
            document.getElementById("password").style.borderColor = "#E34234";
            document.getElementById("password2").style.borderColor = "#E34234";
        }

        else {
	    
            alert("Everything is okay!!!");
            document.getElementById("regForm").submit();
        }
    }
	</SCRIPT>


	</BODY>
</HTML>



<?php
	if(isset($_POST["login"]))
	{
		$client= new MongoClient();
		$collection = $client->airbnb->host;

		if(isset($_POST["fname"])&&isset($_POST["lname"])&&isset($_POST["phno"])&&isset($_POST["username"])&&isset($_POST["password"]))
		{
			if($collection->count(array('email' =>  $_POST['username']))==0)
			{

				$insertResult=$collection->insert(
					array(
						'fname' => $_POST["fname"],
						'lname' => $_POST["lname"],
						'phno' => $_POST["phno"],		
						'email' => $_POST["username"],
						'passwd' => $_POST["password"],
						'wallet_balance' => (int)'0'
					)

				);
				header("Location: login2.php");
			}
			else
			{
				echo "<script>alert('Email-id already registered. Use another email-id.');</script>";
			}
		}
		else
		{
		
				echo '<script>alert("Enter all the values.");</script>';
		}
	}
	//var_dump($insertResult);

?>