<?php

/*
 * Copyright (C) 2017 dpjb
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

use Phink\Data\Client\PDO\TPdoConfiguration;
use Phink\Data\Client\PDO\TPdoConnection;
use Phink\Data\ISqlConnection;
use Phink\Data\TAnalyzer;
use Phink\UI\TConsoleApplication;
use Phink\Web\UI\TScriptMaker;

class EggLib extends Phink\Core\TObject
{
    protected $connection = null;
    /**
     * Defines the full directory tree of a Phink web application
     */
    private $directories = [
        'app',
        'app/business',
        'app/controllers',
        'app/models',
        'app/rest',
        'app/scripts',
        'app/templates',
        'app/views',
        'app/webservices',
        'cache',
        'cert',
        'config',
        'css',
        'data',
        'docker',
        'fonts',
        'logs',
        'media',
        'media/images',
        'runtime',
        'runtime/js',
        'themes',
        'tmp',
        'tools',
        'web',
        'web/css',
        'web/css/images',
        'web/fonts',
        'web/js',
        'web/js/runtime',
        'web/media',
        'web/media/images',
    ];

    protected $appDir = '';
    protected $appName = '';

    protected function getConnection(): ISqlConnection
    {
        $config = new TPdoConfiguration();
        $config->loadConfiguration($this->appDir . $this->appName . '.json');
        return new TPdoConnection($config);
    }
    /**
     * Constructor
     */
    public function __construct(TConsoleApplication $parent)
    {
        parent::__construct($parent);

        $this->appDir = $parent->getDirectory();
        $this->appName = $parent->getName();
    }

    public function makeScripts(string $usertable): void
    {
        $pa_id = 1;
        $pa_filename = $usertable . '.php';

        $cs = $this->getConnection();
        $config = $cs->getConfiguration();
        $cs->open();

        $userdb = $config->getDatabaseName();

        $analyzer = new TAnalyzer;
        $references = $analyzer->searchReferences($userdb, $usertable, $cs);

        $A_fieldDefs = $references["field_defs"];
        $sql = "show fields from $usertable;";

        $L_sqlFields = "";
        $A_sqlFields = [];

        $stmt = $cs->query($sql);
        while ($rows = $stmt->fetch()) {
            $L_sqlFields .= $rows[0] . ",";
        }

        $L_sqlFields = substr($L_sqlFields, 0, strlen($L_sqlFields) - 1);
        $A_sqlFields = explode(",", $L_sqlFields);
        $indexfield = $A_sqlFields[0];
        $secondfield = $A_sqlFields[1];

        $scriptMaker = new TScriptMaker;
        $code = $scriptMaker->makeCode($userdb, $usertable, $stmt, $pa_id, $indexfield, $secondfield, $A_fieldDefs, $cs, false);
        $page = $scriptMaker->makePage($userdb, $usertable, $pa_filename, $pa_id, $indexfield, $secondfield, $A_sqlFields, $cs, false);

        print_r($references);
        print_r($code);
        print_r($page);
    }

    /**
     * Deletes recursively a tree of directories containing files.
     * It is a workaround for rmdir which doesn't allow the deletion
     * of directories not empty.
     *
     * @param string $path Top directory of the tree
     * @return boolean TRUE if deletion succeeds otherwise FALSE
     */
    private function _deltree($path)
    {
        $class_func = array(__CLASS__, __FUNCTION__);
        return is_file($path) ?
        @unlink($path) :
        array_map($class_func, glob($path . '/*')) == @rmdir($path);
    }

    /**
     * Create the skeleton of the application
     */
    public function createTree()
    {
        $this->parent->writeLine("Current directory %s", __DIR__);

        sort($this->directories);
        foreach ($this->directories as $directory) {
            if (!file_exists($directory)) {
                $this->parent->writeLine("Creating directory %s", $directory);
                mkdir($directory, 0755, true);
            } else {
                $this->parent->writeLine("Directory %s already exist", $directory);
            }
        }
    }

    /**
     * Deletes recursively all known directories of the application
     */
    public function deleteTree()
    {
        $this->parent->writeLine("Current directory %s", $this->appDir);

        rsort($this->directories);
        foreach ($this->directories as $directory) {
            $dir = $this->appDir . $directory;
            if (file_exists($dir)) {
                $this->parent->writeLine("Removing directory %s", $dir);
                $this->_deltree($dir);
            } else {
                $this->parent->writeLine("Cannot find directory %s", $dir);
            }
        }
    }
}
