<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;

class detail extends Controller
{ 
	public function index($id){
		$data = new BaseDataModel;
		$house = $data->get_house_detail($id);
		$area = $data->get_area_name($house['district']);
		$type = $data->get_type_name($house['house_type']);
		$furniture = $data->get_furniture_name($house['furniture']);
		for($i=1;$i<7;$i++){
			$pic[$i] = $house['pic_'.$i];
		}
		$key_word = explode('，',$house['key_word']);
		$this->assign('area',$area);
		$this->assign('keyword',$key_word);
		$this->assign('type',$type);
		$this->assign('furniture',$furniture);
		$this->assign('house',$house);
		$this->assign('pic',$pic);
        return $this->fetch();		
	}
}