<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')  {
	//siamo qua
	define("DB", $_POST["db_name"]);
	require "credentials.php";
	//deve accedere
	require "connect.php";
	
	if (!$mysqli->connect_errno) {
		require "query.php";
		
		if ($_POST["to_do"] == "select") {
			$tables = tables($mysqli);
			if ($mysqli->errno) {
				$error = $mysqli->error;
				$errorNo = $mysqli->errno;
				require "error.php";
			} elseif (sizeof($tables) > 0) {
				$fields;
				foreach ($tables as $rows) {
					foreach ($rows as $table_name)
					$fields[$table_name] = fields($mysqli, $table_name);
				}
				require "selection.php";
			}
			
			$mysqli->close();
		} elseif ($_POST["to_do"] == "insert") {
			$post = $_POST;
			$table_name = $post["table_name"];
			$names = [];
			$i = 0;
			while (isset($post["field_name_" . ($i)])) {
				$names[] = $post["field_name_" . ($i)];
				$i++;
			}
			
			$values = [];
			$types = "";
			foreach ($names as $name) {
				$values[] = $post[$name];
				$types.= get_right_type_for_mysqli($post[$name . "_type"]);
			}
			
			$query = insert_query($table_name, $names);
			$stmt = query($mysqli, $query, $types, $values);
			echo "DONE";
		}
	} else {
		$errorNo = "404";
		$error = "Database not found";
		require "error.php";
	}
} else {
    $errorNo = 403;
    $error = "Utilizzare il form indicato";
    require "error.php";
}