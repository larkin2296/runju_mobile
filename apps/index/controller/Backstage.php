<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\BaseDataModel;
use app\index\model\BackstageModel;

class backstage extends Controller
{ 
	public function index(){
        return $this->fetch();		
	}

	public function house_list(){
		$list = DB::name('house_rent_data')
					->paginate(10);
		$page = $list->render();
        $this->assign('page', $page);
      	$this->assign('lists', $list);
        return $this->fetch();		
	}
	public function list_1(){
		phpinfo();
	}
	public function add(){
		$data = new BaseDataModel;
		$location = $data->get_location_data();
		$line = $data->get_line_data();
		$house_type = $data->house_type_data();
		$furniture_data = $data->furniture_data();
		$key_word = $data->get_key_word();
        $this->assign('key_word',$key_word);
		$this->assign('location',$location);
		$this->assign('line',$line);
		$this->assign('furniture',$furniture_data);
		$this->assign('house_type',$house_type);

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
	
	public function up(){
		$uploaddir = 'public/uploads/';
		$name = $_FILES['file']['name'];
		$name_list = $name;
		$uploadfile = $uploaddir . $name;
		$type = strtolower(substr(strrchr($name, '.'), 1));
		//获取文件类型
		$typeArr = array("jpg","png","gif");
		if (!in_array($type, $typeArr)) {
		    echo "请上传jpg,png或gif类型的图片！";
		    exit;
		}
		if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.$name_list)) {
		    return $name_list;
		} else {			
		    return $name_list;
		}
	}
	public function get_form_data(){
		$data = new BackstageModel;
		$result = $data->get_form_data($_POST);
        return $this->fetch();
	}
	public function house_detail($id){
		$data = new BaseDataModel;
		$pic = array();
		$location = $data->get_location_data();
		$line = $data->get_line_data();

		$house_type = $data->house_type_data();
		$furniture_data = $data->furniture_data();
		$house_data = DB::name('house_rent_data')
				->where('id',$id)
                ->select();
        $key_word = $data->get_key_word();
        $station = $data->get_station_data($house_data[0]['line']);
        $street = $data->get_street_data($house_data[0]['district']);
		$house_data[0]['fur_list'] = explode(',',$house_data[0]['furniture']);
        $house_data[0]['key_list'] = explode(',',$house_data[0]['key_word']);
        $pic[0] = $house_data[0]['pic_1'];
        $pic[1] = $house_data[0]['pic_2'];
        $pic[2] = $house_data[0]['pic_3'];
        $pic[3] = $house_data[0]['pic_4'];
        $pic[4] = $house_data[0]['pic_5'];
        $pic[5] = $house_data[0]['pic_6'];
        $this->assign('key_word',$key_word);
		$this->assign('data',$house_data[0]);
		//print_r($house_data[0]);
        $this->assign('pic',$pic);
        $this->assign('station',$station);
		$this->assign('location',$location);
		$this->assign('line',$line);
        $this->assign('street',$street);
//        print_r($street);
//		print_r($line);
		$this->assign('furniture',$furniture_data);
		$this->assign('house_type',$house_type);

		return $this->fetch();
	}
	public function modify_from_data($id){
		$data = new BackstageModel;
		$result = $data->modify_form_data($_POST,$id);
        return $this->fetch();
	}
	public function delete_house($a){
		$arr = Db::execute("delete from house_rent_data where id={$a}");
		return $arr;
	}
	public function tuoguan_list(){
		$tuguan = DB::name('trusteeship')
				->select();
		foreach($tuguan as &$value){
			if($value['trust_type'] == 0){
				$value['type'] = '出售';
			}else{
				$value['type'] = '出租';
			}
		}
//		$tuguan = $tuguan::paginate(10);
		$this->assign('tuguan',$tuguan);
		return $this->fetch();
	}
	public function add_facilities(){
	    $arr = DB::name('furniture_data')->select();
	    //print_r($arr);
        $this->assign('facil',$arr);
	    return $this->fetch();
    }
    public function add_facil(){
	    $a = $_POST['facil'];
	    $res = DB::execute("insert into furniture_data (`furniture_name`,`furniture_pic`) values('{$a}','')");
	    return 1;
    }
    public function add_key(){
        $arr = DB::name('key_word')->select();
        //print_r($arr);
        $this->assign('facil',$arr);
        return $this->fetch();
    }
    public function add_key_fun(){
        $a = $_POST['key'];
        $res = DB::execute("insert into key_word (`key_word_name`) values('{$a}')");
        return 1;
    }
    public function del_facil(){
        $f = $_POST['facil'];
        $res = DB::execute("delete from furniture_data where t_id=$f");
        return 1;
    }
    public function del_key(){
        $f = $_POST['key'];
        $res = DB::execute("delete from key_word where k_id=$f");
        return 1;
    }
    public function add_house_type(){
        $arr = DB::name('house_type_data')->select();

        $this->assign('type',$arr);
        return $this->fetch();
    }
    public function add_house_fun(){
        $a = $_POST['type'];
        $res = DB::execute("insert into house_type_data (`house_type_name`) values('{$a}')");
        return 1;
    }
    public function del_house_type(){
        $f = $_POST['type'];
        $res = DB::execute("delete from house_type_data where t_id=$f");
        return 1;
    }
    public function shop_set(){
        $arr = DB::name('shopping_set')->where('s_p_id','=',0)->select();
        foreach($arr as &$val){
            $arr_1 = DB::name('shopping_set')->where('s_p_id','=',$val['s_id'])->column('s_name');
            $val['shopping'] = implode(',',$arr_1);
        }
        $this->assign('shop',$arr);
        return $this->fetch();
    }
    public function add_shop(){
        $type = $_POST['type'];
        $name = $_POST['shop_name'];
        if($type == 0){
            $res = DB::query("insert into shopping_set(`s_p_id`,`s_name`) values(0,'$name')");
        }else{
            $shop_id = $_POST['shop_id'];
            $res = DB::query("insert into shopping_set(`s_p_id`,`s_name`) values($shop_id,'$name')");
        }
        return $res;
    }
}