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

namespace Phink\Cache;

use \Phink\Utils\TFileUtils;

class TCache
{
    public static function cacheFile($filename, $content)
    {
        $filename = CACHE_DIR . $filename;
        file_put_contents($filename, $content);
    }
    
    public static function clearRuntime() : bool
    {
        $result = false;
        try {
            TFileUtils::delTree(RUNTIME_DIR);
            TFileUtils::delTree(RUNTIME_JS_DIR);
            TFileUtils::delTree(RUNTIME_CSS_DIR);
            mkdir(RUNTIME_DIR, 0777);
            mkdir(RUNTIME_JS_DIR, 0777);
            mkdir(RUNTIME_CSS_DIR, 0777);
            $result = true;
        } catch (\Throwable $ex) {
            self::writeException($ex);

            $result = false;
        }
        return $result;
    }        
}