<HTML>
	<HEAD>
		<TITLE>LOGIN</TITLE>
		<LINK rel="stylesheet" type="text/css" href="login2.css">
		<link href="https://fonts.googleapis.com/css?family=Amatic+SC" rel="stylesheet">

		<link href="https://fonts.googleapis.com/css?family=Roboto:500" rel="stylesheet">

		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
	</HEAD>
	<BODY>





	<BR>
	<BR>
	<BR>
	<DIV class="login_details">
		<BR>
		<div class="login_text">
		<H1 style="color:black; opacity:0.6; margin-left:35%;font-family:  'Open Sans Condensed', sans-serif;">LOG IN</H1>
		</div>
		<br>
	
		
		<?php
			session_start();
			if(!$_SESSION["loggedIn"])
			{
				echo '<FORM method=post name="login2" >
					<H4 class="email_pass" align="center" ></H4><INPUT TYPE="text" name="email" class="input_box" left-margin="20%" size="35" placeholder="Username" required>
					<H4 class="email_pass" align="center" ></H4><INPUT type="password" name="password" class="input_box2" size="35" placeholder="Password" required>
					<BR>
					<BR>
				
					<INPUT TYPE="submit" name="login" value="LOGIN" id="submit_btn" onclick="return(ValidateEmail(document.login2.email,document.login2.password));"><br><br><br>
					<!---<INPUT TYPE="submit" name="admin" value="Admin" id="submit_btn" >---->
				</FORM>';

				if(isset($_POST["login"])&&isset($_POST["email"])&&isset($_POST["password"]))
				{

					if($_POST["email"]=="admin@airbnb.com"&&$_POST["password"]=="Admin123")
					{
						echo '<script type="text/javascript">location.href = "admin.php";</script>';
					}
					else
					{

						$client= new MongoClient();
						$collection = $client->airbnb->host;
						if($collection->count(array('email' =>  $_POST['email'], 'passwd' =>  $_POST['password']))==1)
						{
							$document=$collection->findOne(array('email' =>  $_POST['email'], 'passwd' =>  $_POST['password']));
							$_SESSION['name']=$document["fname"]." ".$document["lname"];
							$_SESSION['email']=$document["email"];
							$_SESSION['loggedIn']=true;
							echo '<script type="text/javascript">location.href = "home3.php";</script>';
						}
						else
						{
							//echo '<script>alert("Given email-id or password are not correct.");</script>';
							//echo '<script type="text/javascript">location.href = "login2.php";</script>';
						}
					}

				}

			}
			else
			{
				echo "You are already logged in, wait till you're redirected to the home page";
				echo '<script type="text/javascript">window.setTimeout(function() {window.location.href="home3.php";}, 3000);</script>';
			}
		?>
	</DIV>

		<script>
  





     function ValidateEmail(inputText,inputtxt)  
    {  
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
     var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/; 


 
    if((inputText.value.match(mailformat))  && (inputtxt.value.match(passw)))  
    {
    return true;  
    }  
    else  
    {  
  alert("Please fill the  fields properly.");
    return false;  
    }  
  
 }
       </script>


	</BODY>
</HTML>