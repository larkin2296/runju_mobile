<?php

namespace app\index\model;

use think\Model;
use think\Db;

class BaseDataModel extends Model
{
	//获取地区信息
	public function get_location_data(){
		$data = DB::name('location_data')
				->where('parent_id',1)
				->select();
		return $data;
	}
	//获取对应街道信息
	public function get_street($id){
		$data = DB::name('location_data')
				->where('parent_id',$id)
				->select();
		return $data;
	}
	//获取地铁线路信息
	public function get_line_data(){
		$data = DB::name('underground_data')
				->where('parent_id',1)
				->select();
		return $data;
	}
	//获取地铁站信息
	public function get_station($id){
		$data = DB::name('underground_data')
				->where('parent_id',$id)
				->select();
		return $data;
	}
	//获取户型信息
	public function house_type_data(){
		$data = DB::name('house_type_data')
				->select();
		return $data;
	}
	//获取房屋设施信息
	public function furniture_data(){
		$data = DB::name('furniture_data')
				->select();
		return $data;
	}
	public function get_house($type = '',$response = ''){
//		$sql = 'select r1.* from house_rent_data r1 ';
//		if($response == 'tuijian'){
//			$sql_join = '';
//			$where = " where r1.house_level = 1 ";
//		}else if($response == '' && $type == ''){
//			$sql_join = '';
//			$where = '';
//		}else if($response != '' && $type == 1){
//			$sql_join = '';
//			$where = " where r1.street='$response' ";			
//		}else if($response != '' && $type == 2){
//			$sql_join = ' inner join underground_data r2 on r1.underground=r2.underground_name ';
//			$where = " where r2.u_id='$response' ";			
//		}else if($response != '' && $type == ''){
//			$sql_join = ' inner join location_data r2 on r1.street=r2.l_id ';
//			$where = " where r2.location_name like '%$response%' or r1.address like '%$response%' or r1.underground like '%$response%' ";
//		}
		$sql = '';
		$sql_join = '';
		$where = '';
		if($type =='1'){			
			if($response == 'tuijian'){
				$sql_join = '';
				$where = "and r1.house_level = 1 ";
			}else if($response != ''){
				$sql_join = 'inner join location_data r2 on r1.street=r2.l_id ';
				$where = "and r2.location_name like '%$response%' or r1.address like '%$response%' or r1.underground like '%$response%' ";				
			}
			$sql = 'select r1.* from house_rent_data r1 '.$sql_join.'where r1.rent_type = 1 ';
		}else if($type == '0'){
			
			if($response == 'tuijian'){
				$sql_join = '';
				$where = " and r1.house_level = 1 and ";
			}else if($response != ''){
				$sql_join = 'inner join location_data r2 on r1.street=r2.l_id ';
				$where = "and r2.location_name like '%$response%' or r1.address like '%$response%' or r1.underground like '%$response%' ";				
			}
			$sql = 'select r1.* from house_rent_data r1 '.$sql_join.' where r1.rent_type = 0';			
		}else if($type == '3'){
			$sql = 'select r1.* from house_sell_data r1 where 1 ';
			if($response == 'tuijian'){
				$sql_join = '';
				$where = " and r1.house_level = 1 ";
			}else if($response != ''){
				$sql_join = '';
				$where = " and r1.street='$response' ";				
			}					
		}
		$limit = ' limit 10';
		$sql = $sql.$where.$limit;
//		print_r($sql);
		$result = DB::query($sql);
		foreach($result as $key=>$val){
			$data_1 = DB::name('house_type_data')
											->field('house_type_name')
											->where('t_id',$val['house_type'])
											->select();
			$result[$key]['house_type_name'] = $data_1[0]['house_type_name'];
			$result[$key]['keyword'] = explode('，',$val['key_word']);
		}
		return $result;		
	}
	public function get_house_detail($id){
		$data = DB::name('house_rent_data')
				->where('id',$id)
				->select();
		return $data[0];
	}
	public function get_area_name($id){
		$data = DB::name('location_data')
				->field('location_name')
				->where('l_id',$id)
				->select();
		return $data[0]['location_name'];		
	}
	public function get_type_name($id){
		$data = DB::name('house_type_data')
				->field('house_type_name')
				->where('t_id',$id)
				->select();
		return $data[0]['house_type_name'];		
	}
	public function get_furniture_name($furniture){
		$id = explode(',',$furniture);
		foreach($id as $val){
			$data = DB::name('furniture_data')
					->field('furniture_name')
					->where('t_id',$val)
					->select();
			$name[] = $data[0]['furniture_name'];			
		}
		return $name;
	}
	public function get_map($response){
			$sql_main = 'select r1.longitude,r1.latitude,r1.title,r1.id,r1.addr from house_rent_data r1 ';
			$sql_join = ' inner join location_data r2 on r1.street=r2.l_id ';
			$where = " where r2.location_name like '%$response%' or r1.address like '%$response%' or r1.underground like '%$response%' ";
			$sql = $sql_main.$sql_join.$where;
			$result = DB::query($sql);
			return $result;
	}
	public function save_trust_msg($arr,$type){
			$sell_name = $arr['sell_name'];
			$sell_tel = $arr['sell_tel'];
			$sell_village = $arr['sell_village'];
			$sell_addr = $arr['sell_addr'];
			$sell_price = $arr['sell_price'];
			$sub = DB::execute("insert into trusteeship(`name`,`tel`,`village`,`address`,`price`,`trust_type`) values('$sell_name','$sell_tel','$sell_village','$sell_addr','$sell_price',$type)");
			return $sub;
	}
}