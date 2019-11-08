<?php   
	use Phink\Data\Client\PDO\TPdoConnection;
	$cs = TPdoConnection::opener('niduslite_conf');
	$query = getArgument("query", "SELECT");
	$event = getArgument("event", "onLoad");
	$action = getArgument("action", "Ajouter");
	$id = getArgument("id");
	$di = getArgument("di");
	$tablename = "todo";
	$td_id = getArgument("td_id");
	$mbr_id2 = getArgument("mbr_id2");
	if($event === "onLoad" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":

			$td_title="";
			$td_text="";
			$td_priority="";
			$td_expiry="";
			$td_status="";
			$td_date="";
			$mbr_id="";
			$mbr_id2="";
		break;
		case "Modifier":
			$sql="select * from $tablename where td_id='$td_id';";
			$stmt = $cs->query($sql);
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			$td_title = $rows["td_title"];
			$td_text = $rows["td_text"];
			$td_priority = $rows["td_priority"];
			$td_expiry = $rows["td_expiry"];
			$td_status = $rows["td_status"];
			$td_date = $rows["td_date"];
			$mbr_id = $rows["mbr_id"];
			$mbr_id2 = $rows["mbr_id2"];
		break;
		}
	} else if($event === "onRun" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":
			$td_title = filterPOST("td_title");
			$td_text = filterPOST("td_text");
			$td_priority = filterPOST("td_priority");
			$td_expiry = filterPOST("td_expiry");
			$td_status = filterPOST("td_status");
			$td_date = filterPOST("td_date");
			$mbr_id = filterPOST("mbr_id");
			$mbr_id2 = filterPOST("mbr_id2");
			$sql = <<<SQL
			insert into $tablename (
				td_title, 
				td_text, 
				td_priority, 
				td_expiry, 
				td_status, 
				td_date, 
				mbr_id,
				mbr_id2
			) values (
				:td_title, 
				:td_text, 
				:td_priority, 
				:td_expiry, 
				:td_status, 
				:td_date, 
				:mbr_id,
				:mbr_id2
			)
SQL;
			$stmt = $cs->prepare($sql);
			$stmt->execute([':td_title' => $td_title, ':td_text' => $td_text, ':td_priority' => $td_priority, ':td_expiry' => $td_expiry, ':td_status' => $td_status, ':td_date' => $td_date, ':mbr_id' => $mbr_id, ':mbr_id2' => $mbr_id2]);
		break;
		case "Modifier":
			$td_title = filterPOST("td_title");
			$td_text = filterPOST("td_text");
			$td_priority = filterPOST("td_priority");
			$td_expiry = filterPOST("td_expiry");
			$td_status = filterPOST("td_status");
			$td_date = filterPOST("td_date");
			$mbr_id = filterPOST("mbr_id");
			$mbr_id2 = filterPOST("mbr_id2");
			$sql=<<<SQL
			update $tablename set 
				td_title = :td_title, 
				td_text = :td_text, 
				td_priority = :td_priority, 
				td_expiry = :td_expiry, 
				td_status = :td_status, 
				td_date = :td_date, 
				mbr_id = :mbr_id,
				mbr_id2 = :mbr_id2
			where td_id = '$td_id';
SQL;
			$stmt = $cs->prepare($sql);
			$stmt->execute([':td_title' => $td_title, ':td_text' => $td_text, ':td_priority' => $td_priority, ':td_expiry' => $td_expiry, ':td_status' => $td_status, ':td_date' => $td_date, ':mbr_id' => $mbr_id, ':mbr_id2' => $mbr_id2]);
		break;
		case "Supprimer":
			$sql = "delete from $tablename where td_id='$td_id'";
			$stmt = $cs->query($sql);
		break;
		}
		$query="SELECT";
	}
