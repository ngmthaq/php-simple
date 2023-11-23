<?php

final class Request
{
    public array $queries;
    public array $inputs;
    public array $cookies;
    public array $files;

    public function __construct()
    {
        $this->queries = $this->prepareInput($_GET);
        $this->inputs = $this->prepareInput($_POST);
        $this->cookies = $this->prepareInput($_COOKIE);
        $this->files =  $_FILES;
    }

    private function prepareInput(array $input = [])
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
