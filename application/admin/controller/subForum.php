<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;


class subForum extends Base{
	//分论坛列表d
    public function index()
    {
    	$count = Db::name('parallel_session')->count();
		$list = Db::name('parallel_session')->order('id desc')->paginate(11,$count);

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

            $data['addtime'] = time();

            $res = Db::name('content')->where('cat_id',input('cat_id'))->update($data); // 写入数据到数据库

            if($res){
                $msg = ['status'=>1,'msg'=>'更新单页内容成功','url'=>url('admin/content/index')];
            }else{
                $msg = ['status'=>0,'msg'=>'更新单页内容失败'];
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

        $data = $request->except('file');

        $category = Db::name('category')->where(['mod_id'=>5,'cat_id'=>input('param.cat_id')])->find();

        if($request->isPost()){

            $data['addtime'] = time();

            $res = Db::name('content')->where('cat_id',input('cat_id'))->update($data); // 写入数据到数据库

            if($res){
                $msg = ['status'=>1,'msg'=>'更新单页内容成功','url'=>url('admin/content/index')];
            }else{
                $msg = ['status'=>0,'msg'=>'更新单页内容失败'];
            }
            return json($msg);
        }
        //单页内容详情
        $content = Db::name('content')->where('cat_id',input('cat_id'))->find();

        $this->assign('content',$content);
        $this->assign('category',$category);

        return view();
    }

    //编辑器文件上传
    public function layeditupload(){
        return editUpload('content');
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
}
