<?php

class HomeController extends Controller
{
    public function index()
    {
        return $this->showView("home", ["name" => "Thang"]);
    }
}
