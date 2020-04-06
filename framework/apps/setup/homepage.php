<?php
include '../../framework/phink/phink_library.php';
include '../../framework/apps/setup/setup.php';

use Phink\Setup;

class SetupPage
{
    private $_setup;
    const SERVER_ERROR = 'Something went wrong, please check the server setup';

    public static function main(): void
    {
        (new SetupPage())->load();
    }

    public function load(): void
    {
        $this->_setup = Setup::create();

        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

        if ($action === 'rewrite') {
            $this->fixRewriteBase();
        }

        if ($action === 'js') {
            $this->installPhinkJS();
        }

        if ($action === 'index') {
            $this->makeIndex();
        }

        if ($action === null) {
            $this->displayHtml();
        }
    }

    public function fixRewriteBase(): void
    {
        $ok = $this->_setup->fixRewritBase();
        $this->sendResponse(
            [
                'result' => ($ok) ? 'All is setup' : SetupPage::SERVER_ERROR,
                'error' => !$ok
            ]
        );
    }

    public function installPhinkJS(): void
    {
        $ok = $this->_setup->installPhinkJS();
        $this->sendResponse(
            [
                'result' => ($ok) ? 'Javascript framework installation successful' : SetupPage::SERVER_ERROR,
                'error' => !$ok
            ]
        );
    }

    public function makeIndex(): void
    {
        $ok = $this->_setup->makeBootstrap();
        $ok = $this->_setup->makeIndex();
        $this->sendResponse(
            [
                'result' => ($ok) ? 'Index created' : SetupPage::SERVER_ERROR,
                'error' => !$ok
            ]
        );
    }

    public function ready(): void
    {
        header('Location: ' . $this->_setup->getRewriteBase());
    }

    public function sendResponse(array $response): void
    {
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function displayHtml(): void
    {
?>
        <html>

        <head>
            <meta charset="utf-8" />
            <title>Phink Setup</title>
            <!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" > -->
            <style>
                body {
                    font-family: Arial, Helvetica, sans-serif;
                }

                li {
                    list-style-type: none;
                    text-decoration: none;
                }

                main {
                    text-align: center;
                }

                .border {
                    float: left;
                    position: relative;
                    width: 25%;
                }

                .subborder {
                    float: left;
                    position: relative;
                    width: 25%;
                }

                .content {
                    float: left;
                    position: relative;
                    width: 50%;
                    text-align: center;

                }

                ul {
                    float: left;
                    position: relative;
                    width: 50%;
                    text-align: left;
                    margin-block-start: 0em;
                    margin-block-end: 0em;
                    padding-inline-start: 0px;
                }

                div.caption {
                    float: left;
                    position: relative;
                }

                div.step {
                    float: right;
                    position: relative;
                    text-align: right;
                }

                .clear {
                    clear: both;
                }
            </style>
        </head>

        <body>
            <header>
                <h1>
                    Phink Setup
                </h1>
            </header>
            <main>
                <section>
                    <h3>
                        Processing through steps, please wait ...
                    </h3>
                </section>
                <section>
                    <div class="border">&nbsp;</div>
                    <div class="content">
                        <div class="subborder">&nbsp;</div>

                        <ul>
                            <li>
                                <div class="caption">Fix .htaccess RewriteBase</div>
                                <div data-step="1" class="step uncheck">...</div>
                            </li>
                            <li>
                                <div class="caption">Download JS framework</div>
                                <div data-step="2" class="step uncheck">...</div>
                            </li>
                            <li>
                                <div class="caption">Make the index file</div>
                                <div data-step="3" class="step uncheck">...</div>
                            </li>
                        </ul>
                        <div class="subborder">&nbsp;</div>

                    </div>
                    <div class="border">&nbsp;</div>
                    <div class="clear"></div>
                </section>
                <section>
                    <h3>
                        You will be redirected to the Welcome page
                    </h3>
                </section>
            </main>
            <footer>

            </footer>
            <script>
                PhinkSetup = {};
                PhinkSetup.rewriteBase = window.location.pathname;
                PhinkSetup.URL = window.location.href;

                PhinkSetup.ajax = function(url, data, callback) {
                    var params = [];

                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {
                            params.push(key + '=' + encodeURI(data[key]));
                        }
                    }

                    var queryString = params.join('&');
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', url);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.setRequestHeader("Accept", "application/json, text/javascript, */*; q=0.01");
                    xhr.onload = function() {
                        if (xhr.status < 300 || xhr.status === 304) {
                            var data = (xhr.responseText !== '') ? JSON.parse(xhr.responseText) : [];

                            if (typeof callback == 'function') {
                                callback.call(this, data, xhr.statusText, xhr);
                            }
                        }

                    }
                    xhr.onerror = function() {
                        xhr.abort();
                    }
                    xhr.onabort = function() {
                        if (xhr.statusText === 'error') {
                            errorLog("Satus : " + xhr.status + "\r\n" +
                                "Options : " + xhr.statusText + "\r\n" +
                                "Message : " + xhr.responseText);
                        }
                    }

                    xhr.send(queryString);
                }

                PhinkSetup.operationState = function(step, error) {

                    if (error) {
                        document.querySelector('div[data-step="' + step + '"]').innerHTML = 'Error';
                        document.querySelector('div[data-step="' + step + '"]').className = 'step error';
                    }
                    if (!error) {
                        document.querySelector('div[data-step="' + step + '"]').innerHTML = 'OK!';
                        document.querySelector('div[data-step="' + step + '"]').className = 'step ok';
                    }
                }

                PhinkSetup.ajax(PhinkSetup.URL, {
                        "action": "rewrite"
                    },
                    function(data) {
                        PhinkSetup.operationState(1, data.error);
                    }
                );

                PhinkSetup.ajax(PhinkSetup.URL, {
                        "action": "js"
                    },
                    function(data) {
                        PhinkSetup.operationState(2, data.error);
                    }
                );

                PhinkSetup.ajax(PhinkSetup.URL, {
                        "action": "index"
                    },
                    function(data) {
                        PhinkSetup.operationState(3, data.error);
                    }
                );
            </script>
        </body>

        </html>
<?php
    }
}
