<?php   
	use Phink\Data\Client\PDO\TPdoConnection;
	$cs = TPdoConnection::opener('webfactory_conf');
	$query = getArgument("query", "SELECT");
	$event = getArgument("event", "onLoad");
	$action = getArgument("action", "Ajouter");
	$lg = getArgument("lg", "fr");
	$id = getArgument("id");
	$di = getArgument("di");
	$tablename = "menus";
	$me_id = getArgument("me_id");
	if($event === "onLoad" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":

			$me_level="";
			$me_target="";
			$pa_id="";
			$bl_id="";
			$di_id="";
			$grp_id="";
			$me_charset="";
		break;
		case "Modifier":
			$sql="select * from $tablename where me_id='$me_id';";
			$stmt = $cs->query($sql);
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			$me_level = $rows["me_level"];
			$me_target = $rows["me_target"];
			$pa_id = $rows["pa_id"];
			$bl_id = $rows["bl_id"];
			$di_id = $rows["di_id"];
			$grp_id = $rows["grp_id"];
			$me_charset = $rows["me_charset"];
		break;
		}
	} else if($event === "onRun" && $query === "ACTION") {
		switch ($action) {
		case "Ajouter":
			$me_level = filterPOST("me_level");
			$me_target = filterPOST("me_target");
			$pa_id = filterPOST("pa_id");
			$bl_id = filterPOST("bl_id");
			$di_id = filterPOST("di_id");
			$grp_id = filterPOST("grp_id");
			$me_charset = filterPOST("me_charset");
			$sql = <<<SQL
			insert into $tablename (
				me_level, 
				me_target, 
				pa_id, 
				bl_id, 
				di_id, 
				grp_id, 
				me_charset
			) values (
				:me_level, 
				:me_target, 
				:pa_id, 
				:bl_id, 
				:di_id, 
				:grp_id, 
				:me_charset
			)
SQL;
			$stmt = $cs->query($sql, [':me_level' => $me_level, ':me_target' => $me_target, ':pa_id' => $pa_id, ':bl_id' => $bl_id, ':di_id' => $di_id, ':grp_id' => $grp_id, ':me_charset' => $me_charset]);
		break;
		case "Modifier":
			$me_level = filterPOST("me_level");
			$me_target = filterPOST("me_target");
			$pa_id = filterPOST("pa_id");
			$bl_id = filterPOST("bl_id");
			$di_id = filterPOST("di_id");
			$grp_id = filterPOST("grp_id");
			$me_charset = filterPOST("me_charset");
			$sql=<<<SQL
			update $tablename set 
				me_level = :me_level, 
				me_target = :me_target, 
				pa_id = :pa_id, 
				bl_id = :bl_id, 
				di_id = :di_id, 
				grp_id = :grp_id, 
				me_charset = :me_charset
			where me_id = '$me_id';
SQL;
			$stmt = $cs->query($sql, [':me_level' => $me_level, ':me_target' => $me_target, ':pa_id' => $pa_id, ':bl_id' => $bl_id, ':di_id' => $di_id, ':grp_id' => $grp_id, ':me_charset' => $me_charset]);
		break;
		case "Supprimer":
			$sql = "delete from $tablename where me_id='$me_id'";
			$stmt = $cs->query($sql);
		break;
		}
		$query="SELECT";
	}
