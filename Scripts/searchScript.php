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

$conn = null;

header("Location:../Pages/Search.php");

?>
