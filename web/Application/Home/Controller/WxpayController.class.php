<?php
namespace Home\Controller;
use Home\Controller\HomeController;
use User\Controller\UpdateImgController;
use Backend\Controller\Base\PublicController;
class WxpayController extends HomeController {
	protected $table = 'orders';						/*主要操作表*/
	protected $history_table = 'order_history';			/*订单状态历史记录表*/
	protected $product_table = 'products';				/*商品表*/
	protected $model = 'OrderView';						/*数据视图模型*/
	protected $member_table = 'member';					/*用户信息表*/
	protected $money_table = 'money_record';			/*资金流动记录表*/
	protected $product_type;							/*商品分类*/

	
	public  function __autoload(){
		parent::__autoload();
		$this->product_type = list_to_tree(get_ability_cache());		/*获取缓存的分类属性转换为树状*/
	}
	public function _initialize(){
		//引入WxPayPubHelper
		vendor('WxPay.lib.WxPayApi');
		vendor('WxPay.WxPayNativePay');
		vendor('WxPay.log');
	}
	
	/**
	 * 订单微信支付
	 * @author						李东
	 * @date						2015-07-21
	 */
	public function index(){
		
		
		
		
		/*获取微信支付配置信息*/
		$wxpay_config = C('payment.wxpay');
		$notify = new \NativePay();
		//$url1 = $notify->GetPrePayUrl("123456789");
		
		
		
		$input = new \WxPayUnifiedOrder();
		$input->SetBody("test");																					/*设置商品或支付单简要描述*/
		$input->SetAttach("test");																					/*设置附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据*/
		$input->SetOut_trade_no($wxpay_config['mchid'].date("YmdHis"));												/*设置商户系统内部的订单号,32个字符内、可包含字母*/
		$input->SetTotal_fee("1");																					/*设置订单总金额，只能为整数;如0.01元，设置为1*/
		$input->SetTime_start(date("YmdHis"));																		/*设置订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。*/
		$input->SetTime_expire(date("YmdHis", time() + 600));														/*设置订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。*/
		$input->SetGoods_tag("test");																				/*设置商品标记，代金券或立减优惠功能的参数*/
		$input->SetNotify_url('http://'.I('server.HTTP_HOST').U('notify'));											/*设置接收微信支付异步通知回调地址*/
		$input->SetTrade_type("NATIVE");																			/*设置支付方式，取值如下：JSAPI，NATIVE，APP;*/	
		$input->SetProduct_id("123456789");																			/*设置trade_type=NATIVE，此参数必传。此id为二维码中包含的商品ID，商户自行定义。*/
		$result = $notify->GetPayUrl($input);																		/*获取返回参数*/
		$url = 'http://'.I('server.HTTP_HOST').U('create_code').'?data='.urlencode($result["code_url"]);			/*返回的支付二维码图片地址*/
		$result['show_code'] =$url; 
		$data['result']=$result;
		$this->assign($data);
		$this->display();

		
	}
	
	public function notify(){
		$data['post']=$_POST;
		$data['get']=$_GET;
		$data['env']=$_ENV;
		$data['requst']=$_REQUEST;
		
		open_file($data, 'wxpay');
	}
	
	
	/**
	 * 生成微信支付二维码
	 * @author						李东
	 * @date						2015-07-21
	 */
	public function create_code(){
		vendor("Wxpay.phpqrcode.phpqrcode");
		$url = urldecode($_GET["data"]);

		\QRcode::png($url,false,QR_ECLEVEL_L,8);
	}
	
}