<?php
namespace Backend\Controller\Contents;
use Backend\Controller\Base\AdminController;
class FilesController extends AdminController {
	protected $table='files';
	protected $model='FilesView';
	protected $limit=15;
	
	/**
	 * 文件管理列表页
	 * 展示文件表中所有未删除的文件
	 * 包括文件名，上传用户，所属店铺等信息
	 * 
	 * @author						李东
	 * @date						2015-06-24
	 */
	public function index(){
		if(I('title')){
			$map['title']=array('like','%'.I('title').'%');
		}
		if(I('begin_time') || I('end_time')){
			$begin_time = I('begin_time')?I('begin_time'):0;
			$end_time = I('end_time')?I('end_time'):date('Y-m-d H:i:s');
			$map['add_time']=array('BETWEEN',array($begin_time,$end_time));
		}
		$map['status'] = array('gt','-1');
		$order = ' add_time desc,id desc';
		$result = $this->page(D($this->model),$map,$order,$field=array(),$this->limit);
// 		echo session('sql');exit;
		$result=int_to_string($result,array("files_status"=>array("-1"=>"已删除","0"=>"启用","1"=>"禁用"),"recommend"=>array("0"=>"推荐","1"=>"取消推荐")));
		$data['result']=$result;
		$this->assign($data);
		$this->display();
	} 
	
	
	/**
	 * 文件删除
	 * 先判断批量删除  还是单个删除
	 * 
	 * 批量
	 * 先查询出所有要删除的文件数据，
	 * 循环取出文件存放地址进行删除，
	 * 将取出的数据状态更新为-1
	 * 
	 * 单个
	 * 查询出要删除的单条文件信息，
	 * 对该文件进行删除，
	 * 将取出的数据状态更新为-1
	 * 
	 * 
	 * @author						李东
	 * @date						2015-06-24
	 */
	public function del(){
		$ids=I('ids');
		if(!$ids){
			$this->error('请选择要修改的数据111');
		}
		
		/* 判断是批量删除还是单个删除 */
		if(is_array($ids)){ 
			/* 批量删除 */
			$map['id']=array('in',$ids);
			$result = get_result($this->table,$map);
			
			foreach($result as $val){
				/*循环对文件进行删除*/
				unlink($val['save_path']);
			}
			
		}else{
			/* 单个删除 */
			$map['id']=$ids;
			$info = get_info($this->table,$map);
			unlink($info['save_path']);
		}
		
		/* 将取出的数据状态更新为-1 */
		$this->setStatus();
		
	}
	
	
}
