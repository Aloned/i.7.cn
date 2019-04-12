<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;


class subforum extends Base{
	//分论坛列表
    public function index()
    {
    	$count = Db::name('parallel_session')->count();
		$list = Db::name('parallel_session')->alias('ps')
            ->join('admin a','ps.admin_id = a.admin_id','LEFT')
            ->order('id desc')->paginate(11,$count);

		//获取分页显示
		$page = $list->render();
		$this->assign('list',$list);
		$this->assign('pager',$page);
		
    	return view();
    }

    //添加分论坛
    public function add(){
        //是否为POST请求
        $request = Request::instance();
        if($request->isPost()){
            $data = $request->param();
            unset($data['file']);
            $data['created_on'] = date('Y-m-d H:i:s',time());
            $res = Db::name('parallel_session')->insert($data); // 写入数据到数据库

            if($res){
                $msg = ['status'=>1,'msg'=>'添加成功','url'=>url('admin/subforum/index')];
            }else{
                $msg = ['status'=>0,'msg'=>'添加失败'];
            }
            return json($msg);
        }

        //管理员
        $adminList = Db::name('admin')->field('admin_id,true_name')->where('is_open = 1')->select();

        $this->assign('adminList',$adminList);
        return view();
    }

    //编辑单页内容
    public function edit(){
        //是否为POST请求
        $request = Request::instance();
        $adminList = Db::name('admin')->field('admin_id,true_name')->where('is_open = 1')->select();

        if($request->isPost()){
            $data = $request->except('file');
            $res = Db::name('parallel_session')->update($data); // 写入数据到数据库

            if($res){
                $msg = ['status'=>1,'msg'=>'更新成功','url'=>url('admin/subforum/index')];
            }else{
                $msg = ['status'=>0,'msg'=>'更新失败'];
            }
            return json($msg);
        }
        //单页内容详情
        $res = Db::name('parallel_session')->where('id',input('id'))->find();

        $this->assign('res',$res);
        $this->assign('adminList',$adminList);

        return view();
    }

    //编辑器文件上传
    public function layeditupload(){
        return editUpload('subForum');
    }

	//删除
	public function del(){
		$request = Request::instance();
		if($request->isPost()){
	    	$res = Db::name('parallel_session')->where('id',input('id'))->delete();
			
			if($res){
				$msg = ['status'=>1,'msg'=>'删除成功','url'=>url('admin/subForum/index')];
			}else{
				$msg = ['status'=>-2,'msg'=>'删除失败'];
			}
			
			return json($msg);
		}
	}
}
