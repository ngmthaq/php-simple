<?php

class HomeController extends Controller
{
    /**
     * Render homepage
     */
    public function index()
    {
        return $this->response->view("home");
    }
}
