<?php
class ControllerImportGoodslist extends Controller {
	public function index() {
		echo '1321333ppppp';
		$this->load->language('common/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_map'] = $this->language->get('text_map');
		$data['text_activity'] = $this->language->get('text_activity');
		$data['text_recent'] = $this->language->get('text_recent');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}
		

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$this->load->model('import/goodslist');
		$results = $this->model_import_goodslist->getgoods(15);
		$data['order'] = "gooslist";
		$data['goodslist'] = $results;
		$data['customer'] = "gooslist";
		$data['online'] = "gooslist";
		$data['map'] = "gooslist";
		$data['chart'] = "gooslist";
		$data['activity'] = "gooslist";
		$data['recent'] = "gooslist";
		$data['footer'] = $this->load->controller('common/footer');

		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}

		$this->response->setOutput($this->load->view('import/goodslist.php', $data));
		//$this->response->setOutput($this->load->view('common/dashboard.tpl', $data));
	}
	
	
}
