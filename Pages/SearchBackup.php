<?php $servername = "localhost"; $username = "root"; $password = ""; $dbname = "uni";
try {

	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) { echo "Connection failed: " . $e->getMessage(); }

$term=$_POST['term'];

$queryArray = explode(" ", $term);

$query=$conn->prepare("INSERT INTO searches (term, occurrences) VALUES (?,'1')");

foreach ($queryArray as $key => $value) {

	$termFinder=$conn->prepare("SELECT * FROM searches WHERE term = '$value'");
	$termFinder->execute();
	$results=$termFinder->fetchAll(PDO::FETCH_ASSOC);

	//IF THE TERM DOESNT EXIST, INSERT ROW WITH OCCURRENCES SET TO 1
	//IF IT DOES, ADD 1 TO THE OCCURRENCES
	if(empty($results))
	{
		$query->bindParam(1, $value);
		$query->execute();
	}
	else
	{
		foreach ($results as $row)
		{
			$occurrences = $row['occurrences'] + 1;
			$id = $row['id'];
			$occurrenceUpdater =$conn->prepare("UPDATE searches SET occurrences = '$occurrences' WHERE id = '$id'");
			$occurrenceUpdater->execute();
		}
	}
}

//If search is equal to 'Statistics' the info is local (Database)
	//Get statistics data
$statistics=[];
if($queryArray[0]=="@Statistics" && count($queryArray) == 1)
{
	$statisticsQuery=$conn->prepare("SELECT * FROM stats");
	$statisticsQuery->execute();
	$statistics=$statisticsQuery->fetchAll(PDO::FETCH_ASSOC);
}
else if ($queryArray[0]=="@Statistics" && count($queryArray) > 1)
{
	$queryStringPrep = "SELECT * FROM stats WHERE (";
	for ($x = 1; $x <= count($queryArray)-1; $x++) { //Element in query array
		$queryStringPrep=$queryStringPrep."title like '%".$queryArray[$x]."%' or label like '%".$queryArray[$x]."%'";
		if($x>=count($queryArray)-1)
		{
			//Last item needs to close bracket
			$queryStringPrep=$queryStringPrep.");";
		}
		else
		{
			//more elements means larger sql statement = or needs to be appended
			$queryStringPrep=$queryStringPrep." or ";
		}
	}

	$statisticsQuery=$conn->prepare($queryStringPrep);
	$statisticsQuery->execute();
	$statistics=$statisticsQuery->fetchAll(PDO::FETCH_ASSOC);
}

$conn = null;

?>


<html>

	
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Home - U+Ni</title>

		<script src="../Scripts/jquery-3.3.1.min.js"></script>

		<link href="../Styles/siteStyle.css" rel="stylesheet" type="text/css">

		<link href="../Styles/nav.css" rel="stylesheet" type="text/css">
		<link href="../Styles/main.css" rel="stylesheet" type="text/css">
		<link href="../Styles/header.css" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="../Content/favicon.ico" type="image/x-icon">

		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	</head>

    <body>

		<script src="../Scripts/Chart.bundle.js"></script>

		<nav id="navBar" class="folded">
			<ul>
				<li><a href="./Index.php">Home</a></li>
				<li><a href="./Trending.php">Trending</a></li>
				<li><a href="#" onclick="SearchForUniversities();">Universities</a></li>
				<li><a href="#" onclick="SearchForStatistics();return false;">Statistics</a></li>
				<li><a href="#" onclick="SearchForJobs();return false;">Jobs</a></li>


				<li><a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</i></a></li>
			</ul>
		</nav>
		<div id="searchbar">
			<img src ="../Content/U+Ni Logo White.png" class="searchLogo">
			<span class="searchBarArea">
				<form action="./Search.php" method="post" id="searchForm">
					<div class="search-group">
						<input type="text" id="term" name="term" placeholder="Search..." value="<?php echo htmlspecialchars($term); ?>" />
						<button class="icon" type="submit">
							<i class="material-icons">search</i>
						</button>
					</div>
				</form>
			</span>
		</div>
    <main  style="padding:0;margin:0;width:100%;max-width:none;">

	    <section class="col" id="resultsRegion">
				
    	</section>
		</main>
	</body>
	<!--<footer> &copy; 2018 By Aidan Marshall </footer>-->

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


		//API CALLERS
		$(function() {

			//Universities
			if (<?php echo "\""; echo $queryArray[0]; echo "\""; ?> == "@Universities") {
				if (<?php echo "\""; echo $term; echo "\""; ?> == "@Universities") {
					//Means it is just the @Universities. Search and Return all Universities
					$.ajax({
						url: "http://localhost:8080/U+Ni/Content/LocalUniversityList.json",
						type: "get",
						dataType: "json",
						success: function(data) {

							data.forEach(function(result) {

								var resultsRegion = document.getElementById("resultsRegion");

								var searchCard = document.createElement("div");
								searchCard.className = "searchCard";
								resultsRegion.appendChild(searchCard);

								var searchCardImage = document.createElement("div");
								searchCardImage.className = "searchCardImage";
								searchCard.appendChild(searchCardImage);

								var searchCardBody = document.createElement("div");
								searchCardBody.className = "searchCardBody";
								searchCard.appendChild(searchCardBody);


								var image = document.createElement("img");
								image.src = "../Content/university.png";
								searchCardImage.appendChild(image);

								var altText = document.createElement("p");
								altText.innerHTML = "University";
								searchCardImage.appendChild(altText);


								var header = document.createElement("p");
								header.innerHTML = result.name;
								header.className = "searchCardBodyHeader";
								searchCardBody.appendChild(header);

								var body = document.createElement("p");
								body.innerHTML = result.domains;
								body.className = "searchCardBodySummary";
								searchCardBody.appendChild(body);

								var link = document.createElement("a");
								link.innerHTML = "View"; link.target= "_blank";
								link.href=result.web_pages;
								searchCardBody.appendChild(link);
							
							});


							
						}
					});
				}
				else
				{
					//Means it is the @Universities and a term. Search and Return jobs with term
					$.ajax({
						url: "http://localhost:8080/U+Ni/Content/LocalUniversityList.json",
						type: "get",
						dataType: "json",
						success: function(data) {

							//SEARCH
							//SORT
							//DISPLAY

							//Creates an array with the same amount of items as the query array minus 1
							//This is to remove the @Universities beforehand to only search for query
							var jsQueryArray = <?php echo json_encode($queryArray); ?>;
							var searchedList = new Array();
							var searchedListNames = new Array();


							

							//Loop though all json file to get correct results
							data.forEach(function(result) {

								jsQueryArray.forEach(function(queryElement) {
									//If part of name contains query element add to searchedList
									if(result.name.includes(queryElement)){
										searchedList.push(result);
										searchedListNames.push(result.name);
									}
								});

							});
							


							
							var items = {}, sortableItems = [], i, len, element,
								searchedListNames;

							for (i = 0, len = searchedListNames.length; i < len; i += 1) {
								if (items.hasOwnProperty(searchedListNames[i])) {
									items[searchedListNames[i]] += 1;
								} else {
									items[searchedListNames[i]] = 1;
								}
							}

							for (element in items) {
								if (items.hasOwnProperty(element)) {
									sortableItems.push([element, items[element]]);
								}
							}

							sortableItems.sort(function (first, second) {
								return second[1] - first[1];
							});



							//Displays the searched results
							sortableItems.forEach(function(result){
								//Get the object from the list
								var currentResult;
								for (var i = 0, len = searchedList.length; i < len; i++) {
									if (searchedList[i].name === result[0]){
										currentResult = searchedList[i];
									}
										
								}
								



								var resultsRegion = document.getElementById("resultsRegion");

								var searchCard = document.createElement("div");
								searchCard.className = "searchCard";
								resultsRegion.appendChild(searchCard);

								var searchCardImage = document.createElement("div");
								searchCardImage.className = "searchCardImage";
								searchCard.appendChild(searchCardImage);

								var searchCardBody = document.createElement("div");
								searchCardBody.className = "searchCardBody";
								searchCard.appendChild(searchCardBody);


								var image = document.createElement("img");
								image.src = "../Content/University.png";
								searchCardImage.appendChild(image);

								var altText = document.createElement("p");
								altText.innerHTML = "University";
								searchCardImage.appendChild(altText);


								var header = document.createElement("p");
								header.innerHTML = currentResult.name;
								header.className = "searchCardBodyHeader";
								searchCardBody.appendChild(header);

								var body = document.createElement("p");
								body.innerHTML = currentResult.domains;
								body.className = "searchCardBodySummary";
								searchCardBody.appendChild(body);

								var link = document.createElement("a");
								link.innerHTML = "View"; 
								link.target= "_blank";
								link.href=currentResult.web_pages;
								searchCardBody.appendChild(link);
							});

							
						}
					});
				}
			}
			//Jobs
			else if (<?php echo "\""; echo $queryArray[0]; echo "\""; ?> == "@Jobs") {
				if (<?php echo count($queryArray); ?> == 1) {
					//Means it is just the @Jobs. Search and Return all Jobs
					$.ajax({
						url: "http://api.lmiforall.org.uk/api/v1/vacancies/search?keywords=*",
						type: "get",
						dataType: "json",
						success: function(data) {

							data.forEach(function(result) {

								var resultsRegion = document.getElementById("resultsRegion");

								var searchCard = document.createElement("div");
								searchCard.className = "searchCard";
								resultsRegion.appendChild(searchCard);

								var searchCardImage = document.createElement("div");
								searchCardImage.className = "searchCardImage";
								searchCard.appendChild(searchCardImage);

								var searchCardBody = document.createElement("div");
								searchCardBody.className = "searchCardBody";
								searchCard.appendChild(searchCardBody);


								var image = document.createElement("img");
								image.src = "../Content/job.png";
								searchCardImage.appendChild(image);

								var altText = document.createElement("p");
								altText.innerHTML = "Job";
								searchCardImage.appendChild(altText);


								var header = document.createElement("p");
								header.innerHTML = result.title;
								header.className = "searchCardBodyHeader";
								searchCardBody.appendChild(header);

								var body = document.createElement("p");
								body.innerHTML = result.summary;
								body.className = "searchCardBodySummary";
								searchCardBody.appendChild(body);

								var link = document.createElement("a");
								link.innerHTML = "View"; 
								link.target= "_blank";
								link.href=result.link;
								searchCardBody.appendChild(link);
							
							});


							
						}
					});
				}
				else
				{
					//Means it is the @Jobs and a term. Search and Return jobs with term
					$.ajax({
						url: "http://api.lmiforall.org.uk/api/v1/vacancies/search?keywords="+<?php echo "\""; echo $term; echo "\""; ?>,
						type: "get",
						dataType: "json",
						success: function(data) {

							data.forEach(function(result) {

								var resultsRegion = document.getElementById("resultsRegion");

								var searchCard = document.createElement("div");
								searchCard.className = "searchCard";
								resultsRegion.appendChild(searchCard);

								var searchCardImage = document.createElement("div");
								searchCardImage.className = "searchCardImage";
								searchCard.appendChild(searchCardImage);

								var searchCardBody = document.createElement("div");
								searchCardBody.className = "searchCardBody";
								searchCard.appendChild(searchCardBody);


								var image = document.createElement("img");
								image.src = "../Content/job.png";
								searchCardImage.appendChild(image);

								var altText = document.createElement("p");
								altText.innerHTML = "Job";
								searchCardImage.appendChild(altText);


								var header = document.createElement("p");
								header.innerHTML = result.title;
								header.className = "searchCardBodyHeader";
								searchCardBody.appendChild(header);

								var body = document.createElement("p");
								body.innerHTML = result.summary;
								body.className = "searchCardBodySummary";
								searchCardBody.appendChild(body);

								var link = document.createElement("a");
								link.innerHTML = "View"; 
								link.target= "_blank";
								link.href=result.link;
								searchCardBody.appendChild(link);
							
							});


							
						}
					});
				}
			}
			//Statistics
			else if (<?php echo "\""; echo $queryArray[0]; echo "\""; ?> == "@Statistics") {
				//Means it is just the @Statistics. Search and Return all Statistics
				
				
				//GET ARRAY
				var jsStatisticsArray = <?php echo json_encode($statistics); ?>;
				
				//Convert to JSON
				//Display in chart format

				jsStatisticsArray.forEach(function(statistic) {
					var dates = [];
					var values = [];
					$.ajax({															//CHANGE BACK TO STATISTIC
						url: "http://api.worldbank.org/v2/countries/GBR/indicators/"+statistic.code+"?date=2009:2015&format=json",
						type: "get",
						dataType: "json", //Data[1] filters out rubbish data
						success: function(data) {

							//Put data into 2 arrays (date and amount)
							data[1].forEach(function(result) {
								dates.push(result.date);
								values.push(result.value);
							});	
							
							dates.reverse();
							values.reverse();
						}	
					});

					//Add chart in card
					var resultsRegion = document.getElementById("resultsRegion");

					var searchCard = document.createElement("div");
					searchCard.className = "searchCard";
					resultsRegion.appendChild(searchCard);

					var header = document.createElement("p");
					header.innerHTML = statistic.title;
					header.className = "searchCardBodyHeader";
					searchCard.appendChild(header);


					var canvas = document.createElement("canvas");
					searchCard.appendChild(canvas);
					var ctx = canvas.getContext('2d');
					var myChart = new Chart(ctx, {
						type: 'line',
						data: {
							labels: dates,
							datasets: [{
								data: values,
								backgroundColor: [
									'rgba(255, 99, 132, 0.8)',
									'rgba(54, 162, 235, 0.8)',
									'rgba(255, 206, 86, 0.8)',
									'rgba(75, 192, 192, 0.8)',
									'rgba(153, 102, 255, 0.8)',
									'rgba(255, 159, 64, 0.8)'
								],
								borderColor: [
									'rgba(255, 99, 132, 1)',
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
								display: false,
							},
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero:false,
										display:false,
									}
								}]
							},
						}
					});

					var body = document.createElement("p");
					body.innerHTML = statistic.label;
					body.className = "searchCardBodyStatistic";
					searchCard.appendChild(body);
				});

			}
			else
			{
				//This means that no labels have been selected - Search all APIS


			}

			
		});

	</script>

</html>