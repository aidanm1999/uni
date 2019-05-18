<!DOCTYPE html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Home - U+Ni</title>
		<link href="../Styles/siteStyle.css" rel="stylesheet" type="text/css">

		<link href="../Styles/nav.css" rel="stylesheet" type="text/css">
		<link href="../Styles/main.css" rel="stylesheet" type="text/css">
		<link href="../Styles/header.css" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="../Content/favicon.ico" type="image/x-icon">

		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	</head>

    <body>
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
        <main>
			<header class="banner">
				<p><img src ="../Content/U+Ni Logo White.png" class="logo"></p>
				<p style="font-size:x-small;text-align:center;padding-top:4px;">The search engine focused on connecting you and Universities around the UK.</p>
			</header>

      <section class="col">
	  
				<form action="./Search.php" method="post" id="searchForm">
					<div class="search-group">
						<input type="text" id="term" name="term" placeholder="Search..."/>
						<button class="icon" type="submit">
							<i class="material-icons">search</i>
						</button>
					</div>
				</form>
				<p style="font-size:x-small;text-align:center;padding-top:4px;">Hint: If you type @Jobs before your search, it will only show you jobs!</p>
      </section>
		</main>
	</body>
	<footer> &copy; 2018 By Aidan Marshall </footer>

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
	</script>

</html>
