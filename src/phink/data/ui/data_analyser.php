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
namespace Phink\Data;

use Phink\Data\ISqlConnection;

class TAnalyzer
{
    public function searchReferences(string $database, string $table, ISqlConnection $cs)
    {
        $L_formFields = '';
        $A_formFields = [];
        $L_fieldDefs = '';
        $A_fieldDefs = [];
        $A_data = [];
        $L_tables = '';
        $A_tables = [];
        $L_fields = '';
        $A_fields = [];

        $i = 0;
        $sql = "select * from $table limit 0,1;";
        $stmt = $cs->query($sql);

        // $rows=$stmt->fetch();
        // $k=sizeof($rows)/2;

        $k = $stmt->getFieldCount();

        while ($i < $k) {
            $fieldname = $stmt->getFieldName($i);
            $fieldtype = $stmt->getFieldType($i);
            $fieldsize = $stmt->getFieldLen($i);
            $phptype = $stmt->typeNumToPhp($fieldtype);
            $fieldtype = $stmt->typeNumToName($fieldtype);
            $htmltype = '';
            $class = '';
            $references = [];

            $cols = ($fieldsize > 80) ? 80 : $fieldsize;
            $lines = ($phptype == "blob" || ($phptype == "string" && $fieldsize > 80)) ? ceil($fieldsize / 80) : 1;

            $lines = ($lines > 8) ? 8 : $lines;

            if ($i == 0) {
                $indexfield = $fieldname;
                $p = strpos($indexfield, "_");
                $table_prefix = substr($indexfield, 0, $p);
            }
            $p = strpos($fieldname, "_");
            $current_prefix = substr($fieldname, 0, $p);
            if ($table_prefix != $current_prefix) {
                $fieldFound = false;
                $EOL = $fieldFound;
                while (!$fieldFound && !$EOL) {
                    $tab_res = $cs->showTables();
                    $fieldTable = '';
                    while (($tables = $tab_res->fetch()) && ($fieldTable == "")) {
                        $currentTable = $tables[0];
                        $fld_res = $cs->showFieldsFrom($currentTable);
                        $fields = $fld_res->fetch();
                        if ($fieldname == $fields[0]) {
                            $fieldTable = $currentTable;
                            $L_tables .= "$fieldTable;";
                        }
                        $fieldFound = ($fieldname == $fields[0]); // || $fieldFound;
                    }
                    $EOL = true;
                }
                if ($fieldFound) {
                    $fld_res = $cs->showFieldsFrom($fieldTable);
                    $j = 0;
                    while (($fields = $fld_res->fetch()) && $j < 2) {
                        if ($j == 0) {
                            $foreign_idfield = $fields[0];
                        }
                        if ($j == 1) {
                            $foreign_firstfield = $fields[0];
                        }
                        $j++;
                    }
                    $L_fields .= "$foreign_idfield;";
                    $htmltype = 'select';

                    $lines = 8;
                    array_push($references, ['type' => $phptype,
                        'keyfield' => $foreign_idfield,
                        'valuefield' => $foreign_firstfield,
                        'table' => $currentTable,
                    ]);
                    $class = 'ref';
                }
            } else {
                $L_fields .= "$fieldname;";
                if ($fieldname == $indexfield) {
                    $class = 'key';
                    $htmltype = 'label';
                } else {
                    $class = 'field';
                    if ($phptype == "date" || $phptype == "datetime" || $phptype == "time") {
                        $htmltype = 'text';
                    } elseif ($phptype == "blob" || ($phptype == "string" && $fieldsize > 80)) {
                        $htmltype = 'textarea';
                    } else {
                        $htmltype = 'text';
                    }
                }
            }
            $L_fieldDefs .= "$fieldname,$fieldsize,$fieldtype,$phptype,$htmltype,$cols,$lines;";

            array_push($A_data, ['class' => $class,
                'fieldname' => $fieldname,
                'fieldsize' => $fieldsize,
                'fieldtype' => $fieldtype,
                'phptype' => $phptype,
                'htmltype' => $htmltype,
                'cols' => $cols,
                'lines' => $lines,
                'references' => $references,
            ]);
            $i++;
        }

        $A_data = array_map(function ($arr) {
            return \json_encode($arr);
        }, $A_data);

        return $A_data;
    }
}
