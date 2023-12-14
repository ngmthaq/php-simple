<?php

abstract class Model
{
    protected string $table;

    public function __construct()
    {
        $this->table = $this->setTableName();
    }

    /**
     * Set database table name
     * 
     * @return string
     */
    abstract protected function setTableName(): string;
}
