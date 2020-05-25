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

use Phink\Core\TStaticObject;
use Phink\Utils\TFileUtils;

abstract class TBootstrap extends TStaticObject
{

    use TIniLoader;

    private $_path = '';

    public function getPath()
    {
        return $this->_path;
    }

    public function __construct(string $path)
    {
        $this->_path = dirname($path, 1) . DIRECTORY_SEPARATOR;
    }

    public function start(): void
    {}

    public function mount(array $filenames): TBootstrap
    {
        if (\Phar::running() != '') {
            foreach ($filenames as $filename) {
                include pathinfo($this->_path . $filename, PATHINFO_BASENAME);
            }
        } else {
            foreach ($filenames as $filename) {
                include $this->_path . $filename;
            }
        }

        return $this;
    }

    public function copyAssets()
    {
        $assets = $this->_path . 'assets';

        if (!\file_exists($assets)) {
            return false;
        }

        $tree = TFileUtils::walkTree($assets);
        self::getLogger()->dump('ASSETS TREE AT ' . $assets, $tree);

        // $currentDir = pathinfo($this->_path, PATHINFO_BASENAME);

        $destDir = DOCUMENT_ROOT . 'admin' . DIRECTORY_SEPARATOR . 'assets';

        if (!\file_exists($destDir)) {
            mkdir($destDir, 0755);
        }

        if (\file_exists($destDir)) {

            foreach ($tree as $filePath) {
                $path = pathinfo($filePath, PATHINFO_DIRNAME);

                if (!\file_exists($destDir . $path)) {
                    mkdir($destDir . $path, 0755, true);
                }

                if (!\file_exists($destDir . $filePath)) {
                    copy($assets . $filePath, $destDir . $filePath);
                }
            }
        }

    }

}
