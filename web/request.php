<?php
namespace Phink\Web;

/**
 * Description of pagehandler
 *
 * @author David
 */


class TRequest extends \Phink\Core\TObject
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
    private $_viewHeader = '';
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

    private function _getRedirection($url)
    {
        $result = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        if(preg_match('#Location: (.*)#', $res, $r)) {
            $result = trim($r[1]);
        }
        
        return $result;
    }

    private function _getHeader($page) 
    {
        $cookie = session_id();
        $host = HTTP_HOST;
        $ua = HTTP_USER_AGENT;
        
        $url = parse_url($page);
        if($url['host'] === '') {
            $page = SERVER_ROOT . '/' . $page;
        }
  
        $result = [
              "POST ".$page." HTTP/1.0"
            , "Content-Type:text/html; charset=UTF-8"
            , "Accept:text/html, */*; q=0.01"
            , "Cache-Control: no-cache"
            , "Pragma: no-cache"
//            , "Cookie:PHPSESSID=$cookie"
//            , "Host:$host"
            , "User-Agent:$ua"
        ];
        
        if(HTTP_ORIGIN !== '')
        {
            array_push($result, 'Origin:' . HTTP_ORIGIN);
        }
        
        return $result;
    }
    
    private function _getViewHeader($page) {
        $cookie = session_id();
        $host = HTTP_HOST;
        $ua = HTTP_USER_AGENT;
        
        $url = parse_url($page);
        if($url['host'] === '') {
            $page = SERVER_ROOT . '/' . $page;
        }
  
        $result = [
              "POST ".$page." HTTP/1.0"
            , "Content-Type:application/x-www-form-urlencoded; charset=UTF-8"
            , "Accept:application/json, text/javascript, request/view, */*; q=0.01"
            , "Cache-Control: no-cache"
            , "Pragma: no-cache"
//            , "Cookie:PHPSESSID=$cookie"
//            , "Host:$host"
            , "User-Agent:$ua"
        ];
        
        if(HTTP_ORIGIN !== '')
        {
            array_push($result, 'Origin:' . HTTP_ORIGIN);
        }
        
        return $result;
    }
    
    public function addSubRequest($name, $uri, $data = null)
    {
        $header = $this->_getHeader($uri);
        $this->_subRequests[$name] = ['uri' => $uri, 'header' => $header, 'data' => $data];
    }
    
    public function addViewSubRequest($name, $uri, $data = null)
    {
        $header = $this->_getViewHeader($uri);
        $data['action'] = 'getViewHtml';
        $this->_subRequests[$name] = ['uri' => $uri, 'header' => $header, 'data' => $data];
    }

    public function execSubRequests()
    {
        $result = array();
        
        foreach($this->_subRequests as $name => $request) {
        
            //$certpath = DOCUMENT_ROOT . 'cert' . DIRECTORY_SEPARATOR . 'birdy.crt';
            $certpath = 'ca-bundle.crt';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request['uri']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CAINFO, $certpath);
            curl_setopt($ch, CURLOPT_CAPATH, $certpath);
            if(is_array($request['data'])) {
                $queryString = http_build_query($request['data']);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $request['header']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
            }
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);

            $html = curl_exec($ch);
            $error = curl_error($ch);
            $errno = curl_errno($ch);

            $info = curl_getinfo($ch);
            
            $header = (isset($info['request_header'])) ? $info['request_header'] : '';

            if($errno > 0) {
                throw new \Exception($error, $errno);
            }
            if($header == '') {
                throw new \Exception("Curl is not working fine for some reason. Are you using Android ?");
            }

            $code = $info['http_code'];
            curl_close($ch);

            $result[$name] = (object) ['code' => (int)$code, 'header' => $header, 'html' => $html];
                        
            \Phink\Log\TLog::dump('subrequests result', $result);
        }
        
        return $result;
    }

    public function execAsyncSubRequests()
    {
        $result = array();
        
        $mh = curl_multi_init();
        $certpath = DOCUMENT_ROOT . 'cert' . DIRECTORY_SEPARATOR . 'birdy.crt';
        $certpath = 'ca-bundle.crt';

        foreach($this->_subRequests as $name => $request) {
            $ch = curl_init($request['uri']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($ch, CURLOPT_CAINFO, $certpath);
            curl_setopt($ch, CURLOPT_CAPATH, $certpath);
            if(is_array($request['data'])) {
                $queryString = http_build_query($request['data']);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $request['header']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
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
        if(!isset($_REQUEST[$arg])) return false;
        
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