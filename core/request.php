<?php

final class Request
{
    /**
     * $_GET array prepared
     */
    public array $queries;

    /**
     * $_POST array prepared
     */
    public array $inputs;

    /**
     * $_COOKIE array prepared
     */
    public array $cookies;

    /**
     * $_FILES array prepared
     */
    public array $files;

    public function __construct()
    {
        $this->queries = $this->prepareInput($_GET);
        $this->inputs = $this->prepareInput($_POST);
        $this->cookies = $this->prepareInput($_COOKIE);
        $this->files =  $_FILES;
    }

    /**
     * Prepare input to prevent XSS
     * 
     * @param array $input
     * @return array
     */
    private function prepareInput(array $input = []): array
    {
        $output = [];
        foreach ($input as $key => $value) {
            if (gettype($value) === "string") {
                $output[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
            } elseif (gettype($value) === "array") {
                $output[$key] = $this->prepareInput($value);
            } else {
                $output[$key] = $value;
            }
        }
        return $output;
    }
}
