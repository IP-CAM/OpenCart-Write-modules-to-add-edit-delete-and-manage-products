<?php
class ControllerSanPhamSanPham extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sanpham/sanpham');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sanpham/sanpham');

		$this->getList();
	}

	public function add() {
		$this->load->language('sanpham/sanpham');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sanpham/sanpham');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sanpham_sanpham->addSanPham($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sanpham/sanpham', '' . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sanpham/sanpham');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sanpham/sanpham');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sanpham_sanpham->editSanPham($this->request->get['id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_edit_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sanpham/sanpham', '' . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sanpham/sanpham');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sanpham/sanpham');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $id) {
				$this->model_sanpham_sanpham->deleteSanPham($id);
			}

			$this->session->data['success'] = $this->language->get('text_del_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sanpham/sanpham', '' . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ten_sp';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sanpham/sanpham', '' . $url, true)
		);

		$data['add'] = $this->url->link('sanpham/sanpham/add', '' . $url, true);
		$data['delete'] = $this->url->link('sanpham/sanpham/delete', '' . $url, true);

		$data['users'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$user_total = $this->model_sanpham_sanpham->getTotalSanPham();

		$results = $this->model_sanpham_sanpham->getSanPham($filter_data);

		foreach ($results as $result) {
			$data['array_sanpham'][] = array(
				'id'		=> $result['id'],
				'ten_sp'    => $result['ten_sp'],
				'mota_sp'   => $result['mota_sp'],
				'price'		=> number_format($result['price']).' ₫',
				'time' => date('H:i - d/m/Y', $result['time']),
				'edit'       => $this->url->link('sanpham/sanpham/edit', '' . '&id=' . $result['id'] . $url, true)
			);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_ten_sp'] = $this->url->link('sanpham/sanpham', '' . '&sort=ten_sp' . $url, true);
		$data['sort_mota_sp'] = $this->url->link('sanpham/sanpham', '' . '&sort=mota_sp' . $url, true);
		$data['sort_price'] = $this->url->link('sanpham/sanpham', '' . '&sort=price' . $url, true);
		$data['sort_time'] = $this->url->link('sanpham/sanpham', '' . '&sort=time' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sanpham/sanpham', '' . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($user_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($user_total - $this->config->get('config_limit_admin'))) ? $user_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $user_total, ceil($user_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sanpham/sanpham_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['tensanpham'])) {
			$data['error_tensanpham'] = $this->error['tensanpham'];
		} else {
			$data['error_tensanpham'] = '';
		}

		if (isset($this->error['mota'])) {
			$data['error_mota'] = $this->error['mota'];
		} else {
			$data['error_mota'] = '';
		}

		if (isset($this->error['price'])) {
			$data['error_price'] = $this->error['price'];
		} else {
			$data['error_price'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sanpham/sanpham', '' . $url, true)
		);

		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('sanpham/sanpham/add', '' . $url, true);
		} else {
			$data['action'] = $this->url->link('sanpham/sanpham/edit', '' . '&id=' . $this->request->get['id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('sanpham/sanpham', '' . $url, true);

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$user_info = $this->model_sanpham_sanpham->getInfoSanPham($this->request->get['id']);
		}

		if (isset($this->request->post['tensanpham'])) {
			$data['tensanpham'] = $this->request->post['tensanpham'];
		} else if(!empty($user_info['ten_sp'])) {
			$data['tensanpham'] = $user_info['ten_sp'];
		} else {
			@$data['tensanpham'] = $this->request->post['tensanpham'];
		}

		if (isset($this->request->post['mota'])) {
			$data['mota'] = $this->request->post['mota'];
		} else if(!empty($user_info['mota_sp'])) {
			$data['mota'] = $user_info['mota_sp'];
		} else {
			@$data['mota'] = $this->request->post['mota'];
		}

		if (isset($this->request->post['price'])) {
			$data['price'] = intval($this->request->post['price']);
		} else if(isset($user_info['price'])) {
			$data['price'] = $user_info['price'];
		} else {
			@$data['price'] = $this->request->post['price'];
		}



		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sanpham/sanpham_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('sanpham/sanpham')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		//Kiểm tra nếu tên sản phẩm trống hoặc dưới 6 kí tự
		if ((utf8_strlen($this->request->post['tensanpham']) < 6) || empty($this->request->post['tensanpham'])) {
			$this->error['tensanpham'] = $this->language->get('error_tensanpham');
		}

		//Kiểm tra nếu tên sản phẩm trống hoặc dưới 6 kí tự
		if ((utf8_strlen($this->request->post['mota']) < 15) || empty($this->request->post['mota'])) {
			$this->error['mota'] = $this->language->get('error_mota');
		}

		//Kiểm tra nếu giá tiền trống hoặc bé hơn 0
		if (intval($this->request->post['price']) <= 0) {
			$this->error['price'] = $this->language->get('error_price');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('sanpham/sanpham')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}