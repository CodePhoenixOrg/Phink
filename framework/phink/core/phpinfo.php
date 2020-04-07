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
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Phink\Core;

use stdClass;

final class PhpInfo
{
    public static function getSection(int $section, bool $asObject = false)
    {
        $root = [];

        if ($asObject) {
            $root = new stdClass;
        }


        ob_start();
        phpinfo($section);
        $lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));

        $cat = 'general';
        foreach ($lines as $line) {
            // new cat?

            if (false !== preg_match("~<h2>(.*)</h2>~", $line, $title) ? (isset($title[1]) ? $cat = $title[1] : false) : false) {

                $cat = self::_formatKey($cat);
                if ($asObject) {
                    $root->$cat = new stdClass;
                }
            }
            if (preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
                $key = self::_formatKey($val[1]);
                $value = self::_formatValue($val[2]);
                if ($cat !== 'php_variables') {

                    if (!$asObject) {
                        $root[$cat][$key] = $value;
                    }
                    if ($asObject) {
                        $root->$cat->$key = $value;
                    }
                }
                if ($cat == 'php_variables') {
                    if (preg_match('~\$_(server|cookie)\[\'([a-z_]*)\'\]~', $key, $val)) {
                        $subcat = $val[1];
                        $subkey = $val[2];
                        if (!$asObject) {
                            $root[$cat][$subcat][$subkey] = $value;
                        }
                        if ($asObject) {
                            if (!property_exists($root->$cat, $subcat)) {
                                $root->$cat->$subcat = new stdClass;
                            }
                            $root->$cat->$subcat->$subkey = $value;
                        }
                    }
                }
            } elseif (preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
                $key = self::_formatKey($val[1]);
                $local = self::_formatValue($val[2]);
                $master = self::_formatValue($val[3]);
                if (!$asObject) {
                    $root[$cat][$key] = ['local' => $local, 'master' => $master];
                }
                if ($asObject) {
                    $root->$cat->$key = new stdClass;
                    $root->$cat->$key->local = $local;
                    $root->$cat->$key->master = $master;
                }
            }
        }
        return $root;
    }

    public static function displaySection(int $infoSection, bool $asJSON = false): void
    {
        $array = self::getSection($infoSection);

        if ($asJSON) {
            echo '<pre>' . PHP_EOL;
            echo json_encode($array, JSON_PRETTY_PRINT);
            echo '</pre>' . PHP_EOL;

            return;
        }

        foreach ($array as $section => $data) {
            echo '<p>' . $section . '</p> ' . PHP_EOL;
            echo '<ul>' . PHP_EOL;
            foreach ($data as $key => $value) {
                if (!is_array($value)) {
                    echo '<li>' . $key . '= ' . $value . '</li> ' . PHP_EOL;
                }
                if (is_array($value)) {
                    echo '<li>' . PHP_EOL;
                    echo '<p>' . $key . '</p> ' . PHP_EOL;
                    echo '<ul>' . PHP_EOL;
                    foreach ($value as $subkey => $subvalue) {
                        echo '<li>' . $subkey . '= ' . $subvalue . '</li> ' . PHP_EOL;
                    }
                    echo '</ul>' . PHP_EOL;
                    echo '</li>' . PHP_EOL;
                }
            }
            echo '</ul>' . PHP_EOL;
        }
    }

    private static function _formatKey(string $key): string
    {
        $key = trim($key);
        $key = str_replace('(', '', $key);
        $key = str_replace(')', '', $key);
        $key = str_replace('/', '_', $key);
        $key = str_replace(' ', '_', $key);
        $key = str_replace('__', '_', $key);
        $key = strtolower($key);

        return $key;
    }

    private static function _formatValue(string $value): string
    {
        $value = trim($value);
        // $value = str_replace("\/", '/', $value);

        return $value;
    }
}
