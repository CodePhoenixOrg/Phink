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
namespace Phink\Apps\Admin;

use Phink\Core\TBootstrap;

class AppBootstrap extends TBootstrap
{
    public function start(): void
    {
        include '../../framework/puzzle/ipuzzle_library.php';
        if (!file_exists('puzzle_builder.lock')) {
            \Puzzle\JsBuilder::build();
            file_put_contents('puzzle_builder.lock', date('Y-m-d h:i:s'));
        }

        $this->mount([
            BUSINESS_DIR . 'sub_page' . CLASS_EXTENSION
        ]);

        $this->loadINI($this->getPath());
        $this->copyAssets();

    }

}
