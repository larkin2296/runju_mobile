<?php

namespace app\index\model;

use think\Model;
use think\Db;

class BackstageModel extends Model
{
	public function get_form_data($response){
		$pic = array();
		$pic = explode(',', $response['pic_addr']);
		$pic = array_pad($pic,6,"0");
		$furniture = implode(',', $response['furniture']);
		$key_word = implode(',',$response['key_word']);
		$station = 	DB::name('underground_data')
					->where('u_id','=',$response['station'])
					->select();
		if(empty($response['key_word']) ){
            $response['key_word'] == '0';
        }

		$result = Db::execute("INSERT INTO `house_rent_data` (`title`, `key_word`, `price`, `rent_type`, `house_type`, `house_floor`, `underground`, `acreage`, `furniture`, `remark`, `longitude`, `latitude`, `pic_1`, `pic_2`, `pic_3`, `pic_4`, `pic_5`, `pic_6`, `house_level`, `district`, `street`, `addr`, `address`, `landlord`,`discount`,`orientation`,`pay_type`,`house_state_remark`,`shopping_nearby`,`rest_nearby`,`hospital_nearby`,`translate_nearby`,`house_status`,`landlord_tel`) VALUES ('{$response['house_title']}','{$key_word}','{$response['price']}',{$response['rent_type']},'{$response['house_type']}','{$response['floor']}','{$station[0]['underground_name']}','{$response['acreage']}','{$furniture}','{$response['remarks']}','{$response['longitude']}','{$response['latitude']}','$pic[0]','$pic[1]','$pic[2]','$pic[3]','$pic[4]','$pic[5]','0','{$response['area']}','{$response['street']}','{$response['addr']}','{$response['village']}','{$response['landlord']}','{$response['discount']}','{$response['orientation']}','{$response['pay_type']}','{$response['house_state_remark']}','{$response['shopping_nearby']}','{$response['rest_nearby']}','{$response['hospital_nearby']}','{$response['translate_nearby']}','{$response['house_status']}','{$response['landlord_tel']}');");
	}
	public function modify_form_data($response,$id){
        $pic = array();
        $pic = explode(',', $response['pic_addr']);
        $key_word = implode(',',$response['key_word']);
		$furniture = implode(',', $response['furniture']);
		$station = 	DB::name('underground_data')
					->where('u_id','=',$response['station'])
					->select();
//        if($response['key_word'])
		$result = Db::execute("Update `house_rent_data` set `title`='{$response['house_title']}',`key_word`='{$key_word}',`price`='{$response['price']}',`rent_type`={$response['rent_type']}, `house_type`='{$response['house_type']}',`house_floor`='{$response['floor']}', `underground`='{$station[0]['underground_name']}', `acreage`='{$response['acreage']}', `furniture`='{$furniture}', `remark`='{$response['remarks']}', `longitude`='{$response['longitude']}',`latitude`='{$response['latitude']}',`pic_1`='{$pic[0]}',`pic_2`='{$pic[1]}',`pic_3`='{$pic[2]}',`pic_4`='{$pic[3]}',`pic_5`='{$pic[4]}',`pic_6`='{$pic[5]}',`house_level`=0,`district`={$response['area']},`street`={$response['street']},`addr`='{$response['addr']}',`address`='{$response['village']}',`landlord`='{$response['landlord']}',`discount`={$response['discount']},`orientation`='{$response['orientation']}',`pay_type`='{$response['pay_type']}',`house_state_remark`='{$response['house_state_remark']}',`shopping_nearby`='{$response['shopping_nearby']}',`rest_nearby`='{$response['rest_nearby']}',`hospital_nearby`='{$response['hospital_nearby']}',`translate_nearby`='{$response['translate_nearby']}',`house_status`={$response['house_status']},`landlord_tel`='{$response['landlord_tel']}' where id='{$id}'");
	}
}