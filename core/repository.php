<?php

abstract class Repository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = $this->setModel();
    }

    abstract protected function setModel(): Model;
}
