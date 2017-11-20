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
	public function get_house($type = '',$response = '',$a=0){
		$where = array();
		$table = '';
        /*******************************************************************************************/
        /*方法重写*/
        if($type =='1'){
            $table = 'house_rent_data';
            $where = $this->house_search($table,$response,'1');
        }else if($type == '0'){
            $table = 'house_rent_data';
            $where = $this->house_search($table,$response,'0');
        }else if($type == '3'){
            $table = 'house_sell_data';
            if($response == 'tuijian'){
                $where['house_level'] = array('=','1');
            }else if($response != ''){
                $where['location_data.location_name|house_rent_data.address|house_rent_data.underground'] = array('like','%'.$response.'%');
            }
        }
        if($response != ''){
            $result = DB::view($table,'*')
                ->view('location_data',['location_name'],'location_data.l_id=house_rent_data.street')
                ->where($where)
                ->limit($a,5)
                ->select();
        }else{
            $result = DB::name($table)
                ->where($where)
                ->limit($a,5)
                ->select();
        }
        if(empty($result)){
            return false;
        }
		foreach($result as $key=>$val){
			$data_1 = DB::name('house_type_data')
											->field('house_type_name')
											->where('t_id',$val['house_type'])
											->select();
			$key_list = $this->get_key_data($val['key_word']);
            $result[$key]['key_word_list'] = $key_list;
			$result[$key]['house_type_name'] = $data_1[0]['house_type_name'];
			$result[$key]['keyword'] = explode('，',$val['key_word']);
			if($val['house_status'] == 2){
                $result[$key]['house_status_name'] = '配置中';
            }else if($val['house_status'] == 1){
                $result[$key]['house_status_name'] = '已出售';
            }else if($val['house_status'] == 3){
                $result[$key]['house_status_name'] = '未出租';
            }else{
                $result[$key]['house_status_name'] = '未出售';
            }
		}
		return $result;
	}
	public function get_house_detail($id){
		$data = DB::name('house_rent_data')
				->where('id',$id)
				->select();
            $key_list = $this->get_key_data($data[0]['key_word']);
            $data[0]['key_word_list'] = $key_list;
            $data[0]['keyword'] = explode('，',$data[0]['key_word']);
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
	public function get_key_word(){
        $data = DB::name('key_word')
            ->select();
        return $data;
    }
    public function get_key_data($key){
	    $key_response = array();
        $key_list = array();
        $key_response = explode(',',$key);
	    foreach($key_response as $val){
           $data  = DB::name('key_word')
                ->where('k_id',$val)
                ->column('key_word_name');
            $key_list[] = $data[0];
        }
        return $key_list;
    }
    private function house_search($table,$response,$type){
        $where = array();
        if($response == 'tuijian'){
            $where['house_level'] = array('=','1');
            $where['rent_type'] = array('=',$type);
        }else if($response != ''){
            $key = DB::name('key_word')
                    ->where('key_word_name','=',$response)
                    ->select();
            if(!empty($key)){
                $key_word = $key[0]['k_id'];
                $where[] = ['exp',"FIND_IN_SET($key_word,key_word)"];
                return $where;
            }
            $shop = DB::name('shopping_set')
                ->where([
                    's_name'=>['=',$response],
                    's_p_id'=>['=',0],
                ])
                ->select();

            if(!empty($shop)){
                $get_shopping = DB::name('shopping_set')
                    ->where('s_p_id','=',$shop[0]['s_id'])
                    ->select();
                foreach($get_shopping as $val){
                    $where[] = ['exp',"FIND_IN_SET('{$val['s_name']}',shopping_nearby)"];
                }
                return $where;
            }
            $where['location_data.location_name|house_rent_data.address|house_rent_data.underground'] = array('like','%'.$response.'%');
        }else{
            $where['rent_type'] = array('=','1');
        }
        return $where;
    }
    public function get_station_data($a){
        $data = DB::name('underground_data')
            ->where('parent_id',$a)
            ->select();
        return $data;
    }
    public function get_street_data($a){
        $data = DB::name('location_data')
            ->where('parent_id',$a)
            ->select();
        return $data;
    }
}