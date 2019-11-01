<?php
/*
 * Copyright (C) 2019 David Blanchard
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
namespace Phink\Web;

//require_once 'phink/core/application.php';
//require_once 'phink/utils/date_utils.php';
//require_once 'phink/core/log.php';

use Phink;
use Phink\Utils\TDateUtils;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Log\TLog;

class THtmlObjects
{
    
    public static function createPdfFromHtml($html)
    {
        //require_once('html2pdf/html2pdf.class.php');

        $html2pdf = new HTML2PDF('P','A4','fr');
        $html2pdf->WriteHTML($html, isset($_GET['vuehtml']));
        $resultPDF = $html2pdf->Output('', true);

        return $resultPDF;
    }
        

    public static function createListFromDictionary(array $dictionary, $listType, $link, $colNumber, $css)
    {
        $result = (string)'';

        if(!in_array($listType, array('ul', 'ol'))) return $result;

        $class = ($css != '') ? " class='$css'" : '';

        $length = count($dictionary);

        $colCount = abs($length / $colNumber);

        $result = "<$listType$class>" . PHP_EOL;
        $i = 0;
        foreach($dictionary as  $key=>$value) {
            if($i == $colNumber) {
                $result .= "</$listType>" . PHP_EOL . "<$listType$class>" . PHP_EOL;
                $i = 0;
            }
            
            if(substr($link, 0, 10) == 'javascript' && strstr($link, 'this')) {
                $onclick = " onclick='$link;'";
                $onclick = str_replace('this', $key, $onclick);
                $onclick = str_replace('javascript:', '', $onclick);

                $url = 'javascript:void(0)';
            } else {
                $url="$link?$key=" . rawurlencode($value);

            }

            $value = htmlspecialchars($value, ENT_QUOTES);
            $result .= "\t<li><a href='$url'$onclick>$value</a></li>";

            $i++;

        }

        $result .= "</$listType>" . PHP_EOL;


        return $result;
    }

    public static function createCheckListFromDictionary(array $dictionary, $listType, $checkableAction, $editableAction, $colNumber, $css)
    {

        $result = (string)'';

        if(!in_array($listType, array('ul', 'ol'))) return $result;

        $i = 0;
        $cssArray = explode(',', $css);
        $class = (isset($cssArray[0])) ? ' class="' . $cssArray[0] . '"' : '';

        $result = '<' . $listType . $class . '>' . PHP_EOL;

        $count = count($dictionary);
        foreach($dictionary as  $key=>$subArray) {
            if($i == $colNumber) {
                $result .= '</' . $listType . '>' . PHP_EOL . '<' . $listType . $class . '>' . PHP_EOL;
                $i = 0;
            }
            
            $result .= "\t" . '<li><table ' . $class . '><tr>';
            $data = '';
            $indeterminate = '';
            $checked = '';
            $j = 0;
            $onCheckClick = '';
            $checkValue = '';
            $labelValue = '';
            
            foreach($subArray as $name=>$value) {
            
                if($name == 'data') {
                    $data = $value;
                    if($data == -1 || $data == 0) {
                        $checked = '';
                        $indeterminate = '';
                    } elseif ($data == 1) {
                        $checked = 'checked="checked"';
                        $indeterminate = '';
                    } elseif ($data == 2) {
                        $checked = 'checked="checked"';
                        $indeterminate = 'indeterminate="indeterminate"';
                    }
                    continue;
                } elseif ($name == 'value') {
                    if(substr($checkableAction, 0, 10) == 'javascript' && strstr($checkableAction, 'this')) {
                        $onCheckClick = ' onclick="' . $checkableAction . ';"';
                        $onCheckClick = str_replace('this', "'$name+$key'", $onCheckClick);
                        $onCheckClick = str_replace('javascript:', '', $onCheckClick);

                        $url = 'javascript:void(0)';
                    } else {
                        $checkUrl="$checkableAction?$name$key=" . rawurlencode($value);

                    }
                    $checkValue = $value;
                    continue;
                }
                $labelValue = $value;
                
                $onEditableClick = '';
                
                if(substr($editableAction, 0, 10) == 'javascript' && strstr($editableAction, 'this')) {
                    $onEditableClick = ' onclick="' . $editableAction . ';"';
                    $onEditableClick = str_replace('this', "'$name+$key'", $onEditableClick);
                    $onEditableClick = str_replace('javascript:', '', $onEditableClick);

                    $onEditableClick = (isset($cssArray[$j+1])) ? str_replace(')', ", '" . $cssArray[$j+1] . "')", $onEditableClick) : '';
                    $url = 'javascript:void(0)';
                } else {
                    $editableUrl="$editableAction?$name$key=" . rawurlencode($labelValue);
                }
                
                $labelValue = htmlspecialchars($labelValue, ENT_QUOTES, 'ISO-8859-15');

                $result .= "\t\t" . '<td ' . ((isset($cssArray[$j+1])) ? ' class="' . $cssArray[$j+1] .'"' : '') . '"><div class="checkbox ui-selectee">
                            <input type="hidden" id="hid' . $name . $key . '" value="' . $labelValue . '">
                            <input type="checkbox" id="chk' . $name . $key . '" value="' . $checkValue . '"' . $onCheckClick . ' ' . $checked . ' ' . $indeterminate . ' style="visibility:' . (($j == 0) ? 'visible' : 'hidden') . '">
                            <span id="spn' . $name . $key . '" data-toggle="tooltip" data-original-title="' . $labelValue . '" class="control-label"' . $onEditableClick . '>' . $labelValue . '</span>
                        </div></td>';
                
                $j++;
            }
            $result .= "\t". '</tr></table></li>';
            $i++;

        }

        $result .= '</' . $listType . '>' . PHP_EOL;


        return $result;
    }

    public static function createSelectorOptionsFromDictionary(array $dictionary, $default = '')
    {

            $list="";
            $options="";
            $default=trim($default);

            if(!array_key_exists($default, $dictionary)) {
                $keyValue = each($dictionary);
                $default = $keyValue['key'];

                TLog::debug($default, __FILE__, __LINE__);

                reset($dictionary);
            }

            //$list.="<OPTION SELECTED VALUE=\"0\">(Aucun)</OPTION>\n";

            foreach ($dictionary as $value=>$option) {

                if($value==$default) {
                    $list.="<OPTION SELECTED VALUE=\"$default\">$option</OPTION>\n";
                } else {
                    $list.="<OPTION VALUE=\"$value\">$option</OPTION>\n";
                }
            }

            return array('options' => $list, 'default' => $default);
    }

    public function createDataGrid(TPdoConnection $connection, $name="", $sql="", $rowId=0, $pageLink="",  $curlRows="", $curlPager = '', $canFilter = false, $canAdd = false, $dialog = '', $colwidths = array(), $colors = array(), $offset = null, $step = 0) { 
    /*
            Desciption des paramètres :

            $name="",
            $sql="", 
            $row_id=0, 
            $pageLink="", 
            $curlRows="", 
            $curlPager="",
            $filterfield,
            $canAdd,
            $dialog, 
            $colwidths,
            $step,
            $colors, 
            $cs

            Dessine un tableau dont les informations sont le result d'une requête SQL passée à $sql. Les parametres $pageLink et $image_link sont utilisés pour la premiere colonne. Si $image_link est vide, la valeur affichée est celle du champ d'index.
    */
        
//            $connectionType = $connection->getClassName();
//            
//            $connection = new $connectionType();
//            $connection->open();
                    
                    
        
            $hasPager = isset($offset) && $step > 0;
            $criterion=$_REQUEST["criterion"];

            
            //Détermine les couleurs du dbGrid
            if(!empty($colors)) { 
                    global $grid_colors;
                    $color=$grid_colors;
            }

            if(!empty($colors)) {
                    $border_color=$colors["border_color"];
                    $header_back_color=$colors["header_back_color"];
                    $even_back_color=$colors["even_back_color"];
                    $odd_back_color=$colors["odd_back_color"];
                    $header_fore_color=$colors["header_fore_color"];
                    $even_fore_color=$colors["even_fore_color"];
                    $odd_fore_color=$colors["odd_fore_color"];
                    $pager_color=$colors["pager_color"];
            } else {
                    $border_color="white";
                    $header_back_color="black";
                    $even_back_color="lightgrey";
                    $odd_back_color="grey";
                    $header_fore_color="white";
                    $even_fore_color="black";
                    $odd_fore_color="white";
                    $pager_color="white";
            }

            if(!isset($image_link)) $image_link="images/edit.png";

            $add="Ajouter";

            //validité du numéro du premier enregistrement affiché
            if($offset>0) 
                    $curlRows.="&offset=$offset";
            else
                    unset($offset);

            //validité du compteur de pages
            if($step>0)
                    $curlRows.="&count=$step";
            else
                    unset($step);

            $i=1;
//            if($canFilter) {
//                $criterions=$_REQUEST["criterions"];
//                if(is_array($criterions)) {
//                        //echo "criterions rempli<br>";
//                        foreach($criterions as $criterion) {
//                                $curlPager.="&c$i=$criterion";
//                                $i++;
//                        }
//                } else {
//                        //echo "criterions vide<br>";
//                        $criterion=$_REQUEST["c$i"];
//                        while($criterion!="") {
//                                $criterions[$i]=$criterion;
//                                $curlPager.="&c$i=$criterion";
//                                $i++;
//                                $criterion=$_REQUEST["c$i"];
//                        }
//                }
//            }
            //$curlPager.="&criterion=$criterion";

            $caption=strtoupper($name[0]).substr($name, 1, strlen($name)-1);

//            /*
//            Y a-t-il un complément d'URL en paramètre ?
//            Si oui on sépare les noms de variables de leurs valeurs
//            et on place les valeur indicant des champs de la requête dans un tableau.
//            On concatène les autres variables avec leurs valeurs.
//            */
//            if($curlRows!="") {
//                    $acompl_url=array();
//                    $vars=explode("&", $curlRows);
//                    $curl_rows2="";
//                    for($i=1; $i<count($vars); $i++) {
//                            $var=explode("=", $vars[$i]);
//                            if(substr($var[1],0,1)=="#") {
//                                    $acompl_url[$var[1]]=$var[0];
//                            } else {
//                                    $curl_rows2.="&".$var[0]."=".$var[1];
//                            }
//                    }
//            }


            if($hasPager || $canFilter) {
                /*
                Y a-t-il un complément d'URL en paramètre pour la pagination et le filtre ?
                Si oui on sépare les noms de variables de leurs valeurs
                et on place les valeur indicant des champs de la requête dans un tableau.
                On concatène les autres variables avec leurs valeurs.
                */
                if($curlPager!="") {
                        $vars=explode("&", $curlPager);
                        $hidden_fields="";
                        for($i=1; $i<count($vars); $i++) {
                                $var=explode("=", $vars[$i]);
                                $hidden_fields.="<input type='hidden' name='".$var[0]."' value='".$var[1]."'>\n";
                        }
                }
            }

            /*
            Le paramètre passé à $pageLink est un nom de champ de la reqête précdé du préfixe & ou @.
            Si le préfixe est & on agit différemment en fonction de la valeur du champ.
            Si le préfixe est @ on considère que c'est toujours une adresse web.
            */
            $is_image=false;
            $is_url=false;
            $image_field="";
            $web_field="";

            if(substr($pageLink,0,1)=="|") {
                    $image_field=substr($pageLink, 1, strlen($pageLink)-1);
                    $is_image=true;
                    $pageLink="$pageLink";
            }
            if(substr($pageLink,0,1)=="&") {
                    $web_field=substr($pageLink, 1, strlen($pageLink)-1);
                    $pageLink="$pageLink";
            } elseif(substr($pageLink,0,1)=="@") {
                    $web_field=substr($pageLink, 1, strlen($pageLink)-1);
                    $is_url=true;
                    $pageLink="$pageLink";
            }

//            if(is_array($criterions) && $canFilter) {
//                    $fields=get_fields_from_select_clause($sql);	
//                    array_unshift($criterions, "dummy");
//                    $s=sizeof($fields);
//                    for($i=1; $i<$s; $i++) {
//                            $sql=insert_like_clause($sql, $fields[$i], $criterions[$i]);
//                    }
//            }


            //echo "page_link='$pageLink'<br>";

            $pagerLink=$_SERVER['PHP_SELF'];
            //if($dialog) $pagerLink=$_SERVER['PHP_SELF'];


    
            //if($num) {


            if(substr($curlPager, 0, 10) == 'javascript') {
                $pagerLink = $curlPager;
                $curlPager = '';
            }

            $pager = '';
            if(false) { //$hasPager
                $pager_ctrl = THtmlObjects::createEnhancedPagerControl($pagerLink, $sql, $caption, $offset, $step, $curlPager);
                $sql=$pager_ctrl["sql_query"];
                $pager=$pager_ctrl["pager_ctrl"];        
            }

            $stmt = $connection->query($sql);

            $table="";
            $table.="<table id='$name' class='table table-condensed' bordercolor='$border_color' bgcolor='white'>\n".
                    "<thead>\n".
                    "<tr bgcolor='$header_back_color'>\n";

            //"<input type=\"hidden\" name=\"curl_pager\" value=\"$curlPager\">\n".
            if($canFilter) {	
                    $filters="<form method=\"post\" action=\"$pageLink?id=$pager_id&lg=$lg\" name=\"filter\">\n".
                            $hidden_fields.
                            "<tr>\n";
                    if($fields_count>1) $filters.="<th bgcolor=\"$pager_color\"><img src=\"images/filter.png\" border=\"0\"></th>";

                    $filter_button="<input type=\"submit\" name=\"filter\" value=\"Filtrer\">\n";
            } else {
                    $filters="";
                    $filter_button="";
            }

            //Les colonnes auront la largeur définie par ordre d'indexation dans le tableau $colwidth.
            //Si le nombre de largeurs définies est inférieur on aggrandi le tableau avec des valeurs à 0.
            $width_count = count($colwidths);

            $fields_count = $stmt->getFieldCount();
            
            $cols=$fields_count;
            if($width_count<$fields_count) {
                $j = $fields_count-$width_count;
                $a = array_fill($width_count, $j, 0);
                $colwidths = array_merge($colwidths, $a);
            }

            $index_fieldname=$stmt->getFieldName(0);
            
            $k=0;
            $javascript="";	
            for($j=0; $j<$fields_count; $j++) {
                    $fieldname=$stmt->getFieldName($j);
                    if($fieldname==$web_field && $is_url===false) {
                            $cols--;
                    } else {
//                            if($canFilter && $fields_count>1 && $j==0) {
//                                    //nop;
//                            } else if($canFilter) {
//                                    if($criterions[$k]=="") $criterions[$k]="*";
//                                    $filters.="<th id='crit_td$k' bgcolor='$pager_color'><input id='crit_inp$k' type='text' name='criterions[$k]' value='".$criterions[$k]."' size='10'></th>\n";
//                                    $javascript.="\tvar critinp$k=eval(document.getElementById(\"crit_inp$k\"));\n\tvar crittd$k=eval(document.getElementById(\"crit_td$k\"));\n\tcritinp$k.style.width=crittd$k.offsetWidth+\"px\";\n";
//                            }
                            $tag_width="";
                            if($colwidths[$j]!=0) $tag_width=" width='".$colwidths[$j]."'";
                            if($fields_count>1 && $j==0) $fieldname="<img src='images/edit.png'>";
                            $table.="<th align=center$tag_width><font color='$header_fore_color'><b>".strtoupper($fieldname[0]).substr($fieldname, 1, strlen($fieldname)-1)."<b></font></th>\n";
                            $k++;
                    }
            }

//            if($canFilter) $filters."</tr>\n";
            if($canFilter || $hasPager) {
                $status_bar="<tr><td bgcolor='$pager_color' colspan='$cols' align='center' valign='middle'>\n";
                $status_bar.="<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
                if($canFilter) {
                        $status_bar.="<tr><td align='center' width='20%'>$filter_button</td><td align='center' width='*'>$pager</td></tr>\n";
                } else {
                        $status_bar.="<tr><td align='center' width='100%'>$pager</td></tr>\n";
                }
                $status_bar.="</table></td></tr>\n";
            }

            $table.="</tr>\n";
            $table.="</thead>\n";
            $table.="<tbody>\n";
            $r=0;
            $i=$fields_count;
            while($row = $stmt->fetch()) {
                    $values = array_slice($row, 0);
                    $on_mouse_over="";
                    $on_mouse_out="";

                    $r1=$r/2;
                    $r2=round($r1);
                    if($r1==$r2) {
                            $back_color=$even_back_color;
                            $fore_color=$even_fore_color;
                    } else {
                            $back_color=$odd_back_color;
                            $fore_color=$odd_fore_color;
                    }

                    $index_value=$values[0];

                    $curlRows=$curl_rows2;
                    $target="";
                    $ahref="";
                    $a="";

                    if(!empty($acompl_url)) {
                            for($j=0; $j<$i; $j++) {
                                    $fieldname = $stmt->getFieldName($j);
                                    $sharpname="#".$fieldname;
                                    if(isset($acompl_url[$sharpname])) {
                                            $curlRows.="&".$acompl_url[$sharpname]."=".$values[$fieldname];
                                    }
                            }
                    }

                    $js_events="";
    //		if(is_numeric($row_id))
    //			$page_id="id=$row_id&lg=$lg";
    //		else
    //			$page_id="di=$row_id&lg=$lg";

                    if($web_field!="") {
                            $url_field=$row[$web_field];
                            if($url_field!="none" && $url_field!="") {
                                    if($is_url) {
                                            if(substr($url_field, 0, 3)=="www")
                                                    $url_field="http://".$url_field;
                                            $url=$url_field;
                                            $target=" target=\"_new\"";
                                    } else {
                                            if(substr($url_field, 0, 7)=="http://") {
                                                    $url=$url_field;
                                                    $target=" target=\"_new\"";
                                            } else {
                                                    $url="$pageLink?$page_id$curl_rows";
                                            }
                                    }
                                    $ahref="<a href='$url'$target>";
                                    $a="</a>";
                            }
                    } elseif($image_field!="") {
                            $ahref="";
                            $a="";
                    } else {
                            if(substr($pageLink, 0, 10) == 'javascript' && strstr($pageLink, 'this')) {
                                $p1 = strpos($pageLink, ',');
                                $flatParamList = '';
                                if($p1 > -1) {
                                    $p2 = strpos($pageLink, ')', $p1);

                                    $flatParamList = substr($pageLink, $p1+1, $p2-$p1-1);
                                    $paramList = explode(',', $flatParamList);
                                    $paramListOut = array();
                                    $c = count ($paramList);
                                    for ($ii = 0; $ii < $c; $ii++) {
                                        $iii = $paramList[$ii];
                                        array_push($paramListOut, '"'. $values[$iii] . '"');
                                        
                                    }
                                    $pageLink2= str_replace($flatParamList, '', $pageLink);
                                    $flatParamList = implode(',', $paramListOut);
                                    $pageLink2= str_replace(')', "$flatParamList)", $pageLink2);
                                    
                                    
                                }
                                
                                if($flatParamList == '') {
                                    $url= str_replace('this', $index_value, $pageLink2);
                                } else {
                                    $url= str_replace('this', $index_value, $pageLink2);
                                }
                                
                                
                                if($offset && $step) {
                                    $url = str_replace(')', ", $offset, $step)", $url);
                                } 
                            } else {
                                $url="$pageLink?$index_fieldname=".$index_value."&action=Modifier";

                            }


                            $ahref="<a href='$url$curl_rows'$target>";
                            $a="</a>";
                    }



                    $on_mouse_over.="setRowColor(this, hlBackColor, hlTextColor);";
                    $on_mouse_out.="setBackRowColor(this);";

                    $js_events=" onMouseOver=\"$on_mouse_over\" onMouseOut=\"$on_mouse_out\">";
                    $table.="<tr id='$name$r' bgcolor='$back_color'$js_events";
                    $url="";
                    for($j=0; $j<$i; $j++) {

                            $fieldname = $stmt->getFieldName($j);
                            if($fieldname==$web_field && $is_url===false) {
                                    //nop
                            } else {

                                    $fieldtype = $stmt->getFieldType($j);
                                    $fieldlen = $stmt->getFieldLen($j);

                                    $field=$values[$j];	

                                    if(!empty($curlRows)) $url.=$curlRows;
                                    $tag_width="";
                                    //echo "col_width[$j]=$colwidths[$j]<br>";
                                    if($colwidths[$j]!=0) $tag_width=" width='".$colwidths[$j]."'";

                                    $on_click="";
                                    if(!empty($dialog)) {
                                            $on_click=" onClick=\"".create_dialog_window($url, $dialog[0], $dialog[1])."\"";
                                            $ahref="";
                                            $a="";
                                    }
                                    if($i>1 && $j==0) {
                                            $tag_align=" align='center'";
                                            $field="<img border='0' src='$image_link' height='16' width='16'$on_click>";
                                            $table.="<td>$ahref$field$a</td>\n";

                                    } else {
                                            if(strstr($fieldtype, "datetime")) $field = TDateUtils::dbToFrenchFormat($field);
                                            $tag_align=" align='left'";
                                            if($fieldtype=="int") $tag_align=" align='right'";
                                            if($fieldlen < 5) $tag_align=" align='center'";
                                            $c=$j-1;
                                            $table.="<td$tag_align$tag_width>$ahref<font id='font_$name$r$c' color='$fore_color'><span$on_click>$field</span></font>$a</td>\n";
                                    }
                            }
                    }
                    $table.="</tr>\n";
                    $r++;
            }
            if($canAdd) {
                    $row=array();
                    $row[0]="0";
                    $row[1]="($add)";
                    for($i=2; $i < $stmt->getFieldCount(); $i++) {
                        $row[$i] = "...";
                    }

                    $r1=$r/2;
                    $r2=round($r1);
                    if($r1==$r2) {
                            $back_color=$even_back_color;
                            $fore_color=$even_fore_color;
                    } else {
                            $back_color=$odd_back_color;
                            $fore_color=$odd_fore_color;
                    }

                    $index_value=$row[0];

                    $curlRows=$curl_rows2;
                    $target="";
                    $ahref="";
                    $a="";

                    if(is_numeric($row_id))
                            $page_id="id=$row_id&lg=$lg&action=$add";
                    else
                            $page_id="di=$row_id&lg=$lg&action=$add";

                    $url="$pageLink?$page_id$curl_rows";
                    $ahref="<a href='$url$curl_rows'$target>";
                    $a="</a>";

                    $table.="<tr id='$name$r' bgcolor='$back_color' onMouseOver=\"setRowColor(this, hlBackColor, hlTextColor);\" onMouseOut=\"setBackRowColor(this);\">";
                    for($j=0; $j<$i; $j++) {

                            $fieldname = $stmt->getFieldName($j);
                            
                            if($fieldname==$web_field && $is_url===false) {
                                    if($row[$j]=="(Ajouter)" && $row[$j+1]=="...")
                                            $row[$j+1]=$row[$j];
                            } else {
                                    $field=$row[$j];	

                                    if(!empty($curlRows)) $url.=$curlRows;
                                    $tag_width="";
                                    if($colwidths[$j]!=0) $tag_width=" width='".$colwidths[$j]."'";

                                    if($i>1 && $j==0) {
                                            $tag_align=" align='center'";
                                            $field="<img border='0' src='$image_link' height='16' width='16'>";
                                            $table.="<td>$ahref$field$a</td>\n";

                                    } else {
                                            $tag_align=" align='left'";
                                            $c=$j-1;
                                            $table.="<td$tag_align$tag_width>$ahref<font id='font_$name$r$c' color='$fore_color'>$field</font>$a</td>\n";
                                    }
                            }
                    }
                    $table.="</tr>\n";
            }
            if($step>$r) {
                    $l=$step-$r;
                    for($k=0; $k<$l; $k++) {
                            $table.="<tr bgcolor='$pager_color'>\n";
                            $table.="<td><img border='0' src='images/edit_bw.png'></td>";
                            for($j=1; $j<$i; $j++) {
                                    $fieldname = $stmt->getFieldName($j);
                                    if($fieldname==$web_field && $is_url===false) {
                                            //nop
                                    } else {
                                            $table.="<td>&nbsp;</td>";
                                    }
                            }
                            $table.="\n</tr>\n";
                    }
            }
            $table.=$filters;
            $table.=$status_bar;
            if($canFilter) $table.="</form>\n";
            $table.="</tbody>\n";
            $table.="</table>\n";
            if($javascript) $_SESSION["javascript"].=$javascript;

            
            return $table;
    }


    public static function createEnhancedPagerControl($page_link="", $sql_query="", $caption, $offset=0, $step=5, $comp_url)
    {
    /*
            Desciption des parametres :

            $sql : requête SQL complète ou uniquement la clause ORDER BY de la requête SQL créée automatiquement à partir de $table
            $id : index de menu de la page qui utilise la fonction
            $lg : langue choisie pour afficher la page qui utilise la fonction
            $caption : étiquette du pager qui caractérise le type d'élément paginé (news, article, etc.)
            $offset (starting at record) : numéro d'enregistrement où commence la pagination
            $step : nombre d'éléments affichés par page
            $count (pager count) : nombre d'éléments à paginer
            $comp_url : utile pour les valeur de formulaire à reporter sur la pagination

    */
            global $count, $lg;
            $img = 'images';
            $sql_query=trim($sql_query); //strtolower
            $p=strpos($sql_query, " ");
            $sql_clause=substr($sql_query, 0, $p);

            if(isset($comp_url)) {
                $comp_url = '&' . $comp_url;
            }
            $javasctiptFunction = '';
            if(substr($page_link, 0, 10) == 'javascript' ) {
                $javasctiptFunction = $page_link;
                if($offset && $step) {
                    $page_uri = str_replace(')', ", $offset, $step)", $javasctiptFunction);
                } 
            }

            $result = TConnector::queryLog($sql_query, __FILE__, __LINE__);
            $count = TConnector::numRows($result);
            if(!isset($step)) $step=$count;

            $min_sr=0;
            $max_sr=round($count/$step)*$step;
            if($max_sr>=$count) $max_sr-=$step;

            $pages_num=$max_sr/$step+1;
            $current_page=$offset/$step+1;
            $previous=$offset-$step;
            if($previous<=0) $previous=0;
            $next=$offset+$step;
            if($next>=$count) $next=$max_sr;

            $on_click="";

            if(!isset($page_link)) {
                    $page_link=$_SERVER['PHP_SELF'];
            } 
            /*elseif($page_link!="") {
                    if(substr($page_link, 0, 1)=="/") {
                            $page_uri=get_http_root().$page_link;
                    } else {
                            $page_uri=$page_link;
                    }
            }*/
    //	if(is_numeric($id))
    //		$page_uri="$page_link?id=$id&lg=$lg";
    //	else
    //		$page_uri="$page_link?di=$id&lg=$lg";

            $page_uri = $page_link;

                            //"<tr><td height='1' width='100%' bgcolor='black'></td></tr>\n".
            if($javasctiptFunction == '')
    {
                $page_uri = (strpos($page_uri, "?")>0) ? $page_uri.="&" : $page_uri.="?";
                $pager_ctrl =   "<table border='0' cellspacing='0' cellpadding='0'>\n".
                                "<tr>\n".
                                        "<td width='100%' align='center' valign='bottom'>\n".
                                                "<a href='".$page_uri."offset=$min_sr&count=$count'>\n".
                                                "<img src='$img/scroll/leftLimit_0.gif' valign='top' border='0'></a>\n".
                                                "<a href='".$page_uri."offset=$previous&count=$count$comp_url'>\n".
                                                "<img src='$img/scroll/fastLeft_0.gif' valign='top' border='0'></a>\n".
                                                "$count $caption - Page $current_page/$pages_num\n".
                                                "<a href='".$page_uri."offset=$next&count=$count$comp_url'>\n".
                                                "<img src='$img/scroll/fastRight_0.gif' valign='top' border='0'></a>\n".
                                                "<a href='".$page_uri."offset=$max_sr&count=$count$comp_url'>\n".
                                                "<img src='$img/scroll/rightLimit_0.gif' valign='top' border='0'></a>\n".
                                        "</td>\n".
                                "</tr>\n".
                                "</table>\n";
            } else {
                $javasctiptFunction = substr($javasctiptFunction, 0, -1);
                $pager_ctrl =   "<table border='0' cellspacing='0' cellpadding='0'>\n".
                                "<tr>\n".
                                        "<td width='100%' align='center' valign='bottom'>\n".
                                                "<a href='".$javasctiptFunction.",$min_sr,$step)'>\n".
                                                "<img src='$img/scroll/leftLimit_0.gif' valign='top' border='0'></a>\n".
                                                "<a href='".$javasctiptFunction.",$previous,$step)'>\n".
                                                "<img src='$img/scroll/fastLeft_0.gif' valign='top' border='0'></a>\n".
                                                "$count $caption - Page $current_page/$pages_num\n".
                                                "<a href='".$javasctiptFunction.",$next,$step)'>\n".
                                                "<img src='$img/scroll/fastRight_0.gif' valign='top' border='0'></a>\n".
                                                "<a href='".$javasctiptFunction.",$max_sr,$step)'>\n".
                                                "<img src='$img/scroll/rightLimit_0.gif' valign='top' border='0'></a>\n".
                                        "</td>\n".
                                "</tr>\n".
                                "</table>\n";

            }


            $sql_query = TConnector::formatLimitedQyery($sql_query, $offset, $step);

    //	if($sql_clause=="select") 
    //		$sql_query = $sql_query." limit $offset,$step";
    //	else if($sql_clause=="show")
    //		$sql_query = $sql_query;
    //	else if($sql_clause=="order")
    //		$sql_query = "select * from $table $sql_query limit $offset,$step";
    //	else
    //		$sql_query = "select * from $table limit $offset,$step";

            $pager=array("pager_ctrl"=>$pager_ctrl, "sql_query"=>$sql_query);

            return $pager;
    }
}
