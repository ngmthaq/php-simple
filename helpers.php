<?php

function importPartial(string $_name_)
{
    $_partial_ = "/" . str_replace(".", "/", $_name_) . ".php";
    include(VIEW_DIR . "/partials" . $_partial_);
}

function printData(mixed $data)
{
    echo "<pre data-debug='true'>";
    print_r($data);
    echo "</pre>";
}

function assets(string $path)
{
    $path = str_starts_with($path, "/") ? substr($path, 1) : $path;
    echo ENV === PROD_ENV
        ? APP_HANDLED_URL . $path . "?_v=" . APP_VERSION
        : APP_HANDLED_URL . $path . "?_t=" . time();
}

function generateRandomString(int $length = 16)
{
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($characters);
    $randomString = "";
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getXsrfToken()
{
    return isset($_SESSION[XSRF_TOKEN_KEY]) ? $_SESSION[XSRF_TOKEN_KEY] : "";
}

function generateXsrfMetaTag()
{
    $key = XSRF_TOKEN_KEY;
    $token = getXsrfToken();
    echo "<meta name='$key' content='$token' >";
}

function generateXsrfInputTag()
{
    $key = XSRF_TOKEN_KEY;
    $token = getXsrfToken();
    echo "<input name='$key' value='$token' type='hidden' >";
}

function reload()
{
    header("Refresh:0");
}

function route(string $controller, string $action, array $params = [])
{
    $app = APP_HANDLED_URL;
    $query = http_build_query(
        array_merge(
            $params,
            [
                "a" => $action,
                "c" => $controller,
            ]
        )
    );
    echo "$app?$query";
}

function redirect(string $controller, string $action, array $params = [])
{
    $app = APP_HANDLED_URL;
    $query = http_build_query(
        array_merge(
            $params,
            [
                "a" => $action,
                "c" => $controller,
            ]
        )
    );
    header("Location: $app?$query");
}
