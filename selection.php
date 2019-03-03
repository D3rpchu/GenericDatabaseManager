<!DOCTYPE html>
<html>
    <head>
        <title>Popolamento database</title>
        <meta charset="UTF-8" />
		
		<script type="text/javascript">
			function select_table() {
				table_index = document.getElementById("tables").selectedIndex;
				console.log(table_index);
				switch(table_index) {
					<?php
					$index = 0;
					foreach ($fields as $rows) {
						echo "case " . ($index++) . ":";
						$content = "\"";
						$i = 0;
						foreach ($rows as $field) {
							//foreach ($field as $value) {
							//	$content .= "<label> " . $value . " </label>";
							//}
							$content .= set_right_input_type($field, $i++) . "<br />";
						}
						$content .= "\"";
						echo "document.getElementById(\"fields\").innerHTML = " . "\"<input type='hidden' name='table_name' value='\" + document.getElementById(\"tables\").value + \"'\" + " . $content . "; break;";
					}
					?>
				}
			}
			
			<?php
			function set_right_input_type($field, $index) {
				$name = $field[0];
				$type = $field[1];
				$required = $field[2] == "NO" ? true : false;
				
				$ret = "<label>" . $name . "</label>";
				if (strpos($type, "date") !== false) {
					$ret .= "<input type='date' name='" . $name . "' " . ($required ? "required='required'" : "") . "/>" . ($required ? "*" : "");
				} elseif (strpos($type, "varchar") !== false) {
						$ret .= "<textarea rows='1' name='" . $name . "'" . ($required ? "required='required'" : "") . "></textarea>" . ($required ? "*" : "");
				} elseif (strpos($type, "char") !== false) {
						$ret .= "<input type='text' name='" . $name . "' " . ($required ? "required='required'" : "") . "/>" . ($required ? "*" : "");
				} elseif (strpos($type, "int") !== false || strpos($type, "float") !== false || strpos($type, "double") !== false || strpos($type, "real") !== false) {
					$ret .= "<input type='number' name='" . $name . "' " . ($required ? "required='required'" : "") . "/>" . ($required ? "*" : "");
				}
				$ret .= "<input type='hidden' name='field_name_" . ($index) . "' value='" . ($name) . "' />";
				$ret .= "<input type='hidden' name='" . $name . "_type' value='" . $type . "' />";
				
				return $ret;
			}
			?>
		</script>
    </head>
    <body onload="select_table();">
		<form name="selection" action="controller.php" method="post">
			<fieldset>
				<legend>
					<select id="tables" onchange="select_table();" autofocus>
						<?php
						foreach ($tables as $rows) {
							foreach ($rows as $table_name) {
							?>
								<option name="<?php echo $table_name; ?>"> <?php echo $table_name; ?> </option>
							<?php
							}
						}
						?>
					</select>
				</legend>
				<label id="fields"></label>
				<input type="hidden" name="to_do" value="insert" />
				<input type="hidden" name="db_name" value="<?php echo DB; ?>" />
				<input type="submit" value="Send" />
				<input type="reset" value="reset" />
			</fieldset>
		</form>
    </body>
</html>