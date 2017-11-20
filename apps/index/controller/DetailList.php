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
        $key_list = $data->get_key_word();
        $this->assign('key_word',$key_list);
		$this->assign('location',$location);
		$this->assign('line',$line);
		$this->assign('t',$a);
		$this->assign('r',$res);
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
	public function search_price(){
        $model = new BaseDataModel;
	    if($_POST['price'] == ''){
	        return false;
        }
        if($_POST['type'] == (1 || 2)){
            $table = 'house_rent_data';
        }else{
            $table = 'house_sell_data';
        }
	    $p = explode(',',$_POST['price']);
        $p[0] = intval($p[0]);
        $p[1] = intval($p[1]);
        //dump($p);
        //$condition['price'] = array(array('egt',$p[0]),array('lt',$p[1]));
	    $data = DB::name($table)
            ->where('price','between',[$p[0],$p[1]])
            ->select();
        if(empty($data)){
            return false;
        }
	    //print_r($data);
        //echo  Db::table('house_rent_data')->getLastSql();
        foreach($data as $key=>$val){
            $data_1 = DB::name('house_type_data')
                ->field('house_type_name')
                ->where('t_id',$val['house_type'])
                ->select();
            $data[$key]['house_type_name'] = $data_1[0]['house_type_name'];
            $data[$key]['keyword'] = explode('，',$val['key_word']);
            $key_list = $model->get_key_data($val['key_word']);
            $data[$key]['key_word_list'] = $key_list;
        }

        return $data;
    }
    public function more_list(){
        $data = new BaseDataModel;
        if($_POST['r'] == ''){
            $house_data = $data->get_house($_POST['t'],'',$_POST['a']);
        }else{
            $house_data = $data->get_house($_POST['t'],$_POST['r'],$_POST['a']);
        }
        return $house_data;
    }
    public function search_char(){
        $model = new BaseDataModel;
        if($_POST['tese'] == '' && $_POST['chao'] == ''){
            return false;
        }
        if($_POST['type'] == (1 || 2)){
            $table = 'house_rent_data';
        }else{
            $table = 'house_sell_data';
        }
        $chao = $_POST['chao'];
        $tese = $_POST['tese'];
        if(!empty($chao)){
            foreach($chao as $value){
                $where['orientation'] = array('like','%'.$value.'%');
            }
        }
        if(!empty($tese)){
            foreach($tese as $va){
                $key = DB::name('key_word')
                    ->where('key_word_name','=',$va)
                    ->select();
                if(!empty($key)){
                    $key_word = $key[0]['k_id'];
                    $where[] = ['exp',"FIND_IN_SET($key_word,key_word)"];
                }
            }
        }
        $data = DB::name($table)
            ->where($where)
            ->select();
        if(empty($data)){
            return false;
        }
        foreach($data as $key=>$val){
            $data_1 = DB::name('house_type_data')
                ->field('house_type_name')
                ->where('t_id',$val['house_type'])
                ->select();
            $data[$key]['house_type_name'] = $data_1[0]['house_type_name'];
            $data[$key]['keyword'] = explode(',',$val['key_word']);
            $key_list = $model->get_key_data($val['key_word']);
            $data[$key]['key_word_list'] = $key_list;
        }
        return $data;
    }
    public function search_type(){
        $model = new BaseDataModel;
        if($_POST['h_tp'] == ''){
            return false;
        }
        if($_POST['type'] == (1 || 2)){
            $table = 'house_rent_data';
        }else{
            $table = 'house_sell_data';
        }
        $h_tp = $_POST['h_tp'];
        if(!empty($h_tp)){
            foreach($h_tp as $value){
                $where['house_type'] = array('=',$value);
            }
        }
        $data = DB::name($table)
            ->where($where)
            ->select();
        if(empty($data)){
            return false;
        }
        foreach($data as $key=>$val){
            $data_1 = DB::name('house_type_data')
                ->field('house_type_name')
                ->where('t_id',$val['house_type'])
                ->select();
            $data[$key]['house_type_name'] = $data_1[0]['house_type_name'];
            $data[$key]['keyword'] = explode(',',$val['key_word']);
            $key_list = $model->get_key_data($val['key_word']);
            $data[$key]['key_word_list'] = $key_list;
        }
        return $data;
    }
}