<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;
use think\Cache;
use think\Config;

class Feedback extends Base
{
	//首页
    public function index()
    {
		$list = Db::name('feedback')->select();
		
		$this->assign('list',$list);
		
		return view();
    }
	
	//添加自定义表单
	public function add(){
		$request = Request::instance();
		//获取表单数量
		$count = Db::name('feedback')->count();
		
		if($request -> isPost()){
			$data = $request ->param();
			
			$res = Db::name('feedback') -> insert($data);
			
			$diyid = Db::name('feedback')->getLastInsID();
			
			if($res){
				$msg = ['status' => 1,'msg'=>'添加成功','url'=>url('admin/feedback/index')];
				
				$tablename = "md_feedback_form".$diyid;
				
				$sql = "CREATE TABLE IF NOT EXISTS $tablename(
					`fid` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
					PRIMARY KEY (`fid`)
				)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
				Db::execute($sql);
			}else{
				$msg = ['status' => 0,'msg'=>'添加失败'];
			}
			
			return json($msg);
		}
		
		//表单名字
		$this->assign('title',"自定义表单".($count+1));
		//数据库表明
		$this->assign('table_name',"md_feedback_form".($count+1));
		
		return view();
	}
	
	//编辑自定义表单
	public function edit(){
		$request = Request::instance();
		$id = input('param.id/d');
		$data = $request->param();
		
		if($request->isPost()){
			$res = Db::name("feedback")->where('id',$id)->update($data);
			
			if($res){
				$msg = ['status'=>1,'msg'=>'更新成功','url'=>url('admin/feedback/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'更新失败'];
			}
			
			return json($msg);
		}
		
		$tablename = "md_feedback_form".$id;
		$form = Db::name("feedback")->where('id',$id)->find();
		//表信息
		$fields = Db::query("select column_name,column_comment,data_type,column_type from information_schema.columns where table_name='$tablename' and table_schema='hldahui'");
		
		$this->assign('fields',$fields);
		$this->assign('form',$form);
		
		return view();
	}
	
	//添加数据库表字段
	public function addfield(){
		$request = Request::instance();
		$diyid = input('param.diyid/d');
		
		//var_dump(Db::execute("select column_name from INFORMATION_SCHEMA.Columns where table_name='md_feedback_form1' and column_name='name'"));
		
		if($request -> isPost()){
			
			$data = $request->param();
			
			switch($data['type']){
				case 1:
					$type = "varchar(".$data['length'].")";
					break;
				case 2:
					$type = "text";
					break;
				case 3:
					$type = "int(".$data['length'].")";
					break;
				case 4:
					$type = "float(".$data['length'].")";
					break;
				case 5:
					$type = "varchar(".$data['length'].")";
					break;
				case 6:
					$type = "varchar(".$data['length'].")";
					break;
				case 7:
					$type = "varchar(".$data['length'].")";
					break;
			}
			
			$tablename = "md_feedback_form".$data['diyid'];
			$field = $data['field'];
			$default = $data['default'];
			$title = $data['title'];
			
			$has = "select column_name from INFORMATION_SCHEMA.Columns where table_name='$tablename' and column_name='$field'";
			if(Db::execute($has) < 1){
				$sql = "alter table $tablename add $field $type not null default '$default' COMMENT '$title'";
			
				if(Db::execute($sql) !== false){
					$msg = ['status'=>1,'msg'=>'添加字段成功','url'=>url('admin/feedback/index')];
				}else{
					$msg = ['status'=>0,'msg'=>'添加字段失败'];
				}
			}else{
				$msg = ['status'=>-1,'msg'=>'已存在此字段名'];
			}
			
			return json($msg);
		}
		
		$this->assign('diyid',$diyid);
		
		return view();
	}
	
	//更新网站设置
	public function updateConfig(){
		$request = Request::instance();
		
		if($request -> isPost()){
			$data = $request ->except('image');
			
			$res = Db::name('config') -> where('id',1) -> update($data);
			
			if($res){
				$msg = ['status' => 1,'msg'=>'更新成功','url'=>''];
			}else{
				$msg = ['status' => 0,'msg'=>'更新失败'];
			}
			
			return json($msg);
		}
	}

	//删除字段
	public function delField(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			$field = input('post.field');
			$tablename = input('post.tablename');
			
			$sql = "alter table $tablename drop column $field";
			
			if(Db::execute($sql) !== false){
				$msg = ['status'=>1,'msg'=>'删除字段成功','url'=>''];
			}else{
				$msg = ['status'=>0,'msg'=>'删除字段失败'];
			}
			
			return json($msg);
		}
	}
}
