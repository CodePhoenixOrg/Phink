<center>
<?php   
	include("pages_code.php");
	use \Puzzle\Data\Controls as DataControls;
	use \Phink\Registry\TRegistry;
	$db_prefix = TRegistry::ini("data", "db_prefix");
	$datacontrols = new DataControls($lg, $db_prefix);
	$grid_colors = TRegistry::ini("grid_colors");
	$panel_colors = TRegistry::ini("panel_colors");
	$pc = getArgument("pc");
	$sr = getArgument("sr");
	$curl_pager = "";
	$dialog = "";
	if(isset($pc)) $curl_pager="&pc=$pc";
	if(isset($sr)) $curl_pager.="&sr=$sr";
	if($query === "SELECT") {
			$sql = "select pa_id, di_name as `Page` from $tablename order by pa_id";
			$dbgrid = $datacontrols->createPagerDbGrid($tablename, $sql, $id, "admin", "&query=ACTION$curl_pager", "", true, true, $dialog, array(0, 400), 15, $grid_colors, $cs);
			//$dbgrid = tableShadow($tablename, $dbgrid);
			echo "<br>".$dbgrid;
	} elseif($query === "ACTION") {
?>
<form method="POST" name="pagesForm" action="admin?id=19&lg=fr">
	<input type="hidden" name="query" value="ACTION">
	<input type="hidden" name="event" value="onRun">
	<input type="hidden" name="pc" value="<?php echo $pc?>">
	<input type="hidden" name="sr" value="<?php echo $sr?>">
	<input type="hidden" name="pa_id" value="<?php echo $pa_id?>">
	<table border="1" bordercolor="<?php echo $panel_colors["border_color"]?>" cellpadding="0" cellspacing="0" witdh="100%" height="1">
		<tr>
			<td align="center" valign="top" bgcolor="<?php echo $panel_colors["back_color"]?>">
				<table>
				<tr>
					<td>pa_id</td>
					<td>
						<?php echo $pa_id?>
					</td>
				</tr>
				<tr>
					<td>pa_filename</td>
					<td>
						<textarea name="pa_filename" cols="80" rows="4"><?php echo $pa_filename?></textarea>
					</td>
				</tr>
					<tr>
						<td align="center" colspan="2">
							<input type="submit" name="action" value="<?php echo $action?>">
							<?php   if($action!="Ajouter") { ?>
								<input type="submit" name="action" value="Supprimer">
							<?php   } ?>
							<input type="reset" name="action" value="Annuler">
							<input type="submit" name="action" value="Retour">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<?php   	} ?>
</center>
