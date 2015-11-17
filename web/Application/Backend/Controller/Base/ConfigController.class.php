<?php
namespace Backend\Controller\Base;
use Backend\Controller\Base\AdminController;
class ConfigController extends AdminController {
	protected $table='config';
	/*
	 * 菜单列表页
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function index(){
		$map['status']=array('gt',-1);
		$keywords=I('keywords');
		if($keywords){
			$map['title']=array('like', "%$keywords%");
		}
		$result=$this->page($this->table, $map,'id asc');
		$result=int_to_string($result);
		$data['result']=$result;
// 		dump($data);die;
		$this->assign($data);
		$this->display();
	}
	/*
	 * 添加操作
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
	 * 修改操作
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
	 * @author	康利民  <3027788306@qq.com>
	 * */
	public function update(){
		if(IS_POST){
			$id=intval(I('post.id'));
			$rules = array ( 
				array('title','require','请填写配置标题'),
				array('name','require','请填写配置标识'),
				array('name','/^[a-zA-Z_]{4,15}+$/','配置标识只允许使用字母和下划线'),
				array('name','','配置标识已存在，请更换其它标识',1,'unique'),
				array('group','require','请填写分组'),
				array('group','/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u','分组只允许使用中文、字母和下划线'),
				array('type','require','请选择配置类型')
			);
			$result=update_data($this->table, $rules);
			if(is_numeric($result)){
				F('config',null);
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}else{
			$this->success('违法操作',U('index'));
		}
	}
	

	
	
}