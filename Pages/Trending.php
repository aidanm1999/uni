<?php

	$servername = "localhost"; $username = "root"; $password = ""; $dbname = "uni";

	try 
	{
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){ echo "Connection failed: " . $e->getMessage(); }

	$query=$conn->prepare("SELECT id,term,occurrences  FROM searches ORDER BY occurrences DESC LIMIT 5;");
	$query->execute();
	$results=$query->fetchAll(PDO::FETCH_ASSOC);
	$occurrences = array();
	$terms = array();

	foreach($results as $row){
		array_push($occurrences, $row['occurrences']);
		array_push($terms, $row['term']);
	}
	
	$conn = null;
	
?>


<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Trending - U+Ni</title>
		<link href="../Styles/siteStyle.css" rel="stylesheet" type="text/css">

		<link href="../Styles/nav.css" rel="stylesheet" type="text/css">
		<link href="../Styles/main.css" rel="stylesheet" type="text/css">
		<link href="../Styles/header.css" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="../Content/favicon.ico" type="image/x-icon">

		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	</head>

    <body>

		<script src="../Scripts/Chart.bundle.min.2.7.3.js"></script>

		<div id="searchbar" style="display:none;" >
			<form action="./Search.php" method="post" id="searchForm">
					<input type="text" id="term" name="term" placeholder="Search..." value="" />
			</form>
		</div>
		<nav id="navBar" class="folded">
			<ul>
				<li><a href="./Index.php">Home</a></li>
				<li><a href="./Trending.php">Trending</a></li>
				<li><a href="#" onclick="SearchForUniversities();return false;">Universities</a></li>
				<li><a href="#" onclick="SearchForStatistics();return false;">Statistics</a></li>
				<li><a href="#" onclick="SearchForJobs();return false;">Jobs</a></li>


				<li><a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</i></a></li>
			</ul>
		</nav>
        <main>
            <section class="col">
                <h2>Trending</h2>
                <canvas id="trendingChart"></canvas>

            </section>
		</main>
	</body>

	<script>
		function myFunction() {
			var x = document.getElementById("navBar");
			if (x.className === "folded") {
				x.className = "unfolded";
			} else {
				x.className = "folded";
			}
		}


		function SearchForUniversities(){ document.getElementById("term").value = "@Universities"; document.forms["searchForm"].submit(); }
		function SearchForJobs(){ document.getElementById("term").value = "@Jobs"; document.forms["searchForm"].submit(); }
		function SearchForStatistics(){ document.getElementById("term").value = "@Statistics"; document.forms["searchForm"].submit(); }




		var chart = document.getElementById("trendingChart");

		var ctx = chart.getContext('2d');
		console.log(chart);
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: [<?php foreach ($terms as $key => $val) { echo "\""; echo $val;  echo "\",";} ?>],
				datasets: [{
					data: [<?php foreach ($occurrences as $key => $val) { echo $val; echo ",";} ?>],
					backgroundColor: [
						'rgba(255, 99, 132, 0.8)',
						'rgba(54, 162, 235, 0.8)',
						'rgba(255, 206, 86, 0.8)',
						'rgba(75, 192, 192, 0.8)',
						'rgba(153, 102, 255, 0.2)',
						'rgba(255, 159, 64, 0.2)'
					],
					borderColor: [
						'rgba(255,99,132,1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)'
					],
					borderWidth: 1
				}]
			},
			options: {
				legend: {
        			display: false
    			},
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true,
							display:false,
						}
					}]
				}
			}
		});
	</script>

</html>