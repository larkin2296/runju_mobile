<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;

class map extends Controller
{ 
	public function index(){
		$data = new BaseDataModel;
		$house_data = $data->get_house('1','tuijian');
        $this->assign('house',$house_data);
        return $this->fetch();			
	}
	public function get_data($response){
		$data = new BaseDataModel;
		$local = $data->get_map($response);
		return $local;
	}
}