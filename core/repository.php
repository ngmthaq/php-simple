<?php

abstract class Repository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = $this->setModel();
    }

    /**
     * Set related model
     * 
     * @return Model
     */
    abstract protected function setModel(): Model;
}
