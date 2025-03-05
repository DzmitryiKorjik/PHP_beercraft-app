<?php 
require_once 'app/models/Beer.php';

class BeerController
{
    private $model;

    public function __construct()
    {
        $this->model = new Beer();
    }

    public function index($view = 'home')
    {
        $beers = $this->model->getAllBeers();
        require_once __DIR__ . "/../views/layout.php";
    }    
}
?>