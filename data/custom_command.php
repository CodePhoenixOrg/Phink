<?php
namespace Phoenix\Data;

/**
 * Description of ICommand
 *
 * @author david
 */

abstract class TCustomCommand extends \Phoenix\Core\TObject
{
    use \Phoenix\Data\TCrudQueries;

    public abstract function query($sql = '', array $params = null);
    public abstract function exec($sql = '');
    public abstract function getActiveConnection();
    public abstract function getStatement();

}
