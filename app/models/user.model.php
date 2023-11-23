<?php

class User extends Model
{
    protected function setTableName(): string
    {
        return "users";
    }
}
