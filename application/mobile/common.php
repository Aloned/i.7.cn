<?php

use think\Db;

//获取用户信息
function getUserInfo($id){
	$res = Db::name('user') -> where('user_id',$id) -> find();
	return $res;
}

/**
 * @param $arr
 * @param $key_name
 * @return array
 * 将数据库中查出的列表以指定的 id 作为数组的键名 
 */
function convert_arr_key($arr, $key_name){
	$arr2 = array();
	foreach($arr as $key => $val){
		$arr2[$val[$key_name]] = $val;        
	}
	return $arr2;
}

function get_parent_list($arr,$id){
    //$arr 所有分类列表
    //$id 父级分类id
    $list=array();
    foreach($arr as $val){

        if($val['parent_id'] == $id){//父级分类id等于所查找的id
        	$val['children'] = get_parent_list($arr,$val['cat_id']);
            $list[] = $val;
        }
    }
    return $list;
}
//指定领票点的余票数
function specTicket($id){
	$count = Db::name('user')->where(['store_id'=>$id,'is_show'=>1])->count();
	
	return $count;
}
//随机生成邀请码
function randomkeys($length) {
    $str = '';
	$strPol = "A0B1CDEFG2HIJ3KLMN6OP4Q9RST5UV8WX7YZ";//如果不需要小写字母，可以把小写字母都删除
	$max = strlen($strPol)-1;
	for($i=0;$i<$length;$i++){
	    $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	}
	return $str;
}
//指定用户的邀请人数
function inviteNumber($ucode){
	$count = Db::name('user')->where('ufrom',$ucode)->count();
	
	return $count;
}

//指定用户邀请的已领票人数
function invitedNumber($ucode){
	$count = Db::name('user')->where(['ufrom'=>$ucode,'is_show'=>1])->count();
	
	return $count;
}

//指定用户的邀请人数
function downNumber($ucode){
    $count = Db::name('user')->where('ufrom',$ucode)->where(['is_show'=>1])->count();

    return $count;
}

//指定用户还差多少成为VIP
function poorNumber($ucode){
    $num = invitedNumber($ucode);
    if($num < 10){
        $count = 10 - $num;
        return '距升级VIP票还差'.$count.'人';
    }else{
        return '您已生成'.floor($num/10).'张VIP门票，继续加油!';
    }


    return $count;
}

//报名总人数
function signTotal(){
	$count = Db::name('user')->count();
	
	return $count;
}

function sortArr($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){  
    if(is_array($arrays)){  
        foreach ($arrays as $array){  
            if(is_array($array)){  
                $key_arrays[] = $array[$sort_key];  
            }else{  
                return false;  
            }  
        }  
    }else{  
        return false;  
    } 
    array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
    return array_slice($arrays,0,100);
}