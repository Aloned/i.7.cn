<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;


class Category extends Base{
	//分类列表
    public function index()
    {
    	$category = model('Category');
		$cat_list = $category ->cat_list();
		$module_list = config('module');
		
		$this->assign('cat_list',$cat_list);
		$this->assign('module_list',$module_list);
		
    	return view();
    }
	
	//添加分类
	public function addcategory(){
		//是否为POST请求
		$request = Request::instance();

		$category = model('category');
		$cat_list = Db::name('category')->where("parent_id = 0")->select();
		$module_list = config('module');
		
		if($request->isPost()){
			// 排除数据库没有的字段  parent_id_1和parent_id_2
			$data = $request->except('image,parent_id_1,parent_id_2'); // 收集数据
			//重构 数据库字段 parent_id
            $data['parent_id'] = input('parent_id_1');
			if(input('parent_id_2')>0){
				$data['parent_id'] = input('parent_id_2');
			}

			$res = Db::name('category')->insert($data); // 写入数据到数据库
            $insert_id = Db::name('category')->getLastInsID();
            $category->refresh_cat($insert_id);
			
			//模型5
			switch($data['mod_id']){
				case 5:
					Db::name('content')->insert(['cat_id'=>$insert_id,'title'=>$data['cat_name']]);
				break;
			}
			
			if($res){
				$msg = ['status'=>1,'msg'=>'添加分类成功','url'=>url('admin/category/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加分类失败'];
			}
			return json($msg);
		}
		
		$this->assign('cat_list',$cat_list);
		$this->assign('module_list',$module_list);
		
		return view();
	}
	
	//编辑分类
	public function editcategory(){
		//是否为POST请求
		$request = Request::instance();
		
		$category = model('category');
		$cat_list = Db::name('category')->where(['parent_id'=>0,'mod_id'=>input('mod_id')])->select();
		$level_cat = $category->find_parent_cat(input('cat_id',0)); // 获取分类默认选中的下拉框
		$module_list = config('module');
		
		if($request->isPost()){
			
			// 排除数据库没有的字段  parent_id_1和parent_id_2
			$data = $request->except('image,parent_id_1,parent_id_2'); // 收集数据
			//重构 数据库字段 parent_id
            $data['parent_id'] = input('parent_id_1');
			if(input('parent_id_2')>0){
				$data['parent_id'] = input('parent_id_2');
			}
			
			$children_where = [
                'parent_id_path'=>['like','%_'.input('cat_id')."_%"]
            ];
            $children = Db::name('category')->where($children_where)->max('level');
			
			if (input('parent_id_1')) {
                $parent_level = Db::name('category')->where(['cat_id' => input('parent_id_1')])->column('level');
                if (($parent_level[0] + $children) > 4) {
                	
                    $msg = ['status' => -1,'msg'=> $parent_level[0].'分类最多为三级'.$children];
                    
					return json($msg);
                }
            }
            if (input('parent_id_2')) {
                $parent_level = Db::name('category')->where(['id' => input('parent_id_2')])->column('level');
                if (($parent_level[0] + $children) > 4) {
                	
                    $msg = ['status' => -1,'msg'   => '分类最多为三级'];
                    
					return json($msg);
                }
            }
			
			$res = Db::name('category')->update($data); // 写入数据到数据库
            $category->refresh_cat(input('cat_id'));
						
			//模型5
			switch($data['mod_id']){
				case 5:
					Db::name('content')->where('cat_id',input('cat_id'))->setField('title',$data['cat_name']);
				break;
			}
			
			if($res){
				$msg = ['status'=>1,'msg'=>'更新分类成功','url'=>url('admin/category/index')];
				
				return json($msg);
			}else{
				$msg = ['status'=>0,'msg'=>'更新分类失败'];
				
				return json($msg);
			}
		}
		
		$category_info = Db::name('category')->where('cat_id',input('cat_id'))->find();
		$this->assign('category',$category_info);
		$this->assign('cat_list',$cat_list);
		$this->assign('module_list',$module_list);
		$this->assign('level_cat',$level_cat);

		return view();
	}

	//删除分类
	public function delcategory(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$cat_id = input('param.id');
			// 判断子分类
	        $count = Db::name('category')->where("parent_id",$cat_id)->count("cat_id");
			
			if($count > 0){
				$msg = ['status' => 0, 'msg' => '该分类下还有分类不得删除', 'url' => ''];
			}else{
				// 删除分类
	        	$res = Db::name('category')->where('cat_id',$cat_id)->delete();
				
				if($res){
					$msg = ['status'=>1,'msg'=>'删除分类成功','url'=>url('admin/category/index')];
					
					//模型5
					switch(input('param.mid')){
						case 5:
							Db::name('content')->where('cat_id',$cat_id)->delete();
						break;
					}
					
				}else{
					$msg = ['status'=>-2,'msg'=>'删除分类失败'];
				}
			}
			return json($msg);
		}
	}
	
	//获取指定id下的分类
	public function getCategory(){
		//是否为Get请求
		$request = Request::instance();
		if($request->isGet()){
			$parent_id = input('param.parent_id/d'); // 分类 父id
            $list = Db::name('category')->where('parent_id',$parent_id)->select();
        
			$html = '';
	        foreach($list as $k => $v)
	            $html .= "<option value='{$v['cat_id']}'>{$v['cat_name']}</option>";        
	        //exit($html);
	        
	        return json($html);
		}
    }  
    
	//获取指定模型下的分类
	public function getmCategory(){
		//是否为Get请求
		$request = Request::instance();
		if($request->isGet()){
			$mod_id = input('param.mod_id/d'); // 模型id
            $list = Db::name('category')->where(['mod_id'=>$mod_id,'parent_id'=>0])->select();
        
			$html = '';
	        foreach($list as $k => $v)
	            $html .= "<option value='{$v['cat_id']}'>{$v['cat_name']}</option>";        
	        //exit($html);
	        
	        return json($html);
		}
    }
	
	//单文件上传
	public function upload(){
		return singleUpload('cate');
	}
}
