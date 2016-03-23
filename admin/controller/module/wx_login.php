<?php
class ControllerModuleWXLogin extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('module/wx_login');

		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('wx_login', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_weixin_open_signup'] = $this->language->get('text_weixin_open_signup');

		$data['entry_appid'] = $this->language->get('entry_appid');
		$data['entry_appsecret'] = $this->language->get('entry_appsecret');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['appid'])) {
			$data['error_appid'] = $this->error['appid'];
		} else {
			$data['error_appid'] = '';
		}

		if (isset($this->error['appsecret'])) {
			$data['error_appsecret'] = $this->error['appsecret'];
		} else {
			$data['error_appsecret'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/wx_login', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/wx_login', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['wx_login_appid'])) {
			$data['wx_login_appid'] = $this->request->post['wx_login_appid'];
		} else {
			$data['wx_login_appid'] = $this->config->get('wx_login_appid');
		}

		if (isset($this->request->post['wx_login_appsecret'])) {
			$data['wx_login_appsecret'] = $this->request->post['wx_login_appsecret'];
		} else {
			$data['wx_login_appsecret'] = $this->config->get('wx_login_appsecret');
		}

		if (isset($this->request->post['wx_login_status'])) {
			$data['wx_login_status'] = $this->request->post['wx_login_status'];
		} else {
			$data['wx_login_status'] = $this->config->get('wx_login_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/wx_login.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/wx_login')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['wx_login_appid']) {
			$this->error['appid'] = $this->language->get('error_appid');
		}

		if (!$this->request->post['wx_login_appsecret']) {
			$this->error['appsecret'] = $this->language->get('error_appsecret');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/event');

		$this->model_extension_event->addEvent('wx_login', 'post.customer.logout', 'module/wx_login/logout');
	}

	public function uninstall() {
		$this->load->model('extension/event');

		$this->model_extension_event->deleteEvent('wx_login');
	}
}