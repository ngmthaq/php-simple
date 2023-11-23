<?php

class UserRepo extends Repository
{
    protected function setModel(): Model
    {
        return new User();
    }
}
