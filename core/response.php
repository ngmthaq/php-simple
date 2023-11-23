<?php

final class Response
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    final public function file(string $attachment_location): void
    {
        if (file_exists($attachment_location)) {
            ob_end_clean();
            $locations = explode("/", $attachment_location);
            $filename = end($locations);
            http_response_code(200);
            header("Cache-Control: public");
            header("Content-Type: application/octet-stream");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length:" . filesize($attachment_location));
            header("Content-Disposition: attachment; filename=$filename");
            readfile($attachment_location);
        } else {
            throw new Exception("File in $attachment_location not found.");
        }
    }

    final public function json(array $data = [], int $status = 200): void
    {
        ob_end_clean();
        http_response_code($status);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data);
    }

    final public function view(string $_name_, array $_data_ = [], int $_status_ = 200): void
    {
        $_QUERIES = $this->request->queries;
        $_data_[] = $_QUERIES;
        extract($_data_);
        $_path_ = "/" . str_replace(".", "/", $_name_) . ".php";
        require_once(VIEW_DIR . "/pages" . $_path_);
        $_html_ = ob_get_contents();
        ob_end_clean();
        http_response_code($_status_);
        echo $this->sanitizeOutput($_html_);
    }

    private function sanitizeOutput(string $buffer): string
    {
        if (ENV === PROD_ENV) {
            $search = ['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/', '/<pre data-debug=\'true\'>(.|\s)*?<\/pre>/'];
            $replace = ['>', '<', '\\1', '', ''];
            $buffer = preg_replace($search, $replace, $buffer);
        }
        return $buffer;
    }
}
