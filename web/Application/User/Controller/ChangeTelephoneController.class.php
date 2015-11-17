<?php
namespace User\Controller;
use User\Controller\BaseController;
class ChangeTelephoneController extends BaseController {
	private $table = 'member';
	/**
	*用户修改手机
	×	用户可以通过个人中心修改自己的手机号码
	*流程分析
	*	1、判断是手机还是pc端在访问
	*	2、接收数据，手机数据和pc数据分开操作
	*	3、处理数据
	*		验证手机号码的正确性
	*		验证验证码的正确性
	*		抛出异常信息
	*		修改数据
	**/
    public function index(){
		//判断是手机还是pc进行数据的接收
		if(C('IS_MOBILE')==1){//表示是手机端在访问
			$gets=inputJson();//获取处理后的提交数据 返回一个数组
			$telephone = $gets['telephone'];//修改的手机号码
			$code = $gets['code'];
			$member_id = $gets['member_id'];
		}else if(C('IS_MOBILE')==0){//表示是pc端在访问
			$telephone = I('telephone');
			$code = I('code');
			$member_id = session('home_member_id');
		}
		if(IS_POST){
			//验证手机号的正确性
			$checkStatus = 1;
			if(!preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $telephone)){
				$checkStatus = 0;
				$massage = '手机号码不正确！';
			}
			//验证验证码的正确性
			if($code!=session('changeTelephoneCode') || $telephone!=session('telephone')){
				$checkStatus = 0;
				$massage = '验证码错误';
			}
			//修改用户的手机号码
			$_POST['id'] = $member_id;
			$_POST['telephone'] = $telephone;
			$result = update_data($this->table);
			if(is_numeric($result)){
				$massage = '修改成功！！';
			}else{
				$checkStatus = 0;
				$massage = $result;
			}
			if(C('IS_MOBILE')==1){
				$this->ajaxReturn(array('checkStatus'=>$checkStatus,'massage'=>$massage));
			}else{
				if($checkStatus==1){
					$this->success($massage,U('User/Index/index'));
				}else if($checkStatus==0){
					$this->error($massage,U('User/ChangeTelephone/index'));
				}
			}
		}else{
			//查询用户的手机
			$info = get_info($this->table,$member_id);
			$data['info'] = $info;
			if(C('IS_MOBILE')==1){
				$this->ajaxReturn(array('telephone'=>$info['telephone']));
			}else{
				$this->assign($data);
				$this->display();
			}
		}
		
		
    }
	public function registerCode(){
		$verify = new \Think\Verify();
		$res = $verify->check($code, 1);
		if(!$res){
			$this->error('验证码错误！！');
		}
		//判断是手机还是pc进行数据的接收
		if(C('IS_MOBILE')==1){//表示是手机端在访问
			$gets=inputJson();//获取处理后的提交数据 返回一个数组
			$telephone = trim($gets['telephone']);//修改的手机号码
		}else if(C('IS_MOBILE')==0){//表示是pc端在访问
			$telephone = trim(I('telephone'));
		}
    	$code=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		session('changeTelephoneCode',$code);
		session('telephone',$telephone);
		//验证是否是手机号码
		$checkStatus = 1;
		if(preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $telephone)){
			$content="您在N邦翻译上注册验证码为：".$code."，如非本人操作，无需理会【N邦翻译】";
			$status=sms($telephone, $content);
			if($status<0){
				$checkStatus = 0;
				$masage = '验证短信发送失败，请联系客服';
			}else{
				$massage = '发送成功！';
			}
		}else{
			$checkStatus = 0;
			$massage = '请输入正确的手机号码！！';
		}
		//判断是手机还是pc端在访问
		if(C('IS_MOBILE')==1){//手机访问
			$this->ajaxReturn(array('checkStatus'=>$checkStatus,'massage'=>$massage));
		}else if(C('IS_MOBILE')==0){
			if($checkStatus==1){
				$this->success($massage,U('Home/Index/index'));
			}else{
				$this->error($massage,U('Home/Index/index'));
			}
		}
		
	}
}