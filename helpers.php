<?php

function importPartial(string $_name_): void
{
    $_partial_ = "/" . str_replace(".", "/", $_name_) . ".php";
    include(VIEW_DIR . "/partials" . $_partial_);
}

function printData(mixed $data): void
{
    echo "<pre data-debug='true'>";
    print_r($data);
    echo "</pre>";
}

function assets(string $path): void
{
    $path = str_starts_with($path, "/") ? substr($path, 1) : $path;
    echo ENV === PROD_ENV
        ? APP_HANDLED_URL . $path . "?_v=" . APP_VERSION
        : APP_HANDLED_URL . $path . "?_t=" . time();
}

function generateRandomString(int $length = 16): string
{
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($characters);
    $randomString = "";
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getXsrfToken(): string
{
    return isset($_SESSION[XSRF_TOKEN_KEY]) ? $_SESSION[XSRF_TOKEN_KEY] : "";
}

function generateXsrfMetaTag(): void
{
    $key = XSRF_TOKEN_KEY;
    $token = getXsrfToken();
    echo "<meta name='$key' content='$token' >";
}

function generateXsrfInputTag(): void
{
    $key = XSRF_TOKEN_KEY;
    $token = getXsrfToken();
    echo "<input name='$key' value='$token' type='hidden' >";
}

function reload(): void
{
    header("Refresh:0");
}

function route(string $controller, string $action, array $params = []): void
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

function redirect(string $controller, string $action, array $params = []): void
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

function systemLog(string $message, string $type = "INFO")
{
    $date = gmdate("Y-m-d");
    $datetime = gmdate("Y-m-d H:i:s");
    $msg = "[$datetime UTC] $type: $message \n";
    $log_dir = STORAGE_DIR . "/logs";
    $log_file = "system-log-$date-utc.log";
    $dir = $log_dir . "/" . $log_file;
    file_put_contents($dir, $msg, FILE_APPEND);
    $log_file_names = scandir($log_dir);
    foreach ($log_file_names as $index => $log_file_name) {
        if ($index > 1 && $log_file_name !== $log_file) {
            unlink($log_dir . "/" . $log_file_name);
        }
    }
}


function systemLogInfo(string $message)
{
    $type = "INFO";
    systemLog($message, $type);
}


function systemLogError(string $message)
{
    $type = "ERROR";
    systemLog($message, $type);
}
