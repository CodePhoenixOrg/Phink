<?php   
	use Phink\Data\Client\PDO\TPdoConnection;
	$cs = TPdoConnection::opener('niduslite_conf');
	$query = getArgument("query", "SELECT");
	$event = getArgument("event", "onLoad");
	$action = getArgument("action", "Ajouter");
	$id = getArgument("id");
	$di = getArgument("di");
	$tablename = "changelog";
	$cl_id = getArgument("cl_id");
	if($event === "onLoad" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":

			$cl_title="";
			$cl_text="";
			$cl_date="";
			$fr_id="";
			$mbr_id="";
		break;
		case "Modifier":
			$sql="select * from $tablename where cl_id='$cl_id';";
			$stmt = $cs->query($sql);
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			$cl_title = $rows["cl_title"];
			$cl_text = $rows["cl_text"];
			$cl_date = $rows["cl_date"];
			$fr_id = $rows["fr_id"];
			$mbr_id = $rows["mbr_id"];
		break;
		}
	} else if($event === "onRun" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":
			$cl_title = filterPOST("cl_title");
			$cl_text = filterPOST("cl_text");
			$cl_date = filterPOST("cl_date");
			$fr_id = filterPOST("fr_id");
			$mbr_id = filterPOST("mbr_id");
			$sql = <<<SQL
			insert into $tablename (
				cl_title, 
				cl_text, 
				cl_date, 
				fr_id, 
				mbr_id
			) values (
				:cl_title, 
				:cl_text, 
				:cl_date, 
				:fr_id, 
				:mbr_id
			)
SQL;
			$stmt = $cs->query($sql, [':cl_title' => $cl_title, ':cl_text' => $cl_text, ':cl_date' => $cl_date, ':fr_id' => $fr_id, ':mbr_id' => $mbr_id]);
		break;
		case "Modifier":
			$cl_title = filterPOST("cl_title");
			$cl_text = filterPOST("cl_text");
			$cl_date = filterPOST("cl_date");
			$fr_id = filterPOST("fr_id");
			$mbr_id = filterPOST("mbr_id");
			$sql=<<<SQL
			update $tablename set 
				cl_title = :cl_title, 
				cl_text = :cl_text, 
				cl_date = :cl_date, 
				fr_id = :fr_id, 
				mbr_id = :mbr_id
			where cl_id = '$cl_id';
SQL;
			$stmt = $cs->prepare($sql);
			$stmt->execute([':cl_title' => $cl_title, ':cl_text' => $cl_text, ':cl_date' => $cl_date, ':fr_id' => $fr_id, ':mbr_id' => $mbr_id, ':cl_title' => $cl_title, ':cl_text' => $cl_text, ':cl_date' => $cl_date, ':fr_id' => $fr_id, ':mbr_id' => $mbr_id]);
		break;
		case "Supprimer":
			$sql = "delete from $tablename where cl_id='$cl_id'";
			$stmt = $cs->query($sql);
		break;
		}
		$query="SELECT";
	}
