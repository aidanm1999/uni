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
				<p>PAGE HEADER</p>
			</header>
            <section class="col">
                <h2>Cafe </h2>
                <p> The Hostel has one of the best locations in Scotland. From the attractive loch-side garden, the views across to mountain range are breathtaking. Good facilities mean the hostel is popular with walkers and tourist alike.</p>
                <p>There are otters, badgers and pine martins in the area. Red deer are numerous, and on the mountains ravens, ptarmigan, golden eagles and mountain hares can be seen on occasion</p>
                <p>... well situated for walking and exploring some of Scotland's most celebrated scenery"!</p>
                <p>With no mobile phone reception the emphasis here is on tranquillity and relaxation! </p>
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