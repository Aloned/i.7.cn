<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;


class Photo extends Base{
    //图片列表
    public function index(){
        //分类id
        $id = input('param.type')?input('param.type'):0;
        if(isset($_GET['type'])){
            $id = $_GET['type'];
        }

        //筛选
        $map = [];
        if($id){
            $map['cat_id'] = $id;
        }
        //如果是合作伙伴
        if($id == 7){
            $ids = Db::name('category')->where('parent_id',$id)->column('cat_id');
            $map['cat_id'] = ['in',$ids];
        }

        //获取图片总数
        $count = Db::name('photo')->where($map)->count();

        $list = Db::view('photo','id,cat_id,title,description,thumb,content,is_show,is_hot,is_recommend,rank,addtime')
            ->view('category','cat_id,cat_name','photo.cat_id=category.cat_id')
            ->where($map)
            ->order('rank desc')
            ->paginate(21,$count);
//		var_dump($list);die;

        //获取分页显示
        $page = $list->render();

        $this->assign('list',$list);
        $this->assign('pager',$page);

        $this->assign('type',$id);

        return view();
    }

    //添加图片
    public function addphoto(){
        //是否为POST请求
        $request = Request::instance();

        $cat_list = Db::name('category')->where("mod_id = 2 and parent_id = 0")->select();
        $type = input('type')?input('type'):0;

        if($request->isPost()){
            // 排除数据库没有的字段  parent_id_1和parent_id_2
            $data = $request->except('file,image,parent_id_1,parent_id_2,addtime'); // 收集数据
            //重构 数据库字段 parent_id
            $data['cat_id'] = input('parent_id_1');
            if(input('parent_id_2')>0){
                $data['cat_id'] = input('parent_id_2');
            }
            $data['addtime'] = strtotime(input('addtime'));

            $res = Db::name('photo')->insert($data); // 写入数据到数据库

            if($res){
                $msg = ['status'=>1,'msg'=>'添加图片成功','url'=>url('admin/photo/index?type='.$data['cat_id'])];
            }else{
                $msg = ['status'=>0,'msg'=>'添加图片失败'];
            }
            return json($msg);
        }

        //当前时间
        $this->assign('curdate',time());
        $this->assign('cat_list',$cat_list);
        if($type == 50){
            return view('addphoto1');
        }
        if($type == 51){
            return view('addphoto2');
        }
        if($type == 52){
            return view('addphoto3');
        }
        if($type == 53){
            return view('addphoto4');
        }
        if($type == 54){
            return view('addphoto5');
        }
        if($type == 55){
            return view('addphoto6');
        }
        if($type == 56){
            return view('addphoto7');
        }
        $this->assign('type',$type);

        return view();
    }

    //编辑图片
    public function editphoto(){
        //是否为POST请求
        $request = Request::instance();
        $category = model('category');

        $cat_list = Db::name('category')->where("mod_id = 2 and parent_id = 0")->select();
        //获取当前分类id
        $cur_cat = Db::name('photo')->where('id',input('param.id'))->column('cat_id');
        $level_cat = $category->find_parent_cat($cur_cat[0]); // 获取分类默认选中的下拉框

        $type = input('type')?input('type'):0;
        if($request->isPost()){

            // 排除数据库没有的字段  parent_id_1和parent_id_2
            $data = $request->except('file,image,parent_id_1,parent_id_2,addtime,type'); // 收集数据
            //重构 数据库字段 parent_id
            $data['cat_id'] = input('parent_id_1');
            if(input('parent_id_2')>0){
                $data['cat_id'] = input('parent_id_2');
            }
            $data['addtime'] = strtotime(input('addtime'));

            $res = Db::name('photo')->update($data); // 写入数据到数据库

            if($res){
                $msg = ['status'=>1,'msg'=>'更新图片成功','url'=>url('admin/photo/index',['type'=>$data['cat_id']])];
            }else{
                $msg = ['status'=>0,'msg'=>'更新图片失败'];
            }
            return json($msg);
        }
        //图片详情
        $photo_info = Db::name('photo')->where('id',input('param.id'))->find();

        $imgslist = explode(",", $photo_info['imgs']);

        $this->assign('imgslist',$imgslist);
        $this->assign('imgscount',count($imgslist));
        $this->assign('photo',$photo_info);
        $this->assign('cat_list',$cat_list);
        $this->assign('level_cat',$level_cat);
        $this->assign('type',$type);
        if($type == 50){
            return view('editphoto1');
        }
        if($type == 51){
            return view('editphoto2');
        }
        if($type == 52){
            return view('editphoto3');
        }
        if($type == 53){
            return view('editphoto4');
        }
        if($type == 54){
            return view('editphoto5');
        }
        if($type == 55){
            return view('editphoto6');
        }
        if($type == 56){
            return view('editphoto7');
        }

        return view();
    }

    //删除说说
//	public function delphoto(){
//		//是否为POST请求
//		$request = Request::instance();
//		if($request->isPost()){
//			// 删除图片
//	    	$res = Db::name('photo')->where('id',input('id'))->delete();
//
//			if($res){
//				$msg = ['status'=>1,'msg'=>'删除图片成功','url'=>url('admin/photo/index')];
//			}else{
//				$msg = ['status'=>-2,'msg'=>'删除图片失败'];
//			}
//
//			return json($msg);
//		}
//	}
//删除说说
    public function delphoto(){
        //是否为POST请求
        $request = Request::instance();
        if($request->isPost()){
            // 删除图片
            $type = explode('-',input('id'));
            $res = Db::name('photo')->where('id',$type[0])->delete();

            if($res){
                $msg = ['status'=>1,'msg'=>'删除图片成功','url'=>url('admin/photo/index',['type'=>$type[1]])];
            }else{
                $msg = ['status'=>-2,'msg'=>'删除图片失败'];
            }

            return json($msg);
        }
    }

    //单文件上传
    public function upload(){
        return singleUpload('photo');
    }

    //多文件上传
    public function dupload(){
        return doubleUpload('photo');
    }

    //编辑器文件上传
    public function layeditupload(){
        return editUpload('photo');
    }
}
