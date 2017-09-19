<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;

class tuoguan extends Controller
{ 
	public function index(){
        return $this->fetch();		
	}
	public function left(){
		return $this->fetch();			
	}
	public function save_sell(){
		$data = new BaseDataModel;
		$res = $data->save_trust_msg($_POST,0);
		return $res;
	}
	public function save_rent(){
		$data = new BaseDataModel;
		$res = $data->save_trust_msg($_POST,1);
		return $res;
	}
}