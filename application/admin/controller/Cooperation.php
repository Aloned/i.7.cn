<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;


class Cooperation extends Base{
	//合作报名列表
    public function index()
    {
    	$count = Db::name('cooperation')->count();
		$list = Db::name('cooperation')->order('id desc')->paginate(11,$count);
		$type = getCooperationType();

		//获取分页显示
		$page = $list->render();
		$this->assign('type',$type);
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
    	return view();
    }

	//删除
	public function del(){
		$request = Request::instance();
		if($request->isPost()){
	    	$res = Db::name('cooperation')->where('id',input('id'))->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除成功','url'=>url('admin/cooperation/index')];
			}else{
				$msg = ['status'=>-2,'msg'=>'删除失败'];
			}
			
			return json($msg);
		}
	}

    //导出
    public function export()
    {
        $list = Db::name('cooperation')->select();
        $type = getCooperationType();

        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">ID</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">姓名</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">联系人电话</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">公司</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">职位</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">合作方式</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">报名日期</td>';
        $strTable .= '</tr>';
        if(is_array($list)){
            foreach($list as $k=>$val){
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['id'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['name'].' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['tel'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['company'].' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['position'].' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$type[$val['cooperation_ways']].' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['created_on'].' </td>';
                $strTable .= '</tr>';
            }
        }
        $strTable .='</table>';
        unset($list);
        downloadExcel($strTable,'商务合作报名信息');
        exit();
    }

}
