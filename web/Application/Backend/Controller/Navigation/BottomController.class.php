<?php
namespace Backend\Controller\Navigation;
use Backend\Controller\Base\AdminController;
class BottomController extends AdminController {
	protected $table='navigation';
	protected $action_filed='id,title';
	protected $cache_data='navigation_result';
	/*
	 * 首页导航
	 * @time 2015-05-05
	 * @author	康利民  <3027788306@qq.com>
	 * */
	public function index(){
		$map['pid']=0;
		if(I('pid')){
			$map['pid']=intval(I('pid'));
		}
		$keywords=I('keywords');
		if($keywords){
			$map['title']=array('like', "%$keywords%");
		}
		$map['type']='bottom';
		$map['status']=array('gt',-1);
		$result=$this->page($this->table, $map,'sort desc');
		$data['result']=$result;
		getNavigation();
		$this->assign($data);
		$this->display();
	}
	/*
	 * 添加导航
	 * @time 2015-05-05
	 * @author	康利民  <3027788306@qq.com>
	 * */
	public function add(){
		if(IS_POST){
			$this->update();
		}else{
			$pid=I('pid');
			$this->assign('pid',$pid);
			$this->display('operate');
		}
	}
	/*
	 * 修改导航
	 * @time 2015-05-05
	 * @author	康利民  <3027788306@qq.com>
	 * */
	public function edit(){
		if(IS_POST){
			$this->update();
		}else{
			$id=intval(I('id'));
			$map['id']=$id;
			$data['info']=get_info($this->table,$map);
			$data['pid']=$data['info']['pid'];
			$this->assign($data);
			$this->display('operate');
		}
	}	
	/*
	 * 添加/修改操作
	 * @time 2015-2-25
	 * @author	袁志刚  <562810463@qq.com>
	 * */
	public function update(){
		if(IS_POST){
			$id=intval(I('post.id'));
			$url=I('post.url');
			$rules = array ( 
			    array('title','require','导航名称必须！'), //默认情况下用正则进行验证
			);

			$_POST['type']='bottom';
			$pid=intval(I('post.pid'));
			$result=update_data($this->table, $rules);
			if(is_numeric($result)){                    //获取修改的特殊状态，更新
				F($this->cache_data,null);
				action_log($this->table,$result,$this->action_filed);
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
	 * @time 2015-2-25
	 * @author	袁志刚  <562810463@qq.com>
	 * */
	function del(){
		$ids=I('ids');
		$map['id']=array('in',$ids);
		$result=delete_data($this->table,$map);
		if($result){
			F($this->cache_data,null);
			action_log($this->table,$ids,$this->action_filed);
			$this->success('操作成功',U('index'));
		}else{
			$this->error($result);
		}
	}
}
