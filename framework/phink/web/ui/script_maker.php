<?php
/*
Copyright (C) 2019 David Blanchard

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Phink\Web\UI;

use Phink\Core\TObject;

class TScriptMaker extends TObject
{
    public function __construct()
    {}

    public function makeCode(
        $conf,
        $table = "",
        $page_id = 0,
        $data
    ): string {

        $A_sqlFields = array_map(function ($defs) {
            $defs = (object) json_decode($defs);
            return $defs->fieldname;
        }, $data);

        $A_phpTypes = array_map(function ($defs) {
            $defs = (object) json_decode($defs);
            return $defs->phptype;
        }, $data);

        $indexfield = $A_sqlFields[0];
        $secondfield = $A_sqlFields[1];

        $script = "\n";
        $script = "<?php   \n";
        $script .= "\tuse Phink\Data\Client\PDO\TPdoConnection;\n";
        $script .= "\t\$cs = TPdoConnection::opener('$conf');\n";
        $script .= "\t\$query = getArgument(\"query\", \"SELECT\");\n";
        $script .= "\t\$event = getArgument(\"event\", \"onLoad\");\n";
        $script .= "\t\$action = getArgument(\"action\", \"Ajouter\");\n";
        $script .= "\t\$lg = getArgument(\"lg\", \"fr\");\n";
        $script .= "\t\$id = getArgument(\"id\");\n";
        $script .= "\t\$di = getArgument(\"di\");\n";
        $script .= "\t\$tablename = \"$table\";\n";
        $defs = explode(',', $A_sqlFields[0]);
        $fieldname = $defs[0];
        $script .= "\t$$fieldname = getArgument(\"$fieldname\");\n";
        $script .= "\tif(\$event === \"onLoad\" && \$query === \"ACTION\") {\n";
        $script .= "\t\tswitch (\$action) {\n";
        $script .= "\t\tcase \"Ajouter\":\n\n";
        for ($i = 0; $i < count($A_sqlFields); $i++) {
            $fieldname = $A_sqlFields[$i];

            if ($i === 0) {
                $indexfield = $fieldname;
            } else {
                $script .= "\t\t\t\$$fieldname=\"\";\n";
            }
        }
        $script .= "\t\tbreak;\n";
        $script .= "\t\tcase \"Modifier\":\n";
        for ($i = 1; $i < count($A_sqlFields); $i++) {
            $fieldname = $A_sqlFields[$i];

            if ($i === 1) {
                $script .= "\t\t\t\$sql=\"select * from \$tablename where $indexfield='$$indexfield';\";\n";
                $script .= "\t\t\t\$stmt = \$cs->query(\$sql);\n";
                $script .= "\t\t\t\$rows = \$stmt->fetch(PDO::FETCH_ASSOC);\n";
                $script .= "\t\t\t\$$fieldname = \$rows[\"$fieldname\"];\n";
            } else {
                $script .= "\t\t\t\$$fieldname = \$rows[\"$fieldname\"];\n";
            }
        }
        $script .= "\t\tbreak;\n";
        $script .= "\t\t}\n";
        $script .= "\t} else if(\$event === \"onRun\" && \$query === \"ACTION\") {\n";
        $script .= "\t\tswitch (\$action) {\n";
        $script .= "\t\tcase \"Ajouter\":\n";
        for ($i = 1; $i < count($A_sqlFields); $i++) {
            $fieldname = $A_sqlFields[$i];
            $script .= "\t\t\t\$$fieldname = filterPOST(\"$fieldname\");\n";
        }
        $replaces = [];
        $insertFields = [];
        $prepargs = [];
        for ($i = 1; $i < count($A_sqlFields); $i++) {
            $fieldname = $A_sqlFields[$i];
            $fieldtype = $A_phpTypes[$i];
            $insertFields[$i] = "\t\t\t\t$fieldname";
            $prepargs[] = "':$fieldname' => \$$fieldname";
        }
        $prepare = '[' . implode(', ', $prepargs) . ']';

        $script .= "\t\t\t\$sql = <<<SQL\n\t\t\tinsert into \$tablename (\n";
        $script .= implode(", \n", $insertFields) . "";
        $script .= "\n\t\t\t) values (\n";
        $insertValues = [];
        for ($i = 1; $i < count($A_sqlFields); $i++) {
            $fieldname = $A_sqlFields[$i];
            $fieldtype = $A_phpTypes[$i];
            $insertValues[$i] = "\t\t\t\t:$fieldname";
        }
        $script .= implode(", \n", $insertValues) . "\n";
        $script .= "\t\t\t)\n";
        $script .= "SQL;\n";
        $script .= "\t\t\t\$stmt = \$cs->query(\$sql, $prepare);\n";
        $script .= "\t\tbreak;\n";
        $script .= "\t\tcase \"Modifier\":\n";
        for ($i = 1; $i < count($A_sqlFields); $i++) {
            $fieldname = $A_sqlFields[$i];
            $script .= "\t\t\t\$$fieldname = filterPOST(\"$fieldname\");\n";
        }
        $replaces = (array) null;
        $update = [];
        $prepargs = (array) null;
        $prepare = (array) null;
        for ($i = 1; $i < count($A_sqlFields); $i++) {
            $fieldname = $A_sqlFields[$i];
            $fieldtype = $A_phpTypes[$i];
            $update[$i] = "\t\t\t\t$fieldname = :$fieldname";
            $prepargs[] = "':$fieldname' => \$$fieldname";
        }
        $prepare = '[' . implode(', ', $prepargs) . ']';

        $script .= "\t\t\t\$sql=<<<SQL\n\t\t\tupdate \$tablename set \n";
        $script .= implode(", \n", $update) . "\n";
        $script .= "\t\t\twhere $indexfield = '\$$indexfield';\n";
        $script .= "SQL;\n";
        $script .= "\t\t\t\$stmt = \$cs->query(\$sql, $prepare);\n";
        $script .= "\t\tbreak;\n";
        $script .= "\t\tcase \"Supprimer\":\n";
        $script .= "\t\t\t\$sql = \"delete from \$tablename where $indexfield='\$$indexfield'\";\n";
        $script .= "\t\t\t\$stmt = \$cs->query(\$sql);\n";
        $script .= "\t\tbreak;\n";
        $script .= "\t\t}\n";
        $script .= "\t\t\$query=\"SELECT\";\n";

        $script .= "\t}\n";

        return $script;
    }

    public function makePage(
        $database,
        $table = '',
        $pa_filename = '',
        $page_id = 0,
        $data
    ): string {
        $formname = $table . "Form";

        $A_sqlFields = array_map(function ($defs) {
            $defs = (object) json_decode($defs);
            return $defs->fieldname;
        }, $data);

        $indexfield = $A_sqlFields[0];
        $secondfield = $A_sqlFields[1];

        $script = "\n";
        $script = "<center>\n";
        $script .= "<?php   \n";
        $script .= "\tinclude(\"" . $pa_filename . "_code.php\");\n";
        $script .= "\tuse \\Puzzle\\Data\\Controls as DataControls;\n";
        $script .= "\tuse \\Phink\\Registry\\TRegistry;\n";
        $script .= "\t\$db_prefix = TRegistry::ini(\"data\", \"db_prefix\");\n";
        $script .= "\t\$datacontrols = new DataControls(\$lg, \$db_prefix);\n";
        $script .= "\t\$grid_colors = TRegistry::ini(\"grid_colors\");\n";
        $script .= "\t\$panel_colors = TRegistry::ini(\"panel_colors\");\n";
        $script .= "\t\$pc = getArgument(\"pc\");\n";
        $script .= "\t\$sr = getArgument(\"sr\");\n";
        $script .= "\t\$curl_pager = \"\";\n";
        $script .= "\t\$dialog = \"\";\n";
        $script .= "\tif(isset(\$pc)) \$curl_pager=\"&pc=\$pc\";\n";
        $script .= "\tif(isset(\$sr)) \$curl_pager.=\"&sr=\$sr\";\n";
        $script .= "\tif(\$query === \"SELECT\") {\n";
        $script .= "\t\t\t\$sql = \"select $indexfield, $secondfield from \$tablename order by $indexfield\";\n";
        $script .= "\t\t\t\$dbgrid = \$datacontrols->createPagerDbGrid(\$tablename, \$sql, \$id, \"admin\", \"&query=ACTION\$curl_pager\", \"\", true, true, \$dialog, array(0, 400), 15, \$grid_colors, \$cs);\n";
        $script .= "\t\t\techo \"<br>\".\$dbgrid;\n";
        $script .= "\t} elseif(\$query === \"ACTION\") {\n";
        $script .= "?>\n";
        $page_filename = "admin";
        $script .= "<form method=\"POST\" name=\"$formname\" action=\"admin?id=$page_id&lg=fr\">\n";
        $script .= "\t<input type=\"hidden\" name=\"query\" value=\"ACTION\">\n";
        $script .= "\t<input type=\"hidden\" name=\"event\" value=\"onRun\">\n";
        $script .= "\t<input type=\"hidden\" name=\"pc\" value=\"<?php echo \$pc?>\">\n";
        $script .= "\t<input type=\"hidden\" name=\"sr\" value=\"<?php echo \$sr?>\">\n";
        $script .= "\t<input type=\"hidden\" name=\"$indexfield\" value=\"<?php echo $$indexfield?>\">\n";
        $script .= "\t<table border=\"1\" bordercolor=\"<?php echo \$panel_colors[\"border_color\"]?>\" cellpadding=\"0\" cellspacing=\"0\" witdh=\"100%\" height=\"1\">\n";
        $script .= "\t\t<tr>\n";
        $script .= "\t\t\t<td align=\"center\" valign=\"top\" bgcolor=\"<?php echo \$panel_colors[\"back_color\"]?>\">\n";
        $script .= "\t\t\t\t<table>\n";
        $inputs = "";
        foreach ($data as $def) {
            $def = json_decode($def, true);
            $def = (object) $def;

            if ($def->class == 'ref') {

                $ref = (object) $def->references[0];

                $options = "\t\t\t\t\t\t<?php   \$sql=\"select {$ref->keyfield}, {$ref->valuefield} from {$ref->table} order by {$ref->valuefield}\";\n";
                $options .= "\t\t\t\t\t\t\$options = \$datacontrols->createOptionsFromQuery(\$sql, 0, 1, [], \${$ref->keyfield}, false, \$cs);\n";
                $options .= "\t\t\t\t\t\techo \$options[\"list\"];?>\n";
                $script .= "\t\t\t\t<tr>\n" .
                    "\t\t\t\t\t<td>$def->fieldname</td>\n" .
                    "\t\t\t\t\t<td>\n" .
                    "\t\t\t\t\t\t<select name=\"{$ref->keyfield}\">\n" .
                    "$options" .
                    "\t\t\t\t\t\t</select>\n" .
                    "\t\t\t\t\t</td>\n" .
                    "\t\t\t\t</tr>";
            }

            if ($def->class == 'key') {
                $script .= "\t\t\t\t<tr>\n";
                $script .= "\t\t\t\t\t<td>$def->fieldname</td>\n";
                $script .= "\t\t\t\t\t<td>\n";
                $script .= "\t\t\t\t\t\t<?php echo $$def->fieldname?>\n";
                $script .= "\t\t\t\t\t</td>\n";
                $script .= "\t\t\t\t</tr>";
            }
            if ($def->class == 'field') {
                if ($def->phptype == "date" || $def->phptype == "datetime" || $def->phptype == "time") {
                    $script .= "\t\t\t\t<tr>\n";
                    $script .= "\t\t\t\t\t<td>$def->fieldname</td>\n";
                    $script .= "\t\t\t\t\t<td>\n";
                    $script .= "\t\t\t\t\t\t<input type=\"text\" name=\"$def->fieldname\" size=\"$def->cols\" value=\"<?php echo (empty($$def->fieldname)) ? date(\"1970-01-01\") : $$def->fieldname; ?>\" >\n";
                    $script .= "\t\t\t\t\t</td>\n";
                    $script .= "\t\t\t\t</tr>";
                    $script .= "\t\t\t\t<tr>\n";
                } elseif ($def->phptype == "blob" || ($def->phptype == "string" && $def->fieldsize > 80)) {
                    $script .= "\t\t\t\t<tr>\n";
                    $script .= "\t\t\t\t\t<td>$fieldname</td>\n";
                    $script .= "\t\t\t\t\t<td>\n";
                    $script .= "\t\t\t\t\t\t<textarea name=\"$def->fieldname\" cols=\"80\" rows=\"$def->lines\"><?php echo $$def->fieldname?></textarea>\n";
                    $script .= "\t\t\t\t\t</td>\n";
                    $script .= "\t\t\t\t</tr>";
                } else {
                    $script .= "\t\t\t\t<tr>\n";
                    $script .= "\t\t\t\t\t<td>$def->fieldname</td>\n";
                    $script .= "\t\t\t\t\t<td>\n";
                    $script .= "\t\t\t\t\t\t<input type=\"text\" name=\"$def->fieldname\" size=\"$def->cols\" value=\"<?php echo $$def->fieldname?>\">\n";
                    $script .= "\t\t\t\t\t</td>\n";
                    $script .= "\t\t\t\t</tr>";
                }
            }

        }
        $script .= $inputs;

        $script .= "\t\t\t\t\t<tr>\n";
        $script .= "\t\t\t\t\t\t<td align=\"center\" colspan=\"2\">\n";
        $script .= "\t\t\t\t\t\t\t<input type=\"submit\" name=\"action\" value=\"<?php echo \$action?>\">\n";
        $script .= "\t\t\t\t\t\t\t<?php   if(\$action!=\"Ajouter\") { ?>\n";
        $script .= "\t\t\t\t\t\t\t\t<input type=\"submit\" name=\"action\" value=\"Supprimer\">\n";
        $script .= "\t\t\t\t\t\t\t<?php   } ?>\n";
        $script .= "\t\t\t\t\t\t\t<input type=\"reset\" name=\"action\" value=\"Annuler\">\n";
        $script .= "\t\t\t\t\t\t\t<input type=\"submit\" name=\"action\" value=\"Retour\">\n";
        $script .= "\t\t\t\t\t\t</td>\n";
        $script .= "\t\t\t\t\t</tr>\n";
        $script .= "\t\t\t\t</table>\n";
        $script .= "\t\t\t</td>\n";
        $script .= "\t\t</tr>\n";
        $script .= "\t</table>\n";
        $script .= "</form>\n";
        $script .= "<?php   \t} ?>\n";
        $script .= "</center>\n";

        return $script;
    }
}
