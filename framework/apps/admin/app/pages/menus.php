<center>
<?php
include "menus_code.php";
use \Phink\Registry\TRegistry;
use \Puzzle\Data\Controls as DataControls;
$db_prefix = TRegistry::ini("data", "db_prefix");
$datacontrols = new DataControls($lg, $db_prefix);
$grid_colors = TRegistry::ini("grid_colors");
$panel_colors = TRegistry::ini("panel_colors");
$pc = getArgument("pc");
$sr = getArgument("sr");
$curl_pager = "";
$dialog = "";
if (isset($pc)) {
    $curl_pager = "&pc=$pc";
}

if (isset($sr)) {
    $curl_pager .= "&sr=$sr";
}

if ($query === "SELECT") {
    $sql = <<<SQL
		SELECT
			me_id,
			d.di_fr_short AS Page,
			CASE
				WHEN me_level = '0' THEN 'Caché'
				WHEN me_level = '1' THEN 'Principal'
				WHEN me_level = '2' THEN 'Latéral'
			END AS Niveau,
			bd.di_fr_short AS Bloc
		FROM
			menus m
				INNER JOIN
			pages p ON m.pa_id = p.pa_id
				INNER JOIN
			dictionary d ON d.di_id = p.di_id
				LEFT JOIN
			blocks b ON m.bl_id = b.bl_id
				LEFT OUTER JOIN
			dictionary bd ON bd.di_id = b.di_id
		ORDER BY d.di_fr_short
		SQL;
	$dbgrid = $datacontrols->createPagerDbGrid("entrées", $sql, $id, "page.html", "&query=ACTION$curl_pager", "", true, true, $dialog, [0, 200, 100], 15, $grid_colors, $cs);
	//$dbgrid = $datacontrols->createPagerDbGrid($tablename, $sql, $id, "page.html", "&query=ACTION$curl_pager", "", true, true, $dialog, array(0, 400), 15, $grid_colors, $cs);
    echo "<br>" . $dbgrid;
} elseif ($query === "ACTION") {
    ?>
<form method="POST" name="menusForm" action="page.html?id=18&lg=fr">
	<input type="hidden" name="query" value="ACTION">
	<input type="hidden" name="event" value="onRun">
	<input type="hidden" name="pc" value="<?php echo $pc ?>">
	<input type="hidden" name="sr" value="<?php echo $sr ?>">
	<input type="hidden" name="int" value="<?php echo $int ?>">
	<table border="1" bordercolor="<?php echo $panel_colors["border_color"]?>" cellpadding="0" cellspacing="0" witdh="100%" height="1">
		<tr>
			<td align="center" valign="top" bgcolor="<?php echo $panel_colors["back_color"]?>">
				<table>
				<tr>
					<td>me_id</td>
					<td>
						<?php echo $me_id?>
					</td>
				</tr>				<tr>
					<td>me_level</td>
					<td>
						<input type="text" name="me_level" size="1" value="<?php echo $me_level?>">
					</td>
				</tr>				<tr>
					<td>me_target</td>
					<td>
						<input type="text" name="me_target" size="7" value="<?php echo $me_target?>">
					</td>
				</tr>				<tr>
					<td>pa_id</td>
					<td>
						<select name="pa_id">
						<?php   $sql="select pa_id, di_name from pages order by di_name";
						$options = $datacontrols->createOptionsFromQuery($sql, 0, 1, [], $pa_id, false, $cs);
						echo $options["list"];?>
						</select>
					</td>
				</tr>				<tr>
					<td>bl_id</td>
					<td>
						<select name="bl_id">
						<?php   $sql="select bl_id, bl_column from blocks order by bl_column";
						$options = $datacontrols->createOptionsFromQuery($sql, 0, 1, [], $bl_id, false, $cs);
						echo $options["list"];?>
						</select>
					</td>
				</tr>				<tr>
					<td>di_id</td>
					<td>
						<select name="di_id">
						<?php   $sql="select di_id, di_name from dictionary order by di_name";
						$options = $datacontrols->createOptionsFromQuery($sql, 0, 1, [], $di_id, false, $cs);
						echo $options["list"];?>
						</select>
					</td>
				</tr>				<tr>
					<td>grp_id</td>
					<td>
						<select name="grp_id">
						<?php   $sql="select grp_id, grp_group from groups order by grp_group";
						$options = $datacontrols->createOptionsFromQuery($sql, 0, 1, [], $grp_id, false, $cs);
						echo $options["list"];?>
						</select>
					</td>
				</tr>				<tr>
					<td>me_charset</td>
					<td>
						<input type="text" name="me_charset" size="8" value="<?php echo $me_charset?>">
					</td>
				</tr>					<tr>
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
<?php }?>
</center>
