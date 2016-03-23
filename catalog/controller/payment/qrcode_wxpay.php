<?php
class ControllerPaymentQrcodeWxPay extends Controller {
	public function index() {
		
		//echo "one";
		$this->load->library('wxpayexception');
		
		
		
		define('APPID', $this->config->get('wxpay_appid'));
		define('MCHID', $this->config->get('wxpay_mchid'));
		define('KEY', $this->config->get('wxpay_key'));
		define('APPSECRET', $this->config->get('wxpay_appsecret'));
		
		
				//echo "three";
		$this->load->library('wxpaydata');
		//echo "four";
		$this->load->library('wxpayapi');
		//echo "five";
		
		$this->load->library('wxpaynativepay');
		//echo "six";

		$this->language->load('payment/qrcode_wxpay');

		$data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->load->model('checkout/order');

		$order_id = $this->session->data['order_id'];

		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		$item_name = $this->config->get('config_name');
		
		$fullname = $order_info['payment_fullname'];
		
		$this->load->model('account/order');

		$shipping_cost = 0;

		$totals = $this->model_account_order->getOrderTotals($order_id);

		foreach ($totals as $total) {
			
			if($total['title'] == 'shipping') {
				
				$shipping_cost = $total['value'];
				
			}
			
		}
		
		$notify_url = HTTPS_SERVER.'catalog/controller/payment/qrcode_wxpay_callback.php';

        


        $out_trade_no = $this->session->data['order_id'];

        $subject = $item_name . ' ' . $this->language->get('text_order') .' '. $order_id;

        $amount = $order_info['total'];
		
		$currency_value = $this->currency->getValue('CNY');
		$price = $amount * $currency_value;
		$price = number_format($price,2,'.','');
		
		$total_fee = $price * 100;//乘100去掉小数点，以传递整数给微信支付
		
		
		//$total_fee = 1;
		//echo "Total Fee: ".$total_fee."<br>";
		
		//echo "3";		
				
		//$openId = $this->session->data['weixin_openid'];
		$notify = new NativePay();
		$input = new WxPayUnifiedOrder();
		$input->SetBody($subject);
		$input->SetAttach("mycncart");
		$input->SetOut_trade_no($order_id);
		$input->SetTotal_fee($total_fee);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("mycncart");
		$input->SetNotify_url(HTTP_SERVER . "catalog/controller/payment/qrcode_wxpay_callback.php");
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($order_id);
		$result = $notify->GetPayUrl($input);
		//$url2 = $result["code_url"];
		$this->session->data['code_url'] = $result['code_url'];
		
		$data['redirect'] = $this->url->link('checkout/qrcode_wxpay_success');
		
		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/qrcode_wxpay.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/qrcode_wxpay.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/qrcode_wxpay.tpl', $data);
		}
		
	}
	
	
	public function callback() {
		
		$log = $this->config->get('qrcode_wxpay_log');
		
		$this->load->library('wxpayexception');

		define('APPID', $this->config->get('wxpay_appid'));
		define('MCHID', $this->config->get('wxpay_mchid'));
		define('KEY', $this->config->get('wxpay_key'));
		define('APPSECRET', $this->config->get('wxpay_appsecret'));
		
		
				//echo "three";
		$this->load->library('wxpaydata');
		//echo "four";
		$this->load->library('wxpayapi');
		
		$this->load->library('wxpaynotify');
		
		$this->load->library('qrcode_wxpay_notify');
		
		if($log) {
			$this->log->write('QrcodeWxPay :: One ');
		}
		
		$notify = new PayNotifyCallBack();
		
		$notify->Handle(false);
		
		if($log) {
			$this->log->write('QrcodeWxPay :: Two ');
		}
		
		$getxml = $GLOBALS['HTTP_RAW_POST_DATA'];
		
		
		
		libxml_disable_entity_loader(true);
		
		$result= json_decode(json_encode(simplexml_load_string($getxml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		
		
		
		
		if($notify->GetReturn_code() == "SUCCESS") {
			
			
			if ($result["return_code"] == "FAIL") {
				
				$this->log->write("QrcodeWxPay ::【通信出错】:\n".$getxml."\n");
				
			}elseif($result["result_code"] == "FAIL"){
				
				$this->log->write("QrcodeWxPay ::【业务出错】:\n".$getxml."\n");
				
			}else{
				
				
				
			
				$order_id = $result['out_trade_no'];
				
				if($log) {
					$this->log->write('QrcodeWxPay :: Order ID: '.$order_id);
				}
				
				$this->load->model('checkout/order');
	
				$order_info = $this->model_checkout_order->getOrder($order_id);
				
				if ($order_info) {
					
					if($log) {
						$this->log->write('QrcodeWxPay :: 1: ');
					}
				
					$order_status_id = $this->config->get('qrcode_wxpay_trade_success_status_id');
						
					if (!$order_info['order_status_id']) {
						
						$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', true);
						
						$this->log->write('QrcodeWxPay :: 2: ');
						
					} else {
						
						$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', true);
						
						$this->log->write('QrcodeWxPay :: 3: ');
						
					}
					
					//清除sesssion，避免客户返回不到成功页面而无法清除原有的购物车等信息
					$this->cart->clear();
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
					unset($this->session->data['guest']);
					unset($this->session->data['comment']);
					unset($this->session->data['order_id']);
					unset($this->session->data['coupon']);
					unset($this->session->data['reward']);
					unset($this->session->data['voucher']);
					unset($this->session->data['vouchers']);
					unset($this->session->data['totals']);
					if(isset($this->session->data['cs_shipfrom'])) {
						unset($this->session->data['cs_shipfrom']);
					}
					
					if(isset($this->sesssion->data['personal_card'])) {
						unset($this->sesssion->data['personal_card']);
					}
					
					if(isset($this->sesssion->data['code_url'])) {
						unset($this->sesssion->data['code_url']);
					}
				
					
				}else{
					
					if($log) {
						$this->log->write('QrcodeWxPay :: Three: ');
					}
					
				}
			
			}
			
			
		}else{
			
			$this->log->write('QrcodeWxPay :: Four: '.$result);
			
		}
		
		
		
	
		
	}
	

	
}