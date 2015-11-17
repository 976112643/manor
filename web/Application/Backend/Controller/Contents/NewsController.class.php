<?php
namespace Backend\Controller\Contents;
use Backend\Controller\Base\AdminController;
class NewsController extends AdminController {
	protected $table='article';
	protected $action_filed='id,title';
	/*
	*用户列表
	*	需求分析
	*		需要将所有用户的信息全部显示到这张表中
	*	流程分析
	*		1、接收用户筛选信息,具体的信息看
	*		2、组织查询的条件
	*		3、按照条件将信息显示出来
	*		4、将信息中的int字段转化成文字信息
	*/
	public function index(){
		//接收用户筛选信息,具体的信息看，组织查询的条件
		$map['status']=array('GT',-1);
		$map['type'] = 'news';
		$keywords=I('keywords');
		if($keywords){
			$map['article.title|article.description|category.title']=array('like', "%$keywords%");//这里的username是真实姓名还是平台生成的呢？？这里还可以添加其他的关键词
			$data['keywords'] = $keywords;//将本次的关键词显示到页面中去
		}
		
		//按照条件将信息显示出来
		$result=$this->page(D('ArticleView'), $map,'add_time desc');
		//将信息中的int字段转化成文字信息
		$result=int_to_string($result);
		
		$data['result']=$result;
		$this->assign($data);
		$this->display();
	}
	/*
	 *用户列表页添加功能
	 *	需求分析
	 *		尽量将用户的信息显示出来，让后台能够控制的东西更多
	 *	流程分析	
	 *		1、将用户的等级信息显示出来
	 *		2、生成用户登录的数字登录码
	 *		3、添加用户
	 * */
	public function add(){
		if(IS_POST){
			$_POST['type'] = 'news';
			$_POST['member_id'] = session('member_id');
			$_POST['update_member_id'] = session('member_id');
			$_POST['update_time'] = date('Y-m-d H:i:s',time());
			$this->update();
		}else{
			//将帮助分类的类别显示出来
			$map['status'] = array("GT",0);//将正常的显示出来
			$map['type'] = 4;
			
			$help_category = get_result('category',$map);
			
			//将帮助信息数据组织成树形
			$help_category = list_to_tree($help_category);
			$data['help_category'] = $help_category;
// 			dump($data);die;
			$this->assign($data);
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
			
			//将帮助分类的类别显示出来
			$map['status'] = array("GT",0);//将正常的显示出来
			$map['type'] = 4;
			
			$help_category = get_result('category',$map);
			
			//将帮助信息数据组织成树形
			$help_category = list_to_tree($help_category);
			$data['help_category'] = $help_category;
			
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
			$posts = I('post.');
// 			dump($posts);die;
			if($posts){
				$_POST["view1"]=$posts['view'];
				if($posts['view'] == null){
					$posts['view'] = 0;
				}
				$rules = array (
						array('title','require','标题必须填写！',1),
						array('view1','require','浏览量必须填写！',1),
						array('description','require','描述必须填写！',1),
						array('content','require','内容必须填写！',1),
				);
				$content=htmlspecialchars_decode($_POST['content']);
				$pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.bmp]))[\'|\"].*?[\/]?>/";
				if(preg_match_all($pattern,$content,$match)){
					$_POST['image']=substr(str_replace(__ROOT__,'',$match[1][0]),1);
				}else{
					$_POST['image']='';
				}
				$_POST = array(
						'id'=>$posts['id'],
						'title'=>$posts['title'],
						'view'=>$posts['view'],
						'description'=>$posts['description'],
						'content'=>$posts['content'],
						'type'=>$posts['type'],
						'member_id'=>$posts['member_id'],
						'update_member_id'=>$posts['update_member_id'],
						'update_time'=>$posts['update_time'],
						'cover'=>$_POST['image'],
					);
			}
			$result=update_data($this->table, $rules);
			if(is_numeric($result)){
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}else{
			$this->success('违法操作',U('index'));
		}
	}
}
