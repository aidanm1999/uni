


<!DOCTYPE html>
<!-- saved from url=(0157)https://blackboard.gcal.ac.uk/bbcswebdav/pid-2436472-dt-content-rid-9688877_2/courses/M3I322923-18-A/M3I322923-18-A_ImportedContent_20180829063352/index.html -->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Hostel Directions</title>
		<link href="../Styles/styleSheet.css" rel="stylesheet" type="text/css">
		<link rel="shortcut icon" href="../Content/favicon.ico" type="image/x-icon">
	</head>

    <body>
        <header>
            <h1>Getting Here</h1>
            <img src="../Content/banner.jpg" class="banner">
        </header>
		<nav>
            <ul>
				<li><a href="../Pages/Index.php">Home Page</a></li>
                <li><a href="../Pages/Facilities.php">Facilities</a></li>
                <li><a href="../Pages/Prices.php">Prices</a></li>
                <li><a href="../Pages/Cafe.php">Cafe</a></li>
                <li><a href="../Pages/Directions.php">Getting Here</a></li>
                <li><a href="../Pages/ContactForm.php">Contact Us</a></li>
            </ul>
        </nav>
        <main>
			<section class="col col-100">
			<?php $servername = "localhost"; $username = "root"; $password = ""; $dbname = "test";
			try {
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				} 
				catch(PDOException $e)
				{ 
					echo "Connection failed: " . $e->getMessage(); 
				}

				$query=$conn->prepare("INSERT INTO directions (transport, days, cafe,pool,bed) VALUES (?,?,?,?,?)");
				$query->bindParam(1, $transport);
				$query->bindParam(2, $days);
				$query->bindParam(3, $cafe);
				$query->bindParam(4, $pool);
				$query->bindParam(5, $bed); 

				$zero = "hello";// Take away '' for int

				$transport=$_POST['transport'];
				$days=$_POST['days'];
				$cafe = $_POST['cafe'];
				$pool=$_POST['pool'];
				$bed=$_POST['bed'];
				$query->execute();
				$conn = null;
				echo 'Hi, thanks for letting us know.</br>'; echo 'We cant wait to see you!'; ?>
			</section>
		</main>
	</body>
	<footer> (C) 2018 By Backpacker's Hostel 2018 </footer>
</html>