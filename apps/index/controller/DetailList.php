<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Cookie;
use app\index\model\BaseDataModel;

class detaillist extends Controller
{ 
	public function index($a = '',$res = ''){
		$data = new BaseDataModel;
        $con = $_COOKIE['condition'];
        $ccc = unserialize($con);
        if($ccc == '' || empty($ccc) || !isset($ccc)){
            if($res == ''){
                $house_data = $data->get_house($a,'');
            }else{
                $house_data = $data->get_house($a,$res);
            }
        }else{
            if ($a == '1') {
                $table = 'house_rent_data';
            } else if ($a == '0') {
                $table = 'house_rent_data';
            } else if ($a == '3') {
                $table = 'house_sell_data';
            }
            if($res != '') {
                $where = $data->get_house_where($res,$a);
                $house_data = $this->get_result($table, $ccc,$where,0);
            }else{
                $house_data = $this->get_result($table,$ccc,'',0);
            }
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
		$type = $_POST['type'];
		$data = new BaseDataModel;
		$house = $data->get_house($type,$id);
		return $house;			
	}
    public function get_result($table,$ccc,$where = '',$a = 0){
        $model = new BaseDataModel;
        if($ccc == ''){
            $data = DB::view($table,'*')
                ->view('location_data',['location_name'],'location_data.l_id=house_rent_data.street')
                ->where($where)
                ->limit($a,5)
                ->order('price asc')
                ->select();
        }else{
            $data = DB::view($table,'*')
                ->view('location_data',['location_name'],'location_data.l_id=house_rent_data.street')
                ->whereOr($where)
                ->where($ccc)
                ->limit($a,5)
                ->order('price asc')
                ->select();
        }


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
            if($val['house_status'] == 2){
                $data[$key]['house_status_name'] = '配置中';
            }else if($val['house_status'] == 1){
                $data[$key]['house_status_name'] = '已出售';
            }else if($val['house_status'] == 3){
                $data[$key]['house_status_name'] = '未出租';
            }else{
                $data[$key]['house_status_name'] = '未出售';
            }
        }
        return $data;
    }
    public function more_list(){
        $data = new BaseDataModel;
        $con = $_COOKIE['condition'];
		$ccc = unserialize($con);
        if($ccc == '' || empty($ccc) || !isset($ccc)){
            if($_POST['r'] == ''){
                $house_data = $data->get_house($_POST['t'],'',$_POST['a']);
            }else{
                $house_data = $data->get_house($_POST['t'],$_POST['r'],$_POST['a']);
            }
        }else{
            if ($_POST['t'] == '1') {
                $table = 'house_rent_data';
            } else if ($_POST['t'] == '0') {
                $table = 'house_rent_data';
            } else if ($_POST['t'] == '3') {
                $table = 'house_sell_data';
            }
            if($_POST['r'] != '') {
                $where = $data->get_house_where($_POST['r'],$_POST['t']);
                $house_data = $this->get_result($table, $ccc,$where, $_POST['a']);
            }else{
                $house_data = $this->get_result($table, $ccc, '',$_POST['a']);
            }
        }
        return $house_data;
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
        if(!empty($_POST['r'])){
            $where = $model->get_house_where($_POST['r'],$_POST['t']);
        }
		$pri_arr = serialize(array('price'=>array('between',"$p[0],$p[1]")));
		setcookie('condition',$pri_arr);
		//Cookie::set('condition',['price'=>array('between',"$p[0],$p[1]")]);
        $where['price'] = array('between',"$p[0],$p[1]");
        $data = $this->get_result($table,$where);
        return $data;
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
			$c_arr = serialize($where);
			setcookie('condition',$c_arr);
        if(!empty($_POST['r'])){
            $whe = $model->get_house_where($_POST['r'],$_POST['t']);
            $new_where = array_merge($whe,$where);
        }else{
            $new_where = $where;
        }
        $data = $this->get_result($table,$new_where);
        return $data;
    }
    public function search_type(){
        $model = new BaseDataModel;
        if(!empty($_POST['r'])){
            $where = $model->get_house_where($_POST['r'],$_POST['t']);
        }
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
		$house_arr = serialize($where);
		setcookie('condition',$house_arr);
        //Cookie::set('condition',['house_type'=>$h_tp]);
        $data = $this->get_result($table,$where);
        return $data;
    }
    public function page($p){
        return $this->fetch();
    }
}