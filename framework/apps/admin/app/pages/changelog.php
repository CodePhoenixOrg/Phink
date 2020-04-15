<center>
<?php   
	include("changelog_code.php");
	use \Puzzle\Data\Controls as DataControls;
	use \Phink\Registry\TRegistry;

	$lg = getArgument("lg", "fr");
	$db_prefix = TRegistry::ini('data', 'db_prefix');
	$grid_colors = TRegistry::ini('grid_colors');
	$panel_colors = TRegistry::ini('panel_colors');
			
	$datacontrols = new DataControls($lg, $db_prefix);
	$pc = getArgument("pc");
	$sr = getArgument("sr");
	$curl_pager = "";
	$dialog = "";
	if(isset($pc)) $curl_pager="&pc=$pc";
	if(isset($sr)) $curl_pager.="&sr=$sr";
	if($query === "SELECT") {
			$sql = "select cl_id, concat('<b>', cl_title, '</b><br>', cl_text, '<br>') as `changements` from $tablename order by cl_id";
			$dbgrid = $datacontrols->createPagerDbGrid("changements", $sql, $id, "page.html", "&query=ACTION$curl_pager", "", true, true, $dialog, array(0, 400), 15, $grid_colors, $cs);
			//$dbgrid = tableShadow($tablename, $dbgrid);
			echo "<br>".$dbgrid;
	} elseif($query === "ACTION") {
?>
<form method="POST" name="changelogForm" action="page.html?id=24&lg=fr">
	<input type="hidden" name="query" value="ACTION">
	<input type="hidden" name="event" value="onRun">
	<input type="hidden" name="pc" value="<?php echo $pc?>">
	<input type="hidden" name="sr" value="<?php echo $sr?>">
	<input type="hidden" name="cl_id" value="<?php echo $cl_id?>">
	<table border="1" bordercolor="<?php echo $panel_colors["border_color"]?>" cellpadding="0" cellspacing="0" witdh="100%" height="1">
		<tr>
			<td align="center" valign="top" bgcolor="<?php echo $panel_colors["back_color"]?>">
				<table>
				<tr>
					<td>Id</td>
					<td>
						<?php echo $cl_id?>
					</td>
				</tr>
				<tr>
					<td>Intitulé</td>
					<td>
						<textarea name="cl_title" cols="80" rows="4"><?php echo $cl_title?></textarea>
					</td>
				</tr>
				<tr>
					<td>Description</td>
					<td>
						<textarea name="cl_text" cols="80" rows="8"><?php echo $cl_text?></textarea>
					</td>
				</tr>
				<tr>
					<td>Date</td>
					<td>
						<input type="text" name="cl_date" size="19" value="<?php echo (empty($cl_date)) ? date("1970-01-01") : $cl_date; ?>" >
					</td>
				</tr>
				<tr>
					<td>Forum</td>
					<td>
						<select name="fr_id">
						<?php   $sql="select fr_id, fr_title from forums order by fr_title";
						$options = $datacontrols->createOptionsFromQuery($sql, 0, 1, [], $fr_id, false, $cs);
						echo $options["list"];?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Auteur</td>
					<td>
						<select name="mbr_id">
						<?php   $sql="select mbr_id, mbr_nom from members order by mbr_nom";
						$options = $datacontrols->createOptionsFromQuery($sql, 0, 1, [], $mbr_id, false, $cs);
						echo $options["list"];?>
						</select>
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
