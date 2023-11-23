<?php

abstract class Model
{
    protected string $table;

    public function __construct()
    {
        $this->table = $this->setTableName();
    }

    abstract protected function setTableName(): string;
}
