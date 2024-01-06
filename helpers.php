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

function systemLog(string $message, string $type = "INFO"): void
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


function systemLogInfo(string $message): void
{
    $type = "INFO";
    systemLog($message, $type);
}


function systemLogError(string $message): void
{
    $type = "ERROR";
    systemLog($message, $type);
}

function getIPAddress(): string
{
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = "";
    }
    return $ip;
}

function convertStringToSlug(string $string, string $symbol = '-'): string
{
    if (empty($string)) return $string;
    $character_a = array('à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ');
    $character_e = array('è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ');
    $character_i = array('ì', 'í', 'ị', 'ỉ', 'ĩ');
    $character_o = array('ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ');
    $character_u = array('ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ');
    $character_y = array('ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ');
    $character_d = array('đ');
    $character_symbol = array('!', '@', '%', '^', '*', '(', ')', '+', '=', '<', '>', '?', '/', ', ', '.', ':', ';', '|', '"', '&', '#', '[', ']', '~', '$', '_', '__', '--', ' ');
    $alias = mb_strtolower($string, 'UTF-8');
    $alias = trim($alias);
    $alias = str_replace($character_a, 'a', $alias);
    $alias = str_replace($character_e, 'e', $alias);
    $alias = str_replace($character_i, 'i', $alias);
    $alias = str_replace($character_o, 'o', $alias);
    $alias = str_replace($character_u, 'u', $alias);
    $alias = str_replace($character_y, 'y', $alias);
    $alias = str_replace($character_d, 'd', $alias);
    $symbol_modify = '-';
    if (isset($symbol)) $symbol_modify = $symbol;
    $alias = str_replace($character_symbol, $symbol_modify, $alias);
    $alias = preg_replace('/--+/', $symbol_modify, $alias);
    $alias = preg_replace('/__+/', $symbol_modify, $alias);
    return $alias;
}

function jsonEncodePrettify(mixed $data): string | false
{
    return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

function needJsonResponse(): bool
{
    return isset($_SERVER["HTTP_ACCEPT"]) && $_SERVER["HTTP_ACCEPT"] === "application/json";
}
