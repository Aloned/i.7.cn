<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Config;


class Index extends Base
{
    public function index()
    {
    	//获取管理员信息
    	$admin_info = getAdminInfo(session('admin_id'));
		$this->assign('admin_info',$admin_info);

    	return view();
    }
	//读取配置菜单
	//菜单
	public function menu()
	{
        $menulist = config('menu');
		$rolelist = explode(',', session('act_list'));

		foreach($menulist as $key=>$val){
			//判断数组中是否有某个元素
			if(in_array($val['id'], $rolelist)){
				$menu[$key] = array(
					'id'=>$val['id'],
					'title'=>$val['title'],
					'icon'=>$val['icon'],
					'spread'=>$val['spread']);
			}
			foreach($val['children'] as $key2=>$val2){
				//判断数组中是否有某个元素
				if(in_array($val2['id'], $rolelist)){
					$menu[$key]['children'][] = array(
						'id'=>$val2['id'],
						'title'=>$val2['title'],
						'icon'=>$val2['icon'],
						'href'=>$val2['href']);
				}
			}
		}

		foreach($menu as $key=>$val){
			$menus[] = $val;
		}

		return json($menus);
	}

	//首页主要内容

	public function main()
	{
		$visit = $this->late_sevendays_visit();

		$this->assign('visittime',json_encode($visit['time']));
		//pv统计
		$this->assign('pvvisit',json_encode($visit['data']));

		$this->assign('sys_info',$this->get_sys_info());
		return view();
	}

	//获取系统信息
	public function get_sys_info(){
		$sys_info['os']             = PHP_OS;
		$sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
		$sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off
		$sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
		$sys_info['curl']			= function_exists('curl_init') ? 'YES' : 'NO';
		$sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
		$sys_info['phpv']           = phpversion();
		$sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
		$sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
		$sys_info['max_ex_time'] 	= @ini_get("max_execution_time").'s'; //脚本最大执行时间
		$sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
		$sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
		$sys_info['memory_limit']   = ini_get('memory_limit');
		$mysqlinfo = Db::query("SELECT VERSION() as version");
		$sys_info['mysql_version']  = $mysqlinfo[0]['version'];
		if(function_exists("gd_info")){
			$gd = gd_info();
			$sys_info['gdinfo'] 	= $gd['GD Version'];
		}else {
			$sys_info['gdinfo'] 	= "未知";
		}
		return $sys_info;
    }

	//近7天销售量统计
	public function late_sevendays_visit(){
		$begin = strtotime(date('Y-m-d', strtotime("-7 days")));//7天前
        $end = strtotime(date('Y-m-d', strtotime('-1 days')))+86399;

		$sql = "SELECT COUNT(*) as num,FROM_UNIXTIME(addtime,'%Y-%m-%d') as gap from md_user group by gap";
		$new = Db::query($sql);//新增会员趋势
		foreach ($new as $val){
			$arr[$val['gap']] = $val['num'];
		}

		for($i=$begin;$i<=$end;$i=$i+24*3600){
			$brr[] = empty($arr[date('Y-m-d',$i)]) ? 0 : $arr[date('Y-m-d',$i)];
			$day[] = date('Y-m-d',$i);
		}
		$result = array('data'=>$brr,'time'=>$day);

		return $result;
	}
}
