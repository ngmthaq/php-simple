<?php

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->throttleRequest();
        $this->verifyXsrf();
        $this->request = new Request();
        $this->response = new Response();
    }

    /**
     * Limit number of request per minute
     * 
     * @return void
     */
    private function throttleRequest(): void
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

    /**
     * Verify CSRF Token
     * 
     * @return void
     */
    private function verifyXsrf(): void
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
