<?php

abstract class Controller
{
    public function __construct()
    {
        $this->throttleRequest();
        $this->verifyXsrf();
    }

    protected function showView(string $_name_, array $_data_ = [])
    {
        extract($_data_);
        $_path_ = "/" . str_replace(".", "/", $_name_) . ".php";
        require_once(VIEW_DIR . "/pages" . $_path_);
        $_html_ = ob_get_contents();
        ob_end_clean();
        echo $this->sanitizeOutput($_html_);
    }

    private function sanitizeOutput(string $buffer)
    {
        if (ENV === PROD_ENV) {
            $search = ['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/', '/<pre data-debug=\'true\'>(.|\s)*?<\/pre>/'];
            $replace = ['>', '<', '\\1', '', ''];
            $buffer = preg_replace($search, $replace, $buffer);
        }
        return $buffer;
    }

    private function throttleRequest()
    {
        if (ENV === PROD_ENV) {
            if (empty($_SESSION[THROTTLE_REQUEST_KEY])) {
                $_SESSION[THROTTLE_REQUEST_KEY] = [
                    "timestamp" => time(),
                    "number" => 1,
                ];
            } else {
                $current_time = time();
                $throttle_start_time = $_SESSION[THROTTLE_REQUEST_KEY]["timestamp"];
                if ($current_time - $throttle_start_time > 60) {
                    $_SESSION[THROTTLE_REQUEST_KEY] = [
                        "timestamp" => $current_time,
                        "number" => 1,
                    ];
                } else {
                    $_SESSION[THROTTLE_REQUEST_KEY]["number"] += 1;
                }
                if ($_SESSION[THROTTLE_REQUEST_KEY]["number"] > APP_MAX_REQUEST_PER_MINUTE) {
                    throw new Exception("Throttle Exception");
                }
            }
        }
    }

    private function verifyXsrf()
    {
        if (empty($_SESSION[XSRF_TOKEN_KEY])) {
            $_SESSION[XSRF_TOKEN_KEY] = generateRandomString(32);
        }
        if (isset($_SERVER["REQUEST_METHOD"]) && strtoupper($_SERVER["REQUEST_METHOD"]) === "POST") {
            if (empty($_POST[XSRF_TOKEN_KEY])) {
                throw new Exception("CSRF Exception");
            } elseif (isset($_POST[XSRF_TOKEN_KEY]) && $_POST[XSRF_TOKEN_KEY] !== $_SESSION[XSRF_TOKEN_KEY]) {
                throw new Exception("CSRF Exception");
            }
        }
    }
}
