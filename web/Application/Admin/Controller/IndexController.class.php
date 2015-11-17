<?php
namespace Admin\Controller;
use Home\Controller\HomeController;
class IndexController extends HomeController {
    public function index(){
        $this->display();
    }
}