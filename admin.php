<?php

	$client = new MongoClient;
	$db=$client->airbnb;
	$collection=$db->host;
	$res=$collection->aggregate(
						array(
							array(
								'$unwind'=>'$listings'
								),
							array(
								'$group'=>array(
									'_id'=>'$listings.city',
									'avg_rent'=>array(
										'$avg'=>'$listings.rent'
										)
									)
								)
							)
						);
	//var_dump($res);

	$city=array();
	$rent=array();
	for ($i=0; $i<4; $i++) {

	$city[$i]=$res["result"][$i]["_id"];
	$rent[$i]=$res["result"][$i]["avg_rent"];


	}

	$res1=$collection->aggregate(
						array(
							array(
								'$unwind'=>'$listings'
								),
							array(
								'$group'=>array(
									'_id'=>'$listings.city',
									'count'=>array(
										'$sum'=>1
										)
									)
								)
							)
						);
	//var_dump($res1);
	//$city=array();
	$count=array();
	for ($i=0; $i<4; $i++) {

	//$city[$i]=$res["result"][$i]["_id"];
	$count[$i]=$res1["result"][$i]["count"];


	}
	//var_dump($count);
		# code...
	
	//var_dump($city);

?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Draw the pie chart for Sarah's pizza when Charts is loaded.
      google.charts.setOnLoadCallback(drawSarahChart);

      // Draw the pie chart for the Anthony's pizza when Charts is loaded.
      google.charts.setOnLoadCallback(drawAnthonyChart);

      // Callback that draws the pie chart for Sarah's pizza.
      function drawSarahChart() {

      	var rent0=<?php echo $rent[0] ?>;
      	rent0=parseInt(rent0);
      	var rent1=<?php echo $rent[1] ?>;
      	rent1=parseInt(rent1);
      	var rent2=<?php echo $rent[2] ?>;
      	rent2=parseInt(rent2);
      	var rent3=<?php echo $rent[3] ?>;
      	rent3=parseInt(rent3);

      	

      	//city0=<?php echo $city[0] ?>;
        // Create the data table for Sarah's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'City');
        data.addColumn('number', 'Average rent in Rs.');
        /*data.addRows([
          [<?php echo $city[0] ?>, <?php echo $rent[0] ?>],
          [<?php echo $city[1] ?>, <?php echo $rent[1] ?>],
          [<?php echo $city[2] ?>, <?php echo $rent[2] ?>],
          [<?php echo $city[3] ?>, <?php echo $rent[3] ?>]
        ]);*/
        data.addRows([
          ['Delhi', rent0],
          ['Thane', rent1],
          ['Mumbai', rent2],
          ['Pune', rent3]
        ]);

        // Set options for Sarah's pie chart.
        var options = {title:'City-wise Average Rent',
                       width:1000,
                       height:1000};

        // Instantiate and draw the chart for Sarah's pizza.
        var chart = new google.visualization.BarChart(document.getElementById('Sarah_chart_div'));
        chart.draw(data, options);
      }

      // Callback that draws the pie chart for Anthony's pizza.
      function drawAnthonyChart() {

		var count0=<?php echo $count[0] ?>;
      	count0=parseInt(count0);
      	var count1=<?php echo $count[1] ?>;
      	count1=parseInt(count1);
      	var count2=<?php echo $count[2] ?>;
      	count2=parseInt(count2);
      	var count3=<?php echo $count[3] ?>;
      	count3=parseInt(count3);

        // Create the data table for Anthony's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'City');
        data.addColumn('number', 'Number of listings');
        data.addRows([
          ['Delhi', count0],
          ['Thane', count1],
          ['Mumbai', count2],
          ['Pune', count3]
        ]);

        // Set options for Anthony's pie chart.
        var options = {title:'City-wise number of listings',
                       width:1000,
                      height:1000,
                      'is3D':true};

        // Instantiate and draw the chart for Anthony's pizza.
        var chart = new google.visualization.PieChart(document.getElementById('Anthony_chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>

  <h1>Site Analysis</h1>

    <!--Table and divs that hold the pie charts-->
    <table class="columns">
      <tr>
        <td><div id="Sarah_chart_div" style="border: 1px solid #ccc"></div></td>
      </tr>
      <tr>
        <td><div id="Anthony_chart_div" style="border: 1px solid #ccc"></div></td>
      </tr>
    </table>

    <h3>List of Users and their Listings:</h3>

<?php

$dbres=$collection->find();
//var_dump(iterator_to_array($dbres));

if(isset($_GET["remove"]))
{
	$collection->remove(array("email"=>$_GET["email"]));
	echo "<script type='text/javascript'>window.top.location='home3.php';</script>";

}



foreach ($dbres as $doc)
{

	echo "First Name: ".$doc["fname"]."<br>";
	echo "Last Name: ".$doc["lname"]."<br>";
	echo "Phone no.: ".$doc["phno"]."<br>";
	echo "Email: ".$doc["email"]."<br>";
	echo "Wallet Balance: Rs. ".$doc["wallet_balance"]."<br>";
	echo "Listings:<br>";
	$i=1;
	foreach ($doc["listings"] as $listing)
	{
		echo $i++.")<br>";
		echo "Number of rooms: ".$listing["rooms"]."<br>";
		echo "Address: ".$listing["address"]."<br>";
		echo "Area: ".$listing["area"]."<br>";
		echo "City: ".$listing["city"]."<br>";
		echo "Rent per day: Rs. ".$listing["rent"]."<br>";
		echo "Date From: ".date("Y-m-d", $listing["from_date"]->sec)."<br>";
		echo "Date To: ".date("Y-m-d", $listing["to_date"]->sec)."<br>";
		# code...

    if(empty($listing["booked"]))
    {
    	echo '<form method="get">

    	<input type="submit" value="Remove user" name="remove">
    	<input type="hidden" name="email" value="'.$doc["email"].'">
    	</form>

  	 ';
    }
  }

	echo "<br><br><br><br>";
	# code...
}

?>

  </body>
</html>
