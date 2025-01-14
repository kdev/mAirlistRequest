<html>

<head>
	<title>mAirlistRequest - Update Database from CSV</title>
	<link rel="stylesheet" type="text/css" href="../css/default.css">
</head>

<body>
	<div id='uploadcsv'>
		<form action="uploadcsv.php" method="post" enctype="multipart/form-data">


			<center>
				<label>Step 1: Export CSV from mAirlist Database Window. And select the file using this button
					-></label><input type="file" name="csv" value="" /><br><br>
				<label>Step 2: Enter secret from settings file here -></label><input type="password"
					name="secret" /><br><br>
				<label>Step 3: Press Update Database -></label><input type="submit" name="submit"
					value="Update Database" />

			</center>
		</form>

	</div>

	<?php


	//get settings
	include('../inc/settings.inc.php');

	ini_set('auto_detect_line_endings', TRUE);

	function import_csv_to_sqlite($filename, $dbname)
	{

		echo '<tr><td>Open csv...</td></tr>';

		$csv = parse_my_csv($filename);
		//$csv = test($filename);
		$db = new SQLite3($dbname);

		$i = count($csv) - 1;

		echo '<tr><td>Deleting table and vacuum...';
		if ($db->exec('DELETE FROM music') && $db->exec('VACUUM')) {
			echo '<b>Success</b></td></tr>';
		} else {
			echo '<b>Failed</b></td></tr>';
		}

		echo '<tr><td>Creating Query for <b>' . $i . ' </b>items...</td></tr>';
		$query = 'INSERT into music (databaseID, artist, title, folder) VALUES ';


		while ($i > 0) {

			$databaseid = $csv[$i][0];
			$artist = SQLite3::escapeString($csv[$i][4]);
			$title = SQLite3::escapeString($csv[$i][3]);


			if ($i <> 1) {
				$query = $query .= "('" . $databaseid . "','" . $artist . "','" . $title . "','0'),";
			} else {
				$query = $query .= "('" . $databaseid . "','" . $artist . "','" . $title . "','0');";
			}



			$i--;
		}

		echo '<tr><td>Executing Query...';
		if ($db->exec($query)) {
			echo '<b>Success</b></td></tr>';
		} else {
			echo '<b>Failed</b></td></tr>';
		}

		//echo $query;
	

		$db->close();
		unset($db);

		echo '<tr><td><b>Done!</b></td></tr>';


	}


	function parse_my_csv($filename)
	{

		$lines = file($filename, FILE_IGNORE_NEW_LINES);


		$data = array();

		for ($i = 0; $i < count($lines); $i++) {
			array_push($data, str_getcsv($lines[$i]));
		}

		return $data;
	}

	function test($filename)
	{
		$file = fopen($filename, 'r');
		$data = [];

		while ($row = fgetcsv($file)) {
			$data[] = $row;
		}

		fclose($file);

		return $data;
	}

	if (isset($_POST['submit'])) {

		if (isset($_POST["secret"])) {


			if ($_POST["secret"] <> $uploadCsvSecret) {
				echo 'access denied';
				exit;
			}
		}

		$csv = array();

		echo '<div><table>';

		// check there are no errors
		if ($_FILES['csv']['error'] == 0) {

			$name = $_FILES['csv']['tmp_name'];
			$dbname = 'mAirlistRequest.db';

			import_csv_to_sqlite($name, $dbname);
		} else {
			echo 'CSV has errors';
			exit;
		}

		echo '</table></div>';

	}

	?>
</body>

</html>