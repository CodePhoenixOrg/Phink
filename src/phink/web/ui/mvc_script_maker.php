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

class TMvcScriptMaker extends TObject
{
    public function __construct()
    {}

    public function makeCode(
        $conf,
        $table = "",
        $page_id = 0,
        $data
    ): string {

        $classname = \ucfirst($table);
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

        $protectedArray = array_map(function ($fieldname) {
            return "$$fieldname";
        }, $A_sqlFields);
        $protecteds = join(', ', $protectedArray) . ';';

        $blankValuesArray = array_map(function ($fieldname) {
            return "\t\t\t\t\$this->$fieldname = '';";
        }, $A_sqlFields);
        $blankValues = join("\n", $blankValuesArray);

        $selectValuesArray = array_map(function ($fieldname) {
            return "\t\t\t\t\$this->$fieldname = \$rows['$fieldname'];";
        }, $A_sqlFields);
        $selectValues = join("\n", $selectValuesArray);

        $insertFilterPostArray = array_map(function ($fieldname) {
            return "\t\t\t\t\$this->$fieldname = filterPOST['$fieldname'];";
        }, $A_sqlFields);
        $insertFilterPost = join("\n", $insertFilterPostArray);

        $insertFieldsArray = array_map(function ($fieldname) {
            return "\t\t\t\t\t$$fieldname";
        }, $A_sqlFields);
        $insertFields = join(',' . "\n", $insertFieldsArray);

        $insertValuesArray = array_map(function ($fieldname) {
            return "\t\t\t\t\t:$fieldname";
        }, $A_sqlFields);
        $insertValues = join(',' . "\n", $insertValuesArray);

        $updateFilterPostArray = array_map(function ($fieldname) {
            return "\t\t\t\t\$this->$fieldname = filterPOST['$fieldname'];";
        }, $A_sqlFields);
        $updateFilterPost = join("\n", $updateFilterPostArray);

        $insertParamsArray = [];
        $prepargs = [];
        for ($i = 1; $i < count($A_sqlFields); $i++) {
            $fieldname = $A_sqlFields[$i];
            $fieldtype = $A_phpTypes[$i];
            $insertParamsArray[$i] = "\t\t\t\t\t$fieldname";
            $prepargs[] = "':$fieldname' => \$this->$fieldname";
        }
        $prepareInsert = '[' . implode(', ', $prepargs) . ']';
        $insertParams = join("\n", $insertParamsArray);

        $updateParamsArray = [];
        $prepargs = [];
        $prepareUpdate = [];
        for ($i = 1; $i < count($A_sqlFields); $i++) {
            $fieldname = $A_sqlFields[$i];
            $updateParamsArray[$i] = "\t\t\t\t\t$fieldname = :$fieldname";
            $prepargs[] = "':$fieldname' => \$this->$fieldname";
        }
        $prepareUpdate = '[' . implode(', ', $prepargs) . ']';
        $updateParams = join("\n", $updateParamsArray);

        $script = <<< SCRIPT
        namespace Phink\Apps\Admin;

        use PDO;
        use Phink\Data\Client\PDO\TPdoConnection;
        use Phink\MVC\TPartialController;
        use Phink\Registry\TRegistry;
        use Puzzle\Data\Controls as DataControls;
        use Puzzle\Menus;

        class $classname extends TPartialController
        {

            // tools
            protected \$id, \$cs, \$datacontrols, \$conf, \$lang, \$db_prefix, \$query,
                \$page_colors, \$grid_colors, \$panel_colors, \$action;

            // view fields
            protected $protecteds

            public function beforeBinding(): void
            {
                \$this->lang = TRegistry::ini('application', 'lang');
                \$this->db_prefix = TRegistry::ini('data', 'db_prefix');
                \$this->conf = TRegistry::ini('data', 'conf');
                \$this->datacontrols = new DataControls(\$this->lang, \$this->db_prefix);
                \$this->menus = new Menus(\$this->lang, \$this->db_prefix);
                \$this->page_colors = (object)TRegistry::ini('page_colors');
                \$this->grid_colors = (object)TRegistry::ini('grid_colors');
                \$this->panel_colors = (object)TRegistry::ini('panel_colors');

                \$this->cs = TPdoConnection::opener('niduslite_conf');
                \$this->query = getArgument('query', 'SELECT');
                \$event = getArgument('event', 'onLoad');
                \$this->action = getArgument('action', 'Ajouter');
                \$this->id = getArgument('id', -1);
                \$fieldname = getArgument('$indexfield');
                if(\$event === 'onLoad' && \$this->query === 'ACTION') {
                    switch (\$this->action) {
                    case 'Ajouter':
        $blankValues
                        break;
                    case 'Modifier':
                        \$sql="select * from $table where $indexfield='\$this->$indexfield';";
                        \$stmt = \$this->cs->query(\$sql);
                        \$rows = \$stmt->fetch(PDO::FETCH_ASSOC);
        $selectValues;
                    break;
                    }
                } else if(\$event === 'onRun' && \$this->query === 'ACTION') {
                    switch (\$this->action) {
                    case 'Ajouter':
        $insertFilterPost;
                        \$sql = <<<SQL
                        insert into $table (
        $insertFields
                        ) values (
        $insertValues
                        )
                        SQL;
                       \$stmt = \$this->cs->query(\$sql, $prepareInsert);
                    break;
                    case 'Modifier':
        $updateFilterPost
                        \$sql=<<<SQL
                        update $table set
        $updateParams
                        where $indexfield = '\$this->$indexfield';
                        SQL;
                        \$stmt = \$this->cs->query(\$sql, $prepareUpdate);
                    break;
                    case 'Supprimer':
                        \$sql = "delete from $table where $indexfield='\$this->$indexfield'";
                        \$stmt = \$this->cs->query(\$sql);
                    break;
                    }
                    \$this->query='SELECT';
                }
            }
        }
        SCRIPT;

        $script = str_replace("\t", "    ", $script);

        return '<?php' . "\n" . $script;
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
        $page_filename = "page.html";

        $script = <<< SCRIPT
        \$pc = getArgument("pc");
        \$sr = getArgument("sr");
        \$curl_pager = "";
        \$dialog = "";
        if(isset(\$pc)) \$curl_pager="&pc=\$pc";
        if(isset(\$sr)) \$curl_pager.="&sr=\$sr";
        if(\$this->query === "SELECT") {
            \$sql = "select $indexfield, $secondfield from $table order by $indexfield";
            \$dbgrid = \$this->datacontrols->createPagerDbGrid('$table', \$sql, \$id, "page.html", "&query=ACTION\$curl_pager", "", true, true, \$dialog, [0, 400], 15, \$this->grid_colors, \$this->cs);
            echo "<br>".\$dbgrid;
        } elseif(\$this->query === "ACTION") {
        ?>
        <form method="POST" name="$formname" action="page.html?id=$page_id&lg=fr">
        <input type="hidden" name="query" value="ACTION">
        <input type="hidden" name="event" value="onRun">
        <input type="hidden" name="pc" value="<?php echo \$pc?>">
        <input type="hidden" name="sr" value="<?php echo \$sr?>">
        <input type="hidden" name="$indexfield" value="<?php echo \$this->$indexfield?>">
        <table border="1" bordercolor="<?php echo \$this->panel_colors->border_color?>" cellpadding="0" cellspacing="0" witdh="100%" height="1">
        <tr>
            <td align="center" valign="top" bgcolor="<?php echo \$this->panel_colors->back_color?>">
                <table>

        SCRIPT;
        foreach ($data as $def) {
            $def = json_decode($def, true);
            $def = (object) $def;

            if ($def->class == 'ref') {

                $ref = (object) $def->references[0];
                $script .= <<< SCRIPT
                            <?php
                            \$sql="select {$ref->keyfield}, {$ref->valuefield} from {$ref->table} order by {$ref->valuefield}";
                            \$options = \$this->datacontrols->createOptionsFromQuery(\$sql, 0, 1, [], \$this->{$ref->keyfield}, false, \$this->cs);
                            ?>
                            <tr>
                                <td>$def->fieldname</td>
                                <td>
                                    <select name="{$ref->keyfield}">
                                    <?php echo \$options["list"]; ?>
                                    </select>
                                </td>
                            </tr>

                SCRIPT;
            }

            if ($def->class == 'key') {
                $script .= <<< SCRIPT
                            <tr>
                                <td>$def->fieldname</td>
                                <td>
                                    <?php echo \$this->$def->fieldname?>
                                </td>
                            </tr>

                SCRIPT;
            }
            if ($def->class == 'field') {
                if ($def->phptype == "date" || $def->phptype == "datetime" || $def->phptype == "time") {
                    $script .= <<< SCRIPT
                                <tr>
                                    <td>$def->fieldname</td>
                                    <td>
                                        <input type="text" name="$def->fieldname" size="$def->cols" value="<?php echo (empty(\$this->{$def->fieldname})) ? date("1970-01-01") : \$this->{$def->fieldname}; ?>" >
                                    </td>
                                </tr>

                    SCRIPT;
                } elseif ($def->phptype == "blob" || ($def->phptype == "string" && $def->fieldsize > 80)) {
                    $script .= <<< SCRIPT
                                <tr>
                                    <td>$def->fieldname</td>
                                    <td>
                                        <textarea name="$def->fieldname" cols="80" rows="$def->lines"><?php echo \$this->$def->fieldname?></textarea>
                                    </td>
                                </tr>

                    SCRIPT;
                } else {
                    $script .= <<< SCRIPT
                                <tr>
                                    <td>$def->fieldname</td>
                                    <td>
                                        <input type="text" name="$def->fieldname" size="$def->cols" value="<?php echo \$this->$def->fieldname?>">
                                    </td>
                                </tr>

                    SCRIPT;
                }
            }

        }

        $script .= <<<SCRIPT
                    <tr>
                        <td align="center" colspan="2">
                            <input type="submit" name="action" value="<?php echo \$this->action?>">
                            <?php   if(\$this->action!="Ajouter") { ?>
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
        <?php
        }
        ?>
        SCRIPT;

        $script = str_replace("\t", "    ", $script);

        return '<?php' . "\n" . $script;
    }
}
