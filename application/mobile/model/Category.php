<?php

namespace app\home\model;

use think\Model;
use think\Db;

class Category extends model {
	/**
	 * 获得指定分类下的子分类的数组
	 * @access  public
	 * @param   int     $cat_id     分类的ID
	 * @param   int     $selected   当前选中分类的ID
	 * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
	 * @param   int     $level      限定返回的级数。为0时返回所有级数
	 * @return  mix
	 */
	public function cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0) {
		
		global $category, $category2;
		$sql = "SELECT * FROM  md_category ORDER BY parent_id , sort_order ASC";
		$category = Db::query($sql);
		$category = convert_arr_key($category, 'cat_id');
		
		foreach ($category AS $key => $value) {
			if ($value['level'] == 1)
				$this -> get_cat_tree($value['cat_id']);
		}
		return $category2;
	}

	/**
	 * 获取指定id下的 所有分类
	 * @global type $category 所有分类
	 * @param type $id 当前显示的 菜单id
	 * @return 返回数组 Description
	 */
	public function get_cat_tree($id) {
		global $category, $category2;
		$category2[$id] = $category[$id];
		foreach ($category AS $key => $value) {
			if ($value['parent_id'] == $id) {
				$this -> get_cat_tree($value['cat_id']);
				$category2[$id]['have_son'] = 1;
				// 还有下级
			}
		}
	}
	
	/**
     *  获取选中的下拉框
     * @param type $cat_id
     */
    public function find_parent_cat($cat_id)
    {
        if($cat_id == null) 
            return array();
        
        $cat_list =  Db::name('category')->column('cat_id,parent_id,level');
        $cat_level_arr[$cat_list[$cat_id]['level']] = $cat_id;

        // 找出他老爸
        $parent_id = $cat_list[$cat_id]['parent_id'];
        if($parent_id > 0){
        	$cat_level_arr[$cat_list[$parent_id]['level']] = $parent_id;
			
			// 找出他爷爷
        	$grandpa_id = $cat_list[$parent_id]['parent_id'];
			if($grandpa_id > 0){
				$cat_level_arr[$cat_list[$grandpa_id]['level']] = $grandpa_id;
				
				// 建议最多分 3级, 不要继续往下分太多级
		        // 找出他祖父
		        $grandfather_id = $cat_list[$grandpa_id]['parent_id'];
				
				if($grandfather_id > 0){
					$cat_level_arr[$cat_list[$grandfather_id]['level']] = $grandfather_id;
				}
			}
        }
        
        return $cat_level_arr;      
    }
	
	/**
     * 改变或者添加分类时 需要修改他下面的 parent_id_path  和 level 
     * @global type $cat_list 所有分类
     * @param type $parent_id_path 指定的id
     * @return 返回数组 Description
     */
    public function refresh_cat($id){            
        $cat = Db::name('category')->where("cat_id",$id)->find(); // 找出他自己
        // 刚新增的分类先把它的值重置一下
        if($cat['parent_id_path'] == '')
        {
            ($cat['parent_id'] == 0) && Db::execute("UPDATE md_category set  parent_id_path = '0_$id', level = 1 where cat_id = $id"); // 如果是一级分类               
            Db::execute("UPDATE md_category AS a ,md_category AS b SET a.parent_id_path = CONCAT_WS('_',b.parent_id_path,'$id'),a.level = (b.level+1) WHERE a.parent_id=b.cat_id AND a.cat_id = $id");                
            $cat = Db::name('category')->where("cat_id", $id)->find(); // 从新找出他自己
        }        
        
        if($cat['parent_id'] == 0) //有可能是顶级分类 他没有老爸
        {
            $parent_cat['parent_id_path'] =   '0';   
            $parent_cat['level'] = 0;
        }
        else{
            $parent_cat = Db::name('category')->where("cat_id",$cat['parent_id'])->find(); // 找出他老爸的parent_id_path
        }        
        $replace_level = $cat['level'] - ($parent_cat['level'] + 1); // 看看他 相比原来的等级 升级了多少  ($parent_cat['level'] + 1) 他老爸等级加一 就是他现在要改的等级
        $replace_str = $parent_cat['parent_id_path'].'_'.$id;                
        Db::execute("UPDATE md_category SET parent_id_path = REPLACE(parent_id_path,'{$cat['parent_id_path']}','$replace_str'), level = (level - $replace_level) WHERE  parent_id_path LIKE '{$cat['parent_id_path']}%'");        
    }
    

	/**
	 * 移除指定$parent_id_path 分类以及下的所有分类
	 * @global type $cat_list 所有分类
	 * @param type $parent_id_path 指定的id
	 * @return 返回数组 Description
	 */
	public function remove_cat($cat_list, $parent_id_path) {
		foreach ($cat_list AS $key => $value) {
			if (strstr($value['parent_id_path'], $parent_id_path)) {
				unset($cat_list[$value['id']]);
			}
		}
		return $cat_list;
	}
}
