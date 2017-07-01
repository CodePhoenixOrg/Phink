<?php
/*
 * Copyright (C) 2016 David Blanchard
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
 
 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phink\Utils;

class TDateUtils
{
    
    public static function frenchToDbFormat($dateFrench)
    {
        if(PHP_OS == 'Linux') {
            $date = explode('/', $dateFrench);
            $result = implode('/', array($date[1], $date[0], $date[2]));
        } else {
            $result = $dateFrench;
        }

        return $result;
    }

    public static function dbToFrenchFormat($dateSql)
    {
        $dateSql = substr($dateSql, 0, 10);
        $date = explode('-', $dateSql);
        $result = implode('/', array($date[2], $date[1], $date[0]));
        
        return $result;
    }

    public static function dbToDbFormat($dateSql)
    {
        $dateFrench = self::dbToFrenchFormat($dateSql);
        $result = self::frenchToDbFormat($dateFrench);
        
        return $result;
    }

    // Une cha�ne de la date pass�e � $timestamp au $format d�sir�
    public static function makeLocaleTime ($timestamp = 0, $format = '%A %e %B %Y') 
    { //, $locale = 'C'
        $result = '';
        if($timestamp == 0) {
            $timestamp = mktime(0,0,0, date("n"), date("d"), date("Y"));
        }

        $savelocale = setlocale(LC_TIME, 0);
        setlocale (LC_TIME, 'fr_FR.UTF-8','fra'); 
        $result = strftime($format, $timestamp);
        setlocale(LC_TIME, $savelocale);

        return $result;

    }

}
