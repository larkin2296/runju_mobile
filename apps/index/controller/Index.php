<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class index extends Controller
{ 
	public function index(){
	    setcookie('condition','',-1);
        return $this->fetch();		
	}
	public function left(){
		return $this->fetch();			
	}
}
