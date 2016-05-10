<?php
namespace Phoenix\Web;

//require_once 'phoenix/core/object.php';
//require_once 'phoenix/mvc/view.php';
//require_once 'phoenix/utils/file_utils.php';
//require_once 'phoenix/utils/string_utils.php';
//require_once 'phoenix/core/log.php';

/**
 * Description of pagehandler
 *
 * @author David
 */


class TRequest extends \Phoenix\Core\TObject
{
    //put your code here
    
    private $_queryArguments = null;
    private $_isAJAX = false;
    private $_isJSONP = false;
    private $_isEncrypted = false;
    private $_isPartialView = false;
    private $_callbackAction = '';
    private $_contents = array();
    private $_subRequests = array();
    //private $_subRequestsHandler = null;
    
    public function __construct()
    {
       
        $this->_queryArguments = $_REQUEST;
        $callback = '';
        if(strstr(HTTP_ACCEPT, 'application/json, text/javascript') || $this->getQueryArguments('ajax')) {
            if(strstr(HTTP_ACCEPT, 'request/partialview') || $this->getQueryArguments('partial')) {
                $this->_isPartialView = true;
            }
            $this->_isAJAX = true;
            $callback = $this->getCallbackAction();
        }
        $this->_isJSONP = ($callback != '');
    
    }

    public function addSubRequest($name, $uri, $data = null)
    {
        $this->_subRequests[$name] = ['uri' => $uri, 'data' => $data];
    }
    
    public function addViewSubRequest($name, $uri, $data = null)
    {
        if(is_array($data)) {
            $data['action'] = 'getViewHtml';
        }
        $this->_subRequests[$name] = ['uri' => $uri, 'data' => $data];
    }

    public function execSubRequests()
    {
        $result = array();
        
        foreach($this->_subRequests as $name => $request) {
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request['uri']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            if(is_array($request['data'])) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request['data']);
            }
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);

            $html = curl_exec($ch);
            $info = curl_getinfo($ch);
            $header = (isset($info['request_header'])) ? $info['request_header'] : '';

            if($header == '') {
                throw new \Exception("Curl is not working fine for some reason. Are you using Android ?");
            }

            $code = $info['http_code'];
            curl_close($ch);

            $result[$name] = ['code' => $code, 'header' => $header, 'html' => $html];
        }
        
        return $result;
    }

    public function execAsyncSubRequests()
    {
        $result = array();
        
        $mh = curl_multi_init();

        foreach($this->_subRequests as $name => $request) {
            $ch = curl_init($request['uri']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            if(is_array($request['data'])) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request['data']);
            }
            curl_multi_add_handle($mh, $ch);
        }
        
        $still_running = true;
        $this->_backgroundSubrequests($mh, $still_running); // start requests
        do { // "wait for completion"-loop
            curl_multi_select($mh); // non-busy (!) wait for state change
            $this->_backgroundSubrequests($mh, $still_running); // get new state
            while ($info = curl_multi_info_read($mh)) {
              // process completed request (e.g. curl_multi_getcontent($info['handle']))
                $ch = $info['handle'];
                $requestInfo = curl_getinfo($ch);
                $header = (isset($requestInfo['request_header'])) ? $requestInfo['request_header'] : '';
                
                if($header == '') {
                    throw new \Exception("Curl is not working fine for some reason. Are you using Android ?");
                }
            
                $code = $requestInfo['http_code'];
                $html = curl_multi_getcontent($ch);
                
                $name = $this->_identifySubRequest($header);
                unset($this->_subRequests[$name]);
                $result[$name] = ['code' => $code, 'header' => $header, 'html' => $html];
            }
        } while ($still_running); 
        
        return $result;
        
    }

    private function _backgroundSubrequests($mh, &$still_active)
    {
        do {
            $status = curl_multi_exec($mh, $still_active);
        } while ($status === CURLM_CALL_MULTI_PERFORM || $still_active);
        
        return $status;
    }
    
    private function _identifySubRequest($header)
    {
        $result = '';
        
        foreach ($this->_subRequests as $name => $request) {
            if(strstr($header, $request['uri'])) {
                $result = $name;
                break;
            }
        }
        
        return $result;
    }


    public function getToken()
    {
        return $this->getQueryArguments('token');
    }

    public static function getQueryStrinng($arg = null)
    {
        $result = false;
        
        if(!($result = filter_input(INPUT_POST, $arg, FILTER_DEFAULT))) {
            $result = filter_input(INPUT_GET, $arg, FILTER_DEFAULT);
        }
        
        return $result;
    }

    public function getQueryArguments($arg = null)
    {
        return self::getQueryStrinng($arg);
    }

    public function getArgumentsNames()
    {
        return array_keys($_REQUEST);
    }

    public function isEncrypted()
    {
        return $this->_isEncrypted;
    }
    
    public function isJSONP()
    {
        return $this->_isJSONP;
    }
    
    public function isAJAX()
    {
        return $this->_isAJAX;
    }
    
    public function isPartialView()
    {
        return $this->_isPartialView;
    }
    
    public function getCallbackAction()
    {
        if(empty($this->_callbackAction)) {
            $this->_callbackAction = (isset($_REQUEST['callback'])) ? $_REQUEST['callback'] : '';
        }
        return $this->_callbackAction;
    }
    
    public function registerContents($name, $content)
    {
        $this->_contents[$name] = $content;
    }
    
    public function getRegisteredContents($name)
    {
        return (isset($this->_contents[$name])) ? $this->_contents[$name] : false;
    }

}