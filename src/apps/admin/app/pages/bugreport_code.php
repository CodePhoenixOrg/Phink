<?php   
	use Phink\Data\Client\PDO\TPdoConnection;
	$cs = TPdoConnection::opener('webfactory_conf');
	$query = getArgument("query", "SELECT");
	$event = getArgument("event", "onLoad");
	$action = getArgument("action", "Ajouter");
	$lg = getArgument("lg", "fr");
	$id = getArgument("id");
	$di = getArgument("di");
	$tablename = "bugreport";
	$br_id = getArgument("br_id");
	if($event === "onLoad" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":

			$br_title="";
			$br_text="";
			$br_importance="";
			$br_status="";
			$br_date="";
			$mbr_id="";
		break;
		case "Modifier":
			$sql="select * from $tablename where br_id='$br_id';";
			$stmt = $cs->query($sql);
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			$br_title = $rows["br_title"];
			$br_text = $rows["br_text"];
			$br_importance = $rows["br_importance"];
			$br_status = $rows["br_status"];
			$br_date = $rows["br_date"];
			$mbr_id = $rows["mbr_id"];
		break;
		}
	} else if($event === "onRun" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":
			$br_title = filterPOST("br_title");
			$br_text = filterPOST("br_text");
			$br_importance = filterPOST("br_importance");
			$br_status = filterPOST("br_status");
			$br_date = filterPOST("br_date");
			$mbr_id = filterPOST("mbr_id");
			$sql = <<<SQL
			insert into $tablename (
				br_title, 
				br_text, 
				br_importance, 
				br_status, 
				br_date, 
				mbr_id
			) values (
				:br_title, 
				:br_text, 
				:br_importance, 
				:br_status, 
				:br_date, 
				:mbr_id
			)
SQL;
			$stmt = $cs->query($sql, [':br_title' => $br_title, ':br_text' => $br_text, ':br_importance' => $br_importance, ':br_status' => $br_status, ':br_date' => $br_date, ':mbr_id' => $mbr_id]);
		break;
		case "Modifier":
			$br_title = filterPOST("br_title");
			$br_text = filterPOST("br_text");
			$br_importance = filterPOST("br_importance");
			$br_status = filterPOST("br_status");
			$br_date = filterPOST("br_date");
			$mbr_id = filterPOST("mbr_id");
			$sql=<<<SQL
			update $tablename set 
				br_title = :br_title, 
				br_text = :br_text, 
				br_importance = :br_importance, 
				br_status = :br_status, 
				br_date = :br_date, 
				mbr_id = :mbr_id
			where br_id = '$br_id';
SQL;
			$stmt = $cs->query($sql, [':br_title' => $br_title, ':br_text' => $br_text, ':br_importance' => $br_importance, ':br_status' => $br_status, ':br_date' => $br_date, ':mbr_id' => $mbr_id]);
		break;
		case "Supprimer":
			$sql = "delete from $tablename where br_id='$br_id'";
			$stmt = $cs->query($sql);
		break;
		}
		$query="SELECT";
	}
