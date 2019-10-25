<?php   
	use Phink\Data\Client\PDO\TPdoConnection;
	$cs = TPdoConnection::opener('webfactory_conf');
	$query = getArgument("query", "SELECT");
	$event = getArgument("event", "onLoad");
	$action = getArgument("action", "Ajouter");
	$lg = getArgument("lg", "fr");
	$id = getArgument("id");
	$di = getArgument("di");
	$tablename = "pages";
	$pa_id = getArgument("pa_id");
	if($event === "onLoad" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":

			$pa_filename="";
		break;
		case "Modifier":
			$sql="select * from $tablename where pa_id='$pa_id';";
			$stmt = $cs->query($sql);
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			$pa_filename = $rows["pa_filename"];
		break;
		}
	} else if($event === "onRun" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":
			$pa_filename = filterPOST("pa_filename");
			$sql = <<<SQL
			insert into $tablename (
				pa_filename
			) values (
				:pa_filename
			)
SQL;
			$stmt = $cs->query($sql, [':pa_filename' => $pa_filename]);
		break;
		case "Modifier":
			$pa_filename = filterPOST("pa_filename");
			$sql=<<<SQL
			update $tablename set 
				pa_filename = :pa_filename
			where pa_id = '$pa_id';
SQL;
			$stmt = $cs->query($sql, [':pa_filename' => $pa_filename]);
		break;
		case "Supprimer":
			$sql = "delete from $tablename where pa_id='$pa_id'";
			$stmt = $cs->query($sql);
		break;
		}
		$query="SELECT";
	}
