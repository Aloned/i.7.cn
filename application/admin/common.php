<?php

use think\Db;

/**
 * 管理员操作记录
 * @param $log_url 操作URL
 * @param $log_info 记录信息
 */
function adminLog($log_info){
    $add['log_time'] = time();
    $add['admin_id'] = session('admin_id');
    $add['log_info'] = $log_info;
    $add['log_ip'] = request()->ip();
    $add['log_url'] = request()->baseUrl() ;
    Db::name('admin_log')->insert($add);
}

function getAdminInfo($admin_id){
	//获取管理员信息
	$res = Db::name('admin') -> where('admin_id',$admin_id) -> find();
	return $res;
}

//单文件上传 $floder 文件夹名字
function singleUpload($folder){
	// 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('image');
	
	// 移动到框架应用根目录/public/uploads/ 目录下
    if($file){
        $info = $file->move(ROOT_PATH . 'uploads' . DS . $folder);
        if($info){
        	// 成功上传后 获取上传信息
        	// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        	$src = '/uploads/' . $folder . DS . $info->getSaveName();
        	$msg = [
        		'status'  =>1,
        		// 输出 jpg
        		'imgtype' => $info->getExtension(),
        		// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        		'src'     => str_replace('\\','/', $src),
        		// 输出 42a79759f284b767dfcb2a0197904287.jpg
        		'name'    => $info->getFilename(),
        		'msg'     => '上传成功'
        	];
			
			return json($msg);
        }else{
            // 上传失败获取错误信息
            $msg = ['status'=>0,'msg'=>$file->getError()];
			
			return json($msg);
        }
    }
}

//单文件上传 $floder 文件夹名字
function editUpload($folder){
	// 获取表单上传文件 例如上传了001.jpg
    $file = request()->file('file');
	
	// 移动到框架应用根目录/public/uploads/ 目录下
    if($file){
        $info = $file->move(ROOT_PATH . 'uploads' . DS . $folder);
        if($info){
        	// 成功上传后 获取上传信息
        	// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        	$src = '/uploads/' . $folder . DS . $info->getSaveName();
        	$msg = [
        		'code'  =>0,
				'msg'   => '上传成功',
				'data'  =>[
					'title'    => $info->getFilename(),
					// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
					'src'     => str_replace('\\','/', $src)
				]
        	];
			
			return json($msg);
        }else{
            // 上传失败获取错误信息
            $msg = ['code'=>1,'msg'=>$file->getError()];
			
			return json($msg);
        }
    }
}

/*多文件上传*/
function doubleUpload($folder){
	// 获取表单上传文件 例如上传了001.jpg
    $files = request()->file('image');
	
	foreach($files as $file){
		$info = $file->move(ROOT_PATH . 'uploads' . DS . $folder);
	
        if($info){
        	// 成功上传后 获取上传信息
        	// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        	$src = '/uploads/' . $folder . DS . $info->getSaveName();
			//$thumb = '/uploads/thumb/'. DS . $info->getSaveName();
			
			//$thumbsrc = \think\Image::open($thumb);
			//$thumbsrc = $thumbsrc->thumb(92, 92,1)->save($thumb);//生成缩
			
        	$msg = [
        		'status'  =>1,
        		// 输出 jpg
        		'imgtype' => $info->getExtension(),
        		// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
        		'src'     => str_replace('\\','/', $src),
        		//'thumb'   => str_replace('\\','/', $thumbsrc),
        		// 输出 42a79759f284b767dfcb2a0197904287.jpg
        		'name'    => $info->getFilename(),
        		'msg'     => '上传成功'
        	];
			
			return json($msg);
        }else{
            // 上传失败获取错误信息
            $msg = ['status'=>0,'msg'=>$file->getError()];
			
			return json($msg);
        }
	}
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

// 递归删除文件夹
function delFile($path,$delDir = FALSE) {
    if(!is_dir($path))
                return FALSE;		
	$handle = @opendir($path);
	if ($handle) {
		while (false !== ( $item = readdir($handle) )) {
			if ($item != "." && $item != "..")
				is_dir("$path/$item") ? delFile("$path/$item", $delDir) : unlink("$path/$item");
		}
		closedir($handle);
		if ($delDir) return rmdir($path);
	}else {
		if (file_exists($path)) {
			return unlink($path);
		} else {
			return FALSE;
		}
	}
}

//获取权限
function getaccess($url){
	$menu = config('menu');
	$id = '';
	foreach($menu as $key=>$val){
		foreach($val['children'] as $k=>$v){
			if($v['href'] == $url){
				$id = $val['id'];
			}
		}
	}
	
	return $id;
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

//指定领票点的余票数
function specTicket($id){
	$count = Db::name('user')->where(['store_id'=>$id,'is_show'=>1])->count();
	return $count;
}

//指定用户的邀请人数
function inviteNumber($ucode){
	$count = Db::name('user')->where('ufrom',$ucode)->count();
	return $count;
}

//指定用户的邀请人数
function downNumber($ucode){
    $count = Db::name('user')->where('ufrom',$ucode)->where(['is_show'=>1])->count();
    return $count;
}

//报名总人数
function signTotal(){
	$count = Db::name('user')->count();
	return $count;
}

//上传资源数
function resourceCount($store_id){
    $count = Db::name('store_resource')->where('store_id',$store_id)->count();
    return $count;
}

//审核通过资源数
function resourcePassCount($store_id){
    $count = Db::name('store_resource')->where('store_id',$store_id)->where('is_pass=1')->count();
    return $count;
}

/**
 * 导出excel
 * @param $strTable	表格内容
 * @param $filename 文件名
 */
function downloadExcel($strTable,$filename)
{
	header("Content-type: application/vnd.ms-excel");
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=".$filename."_".date('Y-m-d').".xls");
	header('Expires:0');
	header('Pragma:public');
	echo '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$strTable.'</html>';
}

//获取商务合作类型字典
function getCooperationType(){
    return array('','冠名/分论坛冠名','主办方','协办方','参展方');
}