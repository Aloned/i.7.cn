<?php
namespace app\admin\controller;
require './vendor/phpexcel/Classes/PHPExcel/IOFactory.php';
use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;

class Store extends Base
{
    public function index1(){
        $list = Db::name('store')->select();
        foreach ($list as $value){
            $res = Db::name('admin')->where(['tel'=>$value['store_tel']])->find();
            if(!$res){
                //生成管理员信息
                $data1['role_id'] = 5;
                $data1['store_id'] = $value['store_id'];
                $data1['user_name'] = $value['store_tel'];
                $data1['true_name'] = $value['store_contact'];
                $data1['tel'] = $value['store_tel'];
                $data1['is_open'] = 1;
                $data1['password'] = md5('123456');
                $data1['add_time'] = time();
                $data1['last_login'] = time();
                $data1['last_ip'] = '';

                $res = Db::name('admin')->insert($data1);
            }
        }
        echo 12;
    }

    //资源审核列表
    public function resource()
    {
        $search = '';
        if(isset($_POST['keywords']) && !empty($_POST['keywords'])){
            if(is_numeric($_POST['keywords'])){
                //手机号
                $search = ['s.store_tel' => $_POST['keywords']];
            }else{
                //联系人
                $search = 's.store_contact like '.'"%'.$_POST['keywords'].'%"';
            }
        }
        $search2 = '';
        $search3 = '';
        $store_city = input('store_city');
        $is_pass = input('is_pass');
        if(isset($store_city) && !empty($store_city)){
            $search2 = ['store_city' =>$store_city];
        }

        if($is_pass ==='0' ){
            $search3 = ['is_pass' =>$is_pass];
        }else{
            if(!empty($is_pass)){
                $search3 = ['is_pass' =>$is_pass];
            }
        }

        $count = Db::name('store_resource')
            ->where($search)
            ->where($search2)
            ->where($search3)
            ->count();

        $list = Db::name('store_resource')->alias('sr')
            ->join('store s','sr.store_id = s.store_id','LEFT')
            ->where($search)
            ->where($search2)
            ->where($search3)
            ->order('sr.is_pass desc','sr.id desc')
            ->paginate(50,$count);

        $page = $list->render();

        if(isset($_POST['keywords']) && !empty($_POST['keywords'])){
            $this -> assign('keyword',$_POST['keywords']);
        }
        if($store_city){
            $this -> assign('cityId',$store_city);
        }else{
            $this -> assign('cityId','');
        }

        //获取城市列表
        $citylist = Db::name('city')->order('orderid asc')->select();
        $this->assign('status',array('待审核','审核通过','未通过'));
        $this->assign('citylist',$citylist);
        $this->assign('is_pass',$is_pass);
        $this -> assign('list',$list);
        $this->assign('count',$count);
        $this->assign('pager',$page);

        return view();
    }

    //审核页面
    public function checkresource(){
        //是否为POST请求
        $request = Request::instance();
        $data = $request->except('file');
        if($request->isPost()){
            $admininfo = getAdminInfo(session('admin_id'));
            $data['checked_on'] = date('Y-m-d H:i:s',time());
            $data['checked_by'] = $admininfo['true_name'];

            $res = Db::name('store_resource')->update($data);

            if($res){
                $msg = ['status'=>1,'msg'=>'处理完成','url'=>url('admin/store/resource')];
            }else{
                $msg = ['status'=>0,'msg'=>'处理失败,请稍后再试'];
            }
            return json($msg);
        }
        $res = Db::name('store_resource')->where(['id'=>input('id')])->find();
        $this->assign('res',$res);

        return view('checkresource');
    }

    //删除资源
    public function delresource(){
        //是否为POST请求
        $request = Request::instance();
        if($request->isPost()){
            $res = Db::name('store_resource')->where('id',input('id'))->delete();

            if($res){
                $msg = ['status'=>1,'msg'=>'资源删除成功','url'=>url('admin/store/resource')];
            }else{
                $msg = ['status'=>-2,'msg'=>'资源删除失败'];
            }

            return json($msg);
        }
    }


    //我的资源列表
    public function myresource()
    {
        $search = '';
        if(isset($_POST['keywords']) && !empty($_POST['keywords'])){
                $search = ['s.title' => $_POST['keywords']];
        }

        $store_id = session('store_id');

        $count = Db::name('store_resource')->where('store_id='.$store_id)->where($search)->count();

        $list = Db::name('store_resource')->alias('sr')
            ->join('store s','sr.store_id = s.store_id','LEFT')
            ->where($search)
            ->where('sr.store_id='.$store_id)
            ->order('sr.id','DESC')
            ->paginate(50,$count);

        $page = $list->render();

        if(isset($_POST['keywords']) && !empty($_POST['keywords'])){
            $this -> assign('keyword',$_POST['keywords']);
        }

        $this->assign('status',array('待审核','审核通过','未通过'));
        $this -> assign('list',$list);
        $this->assign('count',$count);
        $this->assign('pager',$page);

        return view();
    }


    //领票点资源上传
    public function uploadresource(){
        $request = Request::instance();
        if($request->isPost()){
            $store_id = session('store_id');
            $data = $request->except('file');
            $data['created_on'] = date('Y-m-d H:i:s',time());
            $data['store_id'] = $store_id;

            $res = Db::name('store_resource')->insert($data);
            if($res){
                $msg = ['status'=>1,'msg'=>'资源上传成功','url'=>url('admin/Store/myresource')];
            }else{
                $msg = ['status'=>0,'msg'=>'资源上传失败'];
            }
            return json($msg);
        }
        return view('uploadresource');
    }

    //编辑资源内容
    public function editresource(){
        //是否为POST请求
        $request = Request::instance();

        $data = $request->except('file');
        if($request->isPost()){
            $data['modified_on'] = date('Y-m-d H:i:s',time());

            $res = Db::name('store_resource')->update($data);

            if($res){
                $msg = ['status'=>1,'msg'=>'资源更新成功','url'=>url('admin/store/myresource')];
            }else{
                $msg = ['status'=>0,'msg'=>'资源更新失败'];
            }
            return json($msg);
        }
        $res = Db::name('store_resource')->where(['id'=>input('id')])->find();
        $this->assign('res',$res);

        return view('editresource');
    }

    //编辑器文件上传
    public function layeditupload(){
        return editUpload('content');
    }


    //领票点列表
    public function index()
    {
        $search = '';
        if(isset($_POST['keywords']) && !empty($_POST['keywords'])){
            if(is_numeric($_POST['keywords'])){
                //手机号
                $search = ['store_tel' => $_POST['keywords']];
            }else{
                //地址
                $search = 'store_name like '.'"%'.$_POST['keywords'].'%"';
            }
        }
        $search2 = '';
        if(isset($_POST['store_city']) && !empty($_POST['store_city'])){
                //手机号
                $search2 = ['store_city' => $_POST['store_city']];

        }
        if(isset($_GET['store_city']) && !empty($_GET['store_city'])){
            //手机号
            $search2 = ['store_city' => $_GET['store_city']];

        }
    	/*$admininfo = getAdminInfo(session('admin_id'));
		
    	switch($admininfo['role_id']){
			case 1:
				$map = '';
			break;
			case 2:
				//店员信息
				$map['role_id'] = 5;
			break;
		}*/
    	//select 得到二维数组  find 得到一维数组
    	$count = Db::name('store')->where($search)->where($search2)->count();
    	if(isset($_GET['type'])){
            $list = Db::name('store')->where($search)->where($search2)->order('store_id','DESC')->paginate(50,$count);
        }else{
            $list = Db::name('store')->where($search)->where($search2)->paginate(50,$count);
        }
		$page = $list->render();

        if(isset($_POST['keywords']) && !empty($_POST['keywords'])){
            $this -> assign('keyword',$_POST['keywords']);
        }
        if(isset($_POST['store_city']) && !empty($_POST['store_city'])){
            $this -> assign('cityId',$_POST['store_city']);
        }else{
            $this -> assign('cityId','');
        }

        //获取城市列表
        $citylist = Db::name('city')->order('orderid asc')->select();
        $this->assign('citylist',$citylist);
		$this -> assign('list',$list);
		$this->assign('count',$count);
		$this->assign('pager',$page);

    	return view();
    }
	
	//添加领票点
	public function add(){
		//是否为POST请求
		$request = Request::instance();
		
		if($request->isPost()){
			//请求参数
			$data = $request->param(); // 收集数据
			
			$id = Db::name('store')->insertGetId($data); // 写入数据到数据库

			if($id){
			    //生成管理员信息
                $data1['role_id'] = 5;
                $data1['store_id'] = $id;
                $data1['user_name'] = $data['store_tel'];
                $data1['true_name'] = $data['store_contact'];
                $data1['tel'] = $data['store_tel'];
                $data1['is_open'] = 1;
                $data1['password'] = md5(123456);
                $data1['add_time'] = time();
                $data1['last_login'] = time();
                $data1['last_ip'] = $request->ip();

                $res = Db::name('admin')->insert($data1);

				$msg = ['status'=>1,'msg'=>'添加领票点成功','url'=>url('admin/store/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'添加领票点失败'];
			}
			return json($msg);
		}
		
		//获取城市列表
		$citylist = Db::name('city')->order('orderid asc')->select();
		$this->assign('citylist',$citylist);
		
		return view();
	}
	
	//编辑领票点
	public function edit(){
		//是否为POST请求
		$request = Request::instance();
		
		$store_id = input('store_id');
		$store = Db::name('store')->where('store_id',$store_id)->find();
		
		if($request->isPost()){
			//请求参数
			$data = $request->param(); // 收集数据
			
			$res = Db::name('store')->update($data); // 写入数据到数据库
			
			if($res){
				$msg = ['status'=>1,'msg'=>'修改领票点成功','url'=>url('admin/store/index')];
			}else{
				$msg = ['status'=>0,'msg'=>'修改领票点失败'];
			}
			return json($msg);
		}
		//获取城市列表
		$citylist = Db::name('city')->order('orderid asc')->select();
		$this->assign('citylist',$citylist);
		$this->assign('store',$store);
		
		return view();
	}
	
	//删除角色
	public function del(){
		//是否为POST请求
		$request = Request::instance();
		if($request->isPost()){
			// 删除文章
	    	$res = Db::name('store')->where('store_tel',input('id'))->delete();
	    	Db::name('admin')->where('user_name',input('id'))->delete();

			if($res){
				$msg = ['status'=>1,'msg'=>'删除领票点成功','url'=>url('admin/store/index')];
			}else{
				$msg = ['status'=>-2,'msg'=>'删除领票点失败'];
			}
			
			return json($msg);
		}
	}

	//excel导入
    public function importExp(){
        $request = Request::instance();
	    $file = $request->file('excel');
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public'. DS .'uploads');
	        if($info){
                $citylist = Db::name('city')->order('orderid asc')->select();
                foreach ($citylist as $key => $value) {
                    $cityName[$value['id']] = $value['cityname'];
                }

	            $path = $info->getPathInfo();
	            $name = $info->getFilename();
	            $newName = $path.'/'.$name;
	            if (substr(strrchr($newName, '.'), 1) == 'xls') {
                $reader = \PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
            } else {
                $reader = \PHPExcel_IOFactory::createReader('Excel2007'); //设置以Excel5格式(Excel2007工作簿)
            }
                $PHPExcel = $reader->load($newName); // 载入excel文件
                $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
                $highestRow = $sheet->getHighestRow(); // 取得总行数
                $highestColumm = $sheet->getHighestColumn(); // 取得总列数
                for ($row = 1; $row <= $highestRow; $row++) {//行数是以第1行开始
                    if ($row == 1) {
                        continue;
                    }
                    $arr = array();

                    for ($column = 'A'; $column <= $highestColumm; $column++) { //列数是以A列开始
                        if ($column == 'A') {
                            $city = $sheet->getCell('A' . $row)->getValue();
                            $city = preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/","",$city);
                            if($city == ''){
                                die('批量导入完毕');
                            }
                            $key = array_search($city,$cityName);
                            if($key){
                                $arr['store_city'] = $key;
                            }else{
                                die('第'.$row.'行错误：城市名称有误');
                            }

                        }
                        if ($column == 'B') {
                            $arr['store_name'] = $sheet->getCell('B' . $row)->getValue();
                        }
                        if ($column == 'C') {
                            $arr['store_contact'] = $sheet->getCell('C' . $row)->getValue();
                        }
                        if ($column == 'D') {
                            $arr['store_tel'] = intval($sheet->getCell('D' . $row)->getValue());
                        }
                        if ($column == 'E') {
                            $arr['store_amount'] = intval($sheet->getCell('E' . $row)->getValue());
                        }
                        if ($column == 'F') {
                            $arr['store_address'] = $sheet->getCell('F' . $row)->getValue();
                        }
                        if ($column == 'G') {
                            $arr['store_desc'] = empty($sheet->getCell('G' . $row)->getValue())?'':$sheet->getCell('G' . $row)->getValue();
                        }
                    }
                    $res = Db::name('store')->where(['store_tel'=>$arr['store_tel']])->find();
                    if($res){continue;};
                    //插入数据
                    $id = Db::name('store')->insertGetId($arr); // 写入数据到数据库

                    if($id){
                        //生成管理员信息
                        $res = Db::name('admin')->where(['tel'=>$arr['store_tel']])->find();
                        if(!$res){
                            $data1['role_id'] = 5;
                            $data1['store_id'] = $id;
                            $data1['user_name'] = $arr['store_tel'];
                            $data1['true_name'] = $arr['store_contact'];
                            $data1['tel'] = $arr['store_tel'];
                            $data1['is_open'] = 1;
                            $data1['password'] = md5(123456);
                            $data1['add_time'] = time();
                            $data1['last_login'] = time();
                            $data1['last_ip'] = $request->ip();

                            Db::name('admin')->insert($data1);
                        }
                    }else{
                        die('添加领票点失败');
                    }
                }
                $this->success('批量导入领票点成功', 'admin/store/index');
            }else{
	            die('没有权限');
            }
        }else{
	        die('文件未选择');
        }
    }

    //导出报名数据
    public function export()
    {
        $list = Db::name('store')->select();
        $citylist = Db::name('city')->order('orderid asc')->select();
        foreach ($citylist as $key => $value) {
            $cityName[$value['id']] = $value['cityname'];
        }

        $strTable ='<table width="500" border="1">';
        $strTable .= '<tr>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">所在城市</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">领票点名称</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">联系人</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="150">联系人电话</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">总票数</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">已领票数</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="*">余票数</td>';
        $strTable .= '<td style="text-align:center;font-size:12px;" width="150">地址</td>';
        $strTable .= '</tr>';
        if(is_array($list)){
            foreach($list as $k=>$val){
                $strTable .= '<tr>';
                $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$cityName[$val['store_city']].'</td>';
                $strTable .= '<td style="text-align:center;font-size:12px;">&nbsp;'.$val['store_name'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['store_contact'].' </td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['store_tel'].'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['store_amount'].' </td>';
                $count = Db::name('user')->where(['store_id'=>$val['store_id'],'is_show'=>1])->count();
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$count.'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.($val['store_amount']-$count).'</td>';
                $strTable .= '<td style="text-align:left;font-size:12px;">'.$val['store_address'].'</td>';
                $strTable .= '</tr>';
            }
        }
        $strTable .='</table>';
        unset($list);
        downloadExcel($strTable,'StoreList');
        exit();
    }

}
