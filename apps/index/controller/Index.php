<?php
namespace app\index\controller;

use think\Controller;
use think\Cookie;
use think\Db;

class index extends Controller
{ 
	public function index(){
        return $this->fetch();		
	}
	public function left(){
		return $this->fetch();			
	}
}
