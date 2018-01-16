<?php

namespace app\index\model;

use think\Model;
use think\Db;

class BackstageModel extends Model
{
	public function get_form_data($response){
		$pic = array();
		$pic = explode(',', $response['pic_addr']);
		$pic = array_pad($pic,10,"0");
        if(isset($response['key_word'])){
            $key_word = implode(',',$response['key_word']);
        }else{
            return array('result'=>'失败','response'=>'请选择关键字');
        }

        if(isset($response['furniture'])){
            $furniture = implode(',', $response['furniture']);
        }else{
            return array('result'=>'失败','response'=>'请选择房屋设施');
        }
		$furniture = implode(',', $response['furniture']);
		$key_word = implode(',',$response['key_word']);
		$station = 	DB::name('underground_data')
					->where('u_id','=',$response['station'])
					->select();

        $time = date('Y-m-d H:i:s',time());
		$result = Db::execute("INSERT INTO `house_rent_data` (`title`, `key_word`, `price`, `rent_type`, `house_type`, `house_floor`, `underground`, `acreage`, `furniture`, `remark`, `longitude`, `latitude`, `pic_1`, `pic_2`, `pic_3`, `pic_4`, `pic_5`, `pic_6`,`pic_7`,`pic_8`,`pic_9`,`pic_10`, `house_level`, `district`, `street`, `addr`, `address`, `landlord`,`discount`,`orientation`,`pay_type`,`house_state_remark`,`shopping_nearby`,`rest_nearby`,`hospital_nearby`,`translate_nearby`,`house_status`,`landlord_tel`,`line`,`under_station`,`unit`,`doorplate`,`create_time`,`update_time`) VALUES ('{$response['house_title']}','{$key_word}',{$response['price']},{$response['rent_type']},'{$response['house_type']}','{$response['floor']}','{$station[0]['underground_name']}','{$response['acreage']}','{$furniture}','{$response['remarks']}','{$response['longitude']}','{$response['latitude']}','$pic[0]','$pic[1]','$pic[2]','$pic[3]','$pic[4]','$pic[5]','$pic[6]','$pic[7]','$pic[8]','$pic[9]',{$response['house_level']},'{$response['area']}','{$response['street']}','','{$response['village']}','{$response['landlord']}','{$response['discount']}','{$response['orientation']}','{$response['pay_type']}','{$response['house_state_remark']}','{$response['shopping_nearby']}','{$response['rest_nearby']}','{$response['hospital_nearby']}','{$response['translate_nearby']}','{$response['house_status']}','{$response['landlord_tel']}','{$response['underground']}','{$response['station']}','{$response['unit']}','{$response['doorplate']}','{$time}','{$time}');");
	}
	public function modify_form_data($response,$id){
	    //print_r($response);
        $pic = array();
        $pic = explode(',', $response['pic_addr']);

        if(isset($response['key_word'])){
            $key_word = implode(',',$response['key_word']);
        }else{
            return array('result'=>'失败','response'=>'请选择关键字');
        }

        if(isset($response['furniture'])){
            $furniture = implode(',', $response['furniture']);
        }else{
            return array('result'=>'失败','response'=>'请选择房屋设施');
        }

		$station = 	DB::name('underground_data')
					->where('u_id','=',$response['station'])
					->select();
        $time = date('Y-m-d H:i:s',time());
//        if($response['key_word'])
        if(empty($pic[0])){
            //print_r(111);
            //print_r("Update `house_rent_data` set `title`='{$response['house_title']}',`key_word`='{$key_word}',`price`={$response['price']},`rent_type`={$response['rent_type']}, `house_type`='{$response['house_type']}',`house_floor`='{$response['floor']}', `underground`='{$station[0]['underground_name']}', `acreage`='{$response['acreage']}', `furniture`='{$furniture}', `remark`='{$response['remarks']}', `longitude`='{$response['longitude']}',`latitude`='{$response['latitude']}',`house_level`={$response['house_level']},`district`={$response['area']},`street`={$response['street']},`addr`='{$response['addr']}',`address`='{$response['village']}',`landlord`='{$response['landlord']}',`discount`={$response['discount']},`orientation`='{$response['orientation']}',`pay_type`='{$response['pay_type']}',`house_state_remark`='{$response['house_state_remark']}',`shopping_nearby`='{$response['shopping_nearby']}',`rest_nearby`='{$response['rest_nearby']}',`hospital_nearby`='{$response['hospital_nearby']}',`translate_nearby`='{$response['translate_nearby']}',`house_status`={$response['house_status']},`landlord_tel`='{$response['landlord_tel']}',`line`='{$response['underground']}',`under_station`='{$response['station']}' where id='{$id}'");
            $result = Db::execute("Update `house_rent_data` set `title`='{$response['house_title']}',`key_word`='{$key_word}',`price`={$response['price']},`rent_type`={$response['rent_type']}, `house_type`='{$response['house_type']}',`house_floor`='{$response['floor']}', `underground`='{$station[0]['underground_name']}', `acreage`='{$response['acreage']}', `furniture`='{$furniture}', `remark`='{$response['remarks']}', `longitude`='{$response['longitude']}',`latitude`='{$response['latitude']}',`house_level`={$response['house_level']},`district`={$response['area']},`street`={$response['street']},`addr`='',`address`='{$response['village']}',`landlord`='{$response['landlord']}',`discount`={$response['discount']},`orientation`='{$response['orientation']}',`pay_type`='{$response['pay_type']}',`house_state_remark`='{$response['house_state_remark']}',`shopping_nearby`='{$response['shopping_nearby']}',`rest_nearby`='{$response['rest_nearby']}',`hospital_nearby`='{$response['hospital_nearby']}',`translate_nearby`='{$response['translate_nearby']}',`house_status`={$response['house_status']},`landlord_tel`='{$response['landlord_tel']}',`line`='{$response['underground']}',`under_station`='{$response['station']}',`unit`='{$response['unit']}',`doorplate`='{$response['doorplate']}',`update_time`='{$time}' where id='{$id}'");
            return array('result'=>'成功','response'=>'');
        }else{
            $result = Db::execute("Update `house_rent_data` set `title`='{$response['house_title']}',`key_word`='{$key_word}',`price`={$response['price']},`rent_type`={$response['rent_type']}, `house_type`='{$response['house_type']}',`house_floor`='{$response['floor']}', `underground`='{$station[0]['underground_name']}', `acreage`='{$response['acreage']}', `furniture`='{$furniture}', `remark`='{$response['remarks']}', `longitude`='{$response['longitude']}',`latitude`='{$response['latitude']}',`pic_1`='{$pic[0]}',`pic_2`='{$pic[1]}',`pic_3`='{$pic[2]}',`pic_4`='{$pic[3]}',`pic_5`='{$pic[4]}',`pic_6`='{$pic[5]}',`pic_7`='{$pic[6]}',`pic_8`='{$pic[7]}',`pic_9`='{$pic[8]}',`pic_10`='{$pic[9]}',`house_level`={$response['house_level']},`district`={$response['area']},`street`={$response['street']},`addr`='',`address`='{$response['village']}',`landlord`='{$response['landlord']}',`discount`={$response['discount']},`orientation`='{$response['orientation']}',`pay_type`='{$response['pay_type']}',`house_state_remark`='{$response['house_state_remark']}',`shopping_nearby`='{$response['shopping_nearby']}',`rest_nearby`='{$response['rest_nearby']}',`hospital_nearby`='{$response['hospital_nearby']}',`translate_nearby`='{$response['translate_nearby']}',`house_status`={$response['house_status']},`landlord_tel`='{$response['landlord_tel']}',`line`='{$response['underground']}',`under_station`='{$response['station']}',`unit`='{$response['unit']}',`doorplate`='{$response['doorplate']}',`update_time`='{$time}' where id='{$id}'");
            return array('result'=>'成功','response'=>'');
        }
	}
}