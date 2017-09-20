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
	}
	public function house_detail($id){
		$data = new BaseDataModel;
		$location = $data->get_location_data();
		$line = $data->get_line_data();
		$house_type = $data->house_type_data();
		$furniture_data = $data->furniture_data();
		$house_data = DB::name('house_rent_data')
				->where('id',$id)
				->select();
		$house_data[0]['fur_list'] = explode(',',$house_data[0]['furniture']);

		$this->assign('data',$house_data[0]);
		$this->assign('location',$location);
		$this->assign('line',$line);
		$this->assign('furniture',$furniture_data);
		$this->assign('house_type',$house_type);

		return $this->fetch();
	}
	public function modify_from_data($id){
		$data = new BackstageModel;
		$result = $data->modify_form_data($_POST,$id);		
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
}