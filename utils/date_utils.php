<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Phoenix\Utils;

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

    // Une chaîne de la date passée à $timestamp au $format désiré
    public static function makeLocaleTime ($timestamp = 0, $format = '%A %e %B %Y') { //, $locale = 'C'
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
