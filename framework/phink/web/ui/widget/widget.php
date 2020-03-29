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
 
namespace Phink\Web\UI\Widget;

use \Phink\MVC\TPartialController;
use Phink\Data\IDataStatement;

/**
 * Description of TWidget
 *
 * @author david
 */
abstract class TWidget extends TPartialController
{
    protected $caption;
    protected $statement;
    protected $onclick;
    protected $script;
    protected $forThis;
    protected $forView;
    protected $forCtrl;
    protected $forApp;

    public function setStatement(?IDataStatement $value) : void
    {
        $this->statement = $value;
    }

    public function setCaption($value) : void
    {
        $this->caption = $value;
    }

    public function setOnclick($value) : void
    {
        $this->onclick = $value;
    }
    
    public function setFor($value) : void
    {
        $this->forThis = $value;
    }

    public function getCacheFilename() : string
    {
        return SRC_ROOT . REL_RUNTIME_DIR . str_replace(DIRECTORY_SEPARATOR, '_', $this->path . $this->forThis . 'pager' . CLASS_EXTENSION);
    }
}