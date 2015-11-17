<?php
namespace Backend\Controller\Base;
use Backend\Controller\Base\AdminController;
class AreaController extends AdminController {
	protected $table='area';
	protected $cache_data='area_data';/*缓存数据名称*/
	protected $cache_name='area_cache';/*缓存数据名称*/
	/*
	 * 菜单列表页
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function index(){
		/*检查地区缓存*/
		get_area_cache();
		
		$map['pid']=0;
		if(I('pid')){
			$map['pid']=intval(I('pid'));
		}
		
		$keywords=I('keywords');
		if($keywords){
			$map['title']=array('like', "%$keywords%");
		}
		$map['status']=array('gt',-1);
		$result=$this->page($this->table, $map,'id asc');
		$result=int_to_string($result);
		$data['result']=$result;
		
		$this->assign($data);
		$this->display();
	}
	/*
	 * 添加菜单
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function add(){
		if(IS_POST){
			$this->update();
		}else{
			$this->display('operate');
		}
	}
	/*
	 * 修改菜单
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function edit(){
		if(IS_POST){
			$this->update();
		}else{
			$id=intval(I('id'));
			$map['id']=$id;
			$data['info']=M($this->table)->where($map)->find();
			$this->assign($data);
			$this->display('operate');
		}
	}
	/*
	 * 添加/修改操作
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function update(){
		if(IS_POST){
			$id=intval(I('post.id'));
			$url=I('post.url');
			$rules = array ( 
			    array('title','require','菜单名称必须！'), //默认情况下用正则进行验证
			);
			/*
			 * 计算level等级
			 * */
			$pid=intval(I('post.pid'));
			$level=0;
			$_POST['path']='-0-';
			if($pid>0){
				$parent_info=get_info($this->table,array('id'=>$pid),array('level,path'));
				if($parent_info){
					$level=$parent_info['level']+1;
					$_POST['path']=$parent_info['path'].$pid.'-';
				}
			}
			$_POST['level']=$level;
			$_POST['type']=$this->type; 
			
			$result=update_data($this->table, $rules);
			F($cache_name,null);

			if(is_numeric($result)){
				/*修改数据后清空缓存数据*/
				F($this->cache_data,NULL);
				F($this->cache_name,NULL);
				$this->success('操作成功',U('index',array('pid'=>intval(I('post.pid')))));
				
			}else{
				$this->error($result);
			}
		}else{
			$this->success('违法操作',U('index'));
		}
	}
	
	/*
	 * 删除数据库中的数据，如果是删除数据到回收站，不需要此函数
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	function del(){
		$ids=I('ids');
// 		dump($ids);die;
		if(is_array($ids)){
			$map['id']=array('in',$ids);
			$result=delete_data($this->table,$map);
			$ids_str=implode(',', $ids);
			if($result){
				action_log($this->table,$ids);
// 				if($ids_str!=''){
// 					execute_sql('house', 'update sr_house set '.$this->property_type.'=0 where id in ('.$ids_str.')');
// 				}
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}else{
			$ids=intval($ids);
			$map['id']=$ids;
			$result=delete_data($this->table,$map);
			if($result){
// 				execute_sql('house', 'update sr_house set '.$this->property_type.'=0 where id='.$ids);
				action_log($this->table,$ids);
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}
	}
	
public function recommend(){
		$id=I('ids');
		$status=intval(I('status'));
		
			 $_POST=null;
			 $_POST=array(
			 	'id'=>$id,
				'recommend'=>$status
			 );
		 
		$result=update_data('area');
		 	$User = M("Area"); 
			$where['pid']=$id;
			$User-> where($where)->setField('recommend',$status);

		if($result){
				$this->success('操作成功！');
			}else{
				 $this->error('操作失败！');
				}
	
	}
	
}