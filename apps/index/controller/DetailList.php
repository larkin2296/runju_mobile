<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;

class detaillist extends Controller
{ 
	public function index($a = '',$res = ''){
		$data = new BaseDataModel;		
		if($res == ''){
		$house_data = $data->get_house($a,'');			
		}else{
		$house_data = $data->get_house($a,$res);
		}
		$location = $data->get_location_data();
		$line = $data->get_line_data();
		$house_type = $data->house_type_data();
		$this->assign('location',$location);
		$this->assign('line',$line);
		$this->assign('t',$a);
		$this->assign('type',$house_type);
		$this->assign('house',$house_data);
        return $this->fetch();		
	}
	public function left(){
		return $this->fetch();			
	}
	public function get_street(){
		$id = $_POST['id'];
		$data = new BaseDataModel;
		$street = $data->get_street($id);
		return $street;
	}
	public function get_station(){
		$id = $_POST['id'];
		$data = new BaseDataModel;
		$station = $data->get_station($id);
		return $station;
	}
	public function get_house_list(){
		$id = $_POST['id'];
		$type = $_POST['type_1'];
		$data = new BaseDataModel;
		$house = $data->get_house($id,$type);
		return $house;			
	}
}