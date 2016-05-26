<?php
namespace Phink\Data;

/**
 * Description of ICommand
 *
 * @author david
 */

abstract class TCustomCommand extends \Phink\Core\TObject
{
    use \Phink\Data\TCrudQueries;

    public abstract function query($sql = '', array $params = null);
    public abstract function exec($sql = '');
    public abstract function getActiveConnection();
    public abstract function getStatement();

}
