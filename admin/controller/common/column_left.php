<?php
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		$this->load->language('common/column_left');

		// Create a 3 level menu array
		// Level 2 can not have children
		
		// Menu
		$data['menus'][] = array(
			'id'       => 'menu-dashboard',
			'icon'	   => 'fa-dashboard',
			'name'	   => $this->language->get('text_dashboard'),
			'href'     => $this->url->link('common/dashboard', '', true),
			'children' => array()
		);

		//Product
		$data['menus'][] = array(
			'id'       => 'menu-product',
			'icon'	   => 'fa-shopping-cart',
			'name'	   => 'Quản Lí Sản Phẩm',
			'href'     => $this->url->link('sanpham/sanpham', '', true),
			'children' => array()
		);

		// Extension
		$marketplace = array();
		
		if ($this->user->hasPermission('marketplace/installer')) {		
			$marketplace[] = array(
				'name'	   => $this->language->get('text_installer'),
				'href'     => $this->url->link('marketplace/installer', '', true),
				'children' => array()		
			);					
		}	
		
		if ($this->user->hasPermission('marketplace/extension')) {		
			$marketplace[] = array(
				'name'	   => $this->language->get('text_extension'),
				'href'     => $this->url->link('marketplace/extension', '', true),
				'children' => array()
			);
		}
							
		if ($this->user->hasPermission('marketplace/modification')) {
			$marketplace[] = array(
				'name'	   => $this->language->get('text_modification'),
				'href'     => $this->url->link('marketplace/modification', '', true),
				'children' => array()		
			);
		}
		
		if ($this->user->hasPermission('marketplace/event')) {
			$marketplace[] = array(
				'name'	   => $this->language->get('text_event'),
				'href'     => $this->url->link('marketplace/event', '', true),
				'children' => array()		
			);
		}
				
		if ($marketplace) {					
			$data['menus'][] = array(
				'id'       => 'menu-extension',
				'icon'	   => 'fa-puzzle-piece', 
				'name'	   => $this->language->get('text_extension'),
				'href'     => '',
				'children' => $marketplace
			);		
		}
		
		// Design
		$design = array();
		if ($this->user->hasPermission('design/layout')) {	
			$design[] = array(
				'name'	   => $this->language->get('text_layout'),
				'href'     => $this->url->link('design/layout', '', true),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('design/theme')) {	
			$design[] = array(
				'name'	   => $this->language->get('text_theme'),
				'href'     => $this->url->link('design/theme', '', true),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('design/translation')) {
			$design[] = array(
				'name'	   => $this->language->get('text_language_editor'),
				'href'     => $this->url->link('design/translation', '', true),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('design/seo_url')) {
			$design[] = array(
				'name'	   => $this->language->get('text_seo_url'),
				'href'     => $this->url->link('design/seo_url', '', true),
				'children' => array()		
			);
		}
					
		if ($design) {
			$data['menus'][] = array(
				'id'       => 'menu-design',
				'icon'	   => 'fa-television', 
				'name'	   => $this->language->get('text_design'),
				'href'     => '',
				'children' => $design
			);	
		}
		
		// Customer
		$customer = array();
		
		if ($this->user->hasPermission('customer/customer')) {
			$customer[] = array(
				'name'	   => $this->language->get('text_customer'),
				'href'     => $this->url->link('customer/customer', '', true),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('customer/customer_group')) {
			$customer[] = array(
				'name'	   => $this->language->get('text_customer_group'),
				'href'     => $this->url->link('customer/customer_group', '', true),
				'children' => array()		
			);
		}
			
		if ($this->user->hasPermission('customer/customer_approval')) {
			$customer[] = array(
				'name'	   => $this->language->get('text_customer_approval'),
				'href'     => $this->url->link('customer/customer_approval', '', true),
				'children' => array()		
			);
		}
					
		if ($this->user->hasPermission('customer/custom_field')) {		
			$customer[] = array(
				'name'	   => $this->language->get('text_custom_field'),
				'href'     => $this->url->link('customer/custom_field', '', true),
				'children' => array()		
			);	
		}
		
		if ($customer) {
			$data['menus'][] = array(
				'id'       => 'menu-customer',
				'icon'	   => 'fa-user', 
				'name'	   => $this->language->get('text_customer'),
				'href'     => '',
				'children' => $customer
			);	
		}
		
		// System
		$system = array();
		
		if ($this->user->hasPermission('setting/setting')) {
			$system[] = array(
				'name'	   => $this->language->get('text_setting'),
				'href'     => $this->url->link('setting/store', '', true),
				'children' => array()		
			);	
		}
	
		// Users
		$user = array();
		
		if ($this->user->hasPermission('user/user')) {
			$user[] = array(
				'name'	   => $this->language->get('text_users'),
				'href'     => $this->url->link('user/user', '', true),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('user/user_permission')) {	
			$user[] = array(
				'name'	   => $this->language->get('text_user_group'),
				'href'     => $this->url->link('user/user_permission', '', true),
				'children' => array()		
			);	
		}
		
		if ($this->user->hasPermission('user/api')) {		
			$user[] = array(
				'name'	   => $this->language->get('text_api'),
				'href'     => $this->url->link('user/api', '', true),
				'children' => array()		
			);	
		}
		
		if ($user) {
			$system[] = array(
				'name'	   => $this->language->get('text_users'),
				'href'     => '',
				'children' => $user		
			);
		}
		
		// Localisation
		$localisation = array();
		
		if ($this->user->hasPermission('localisation/language')) {
			$localisation[] = array(
				'name'	   => $this->language->get('text_language'),
				'href'     => $this->url->link('localisation/language', '', true),
				'children' => array()		
			);
		}
		
		if ($localisation) {																
			$system[] = array(
				'name'	   => $this->language->get('text_localisation'),
				'href'     => '',
				'children' => $localisation	
			);
		}
		
		// Tools	
		$maintenance = array();
			
		if ($this->user->hasPermission('tool/backup')) {
			$maintenance[] = array(
				'name'	   => $this->language->get('text_backup'),
				'href'     => $this->url->link('tool/backup', '', true),
				'children' => array()		
			);
		}
				
		if ($this->user->hasPermission('tool/upload')) {
			$maintenance[] = array(
				'name'	   => $this->language->get('text_upload'),
				'href'     => $this->url->link('tool/upload', '', true),
				'children' => array()		
			);	
		}
					
		if ($this->user->hasPermission('tool/log')) {
			$maintenance[] = array(
				'name'	   => $this->language->get('text_log'),
				'href'     => $this->url->link('tool/log', '', true),
				'children' => array()		
			);
		}
	
		if ($maintenance) {
			$system[] = array(
				'id'       => 'menu-maintenance',
				'icon'	   => 'fa-cog', 
				'name'	   => $this->language->get('text_maintenance'),
				'href'     => '',
				'children' => $maintenance
			);
		}		
	
	
		if ($system) {
			$data['menus'][] = array(
				'id'       => 'menu-system',
				'icon'	   => 'fa-cog', 
				'name'	   => $this->language->get('text_system'),
				'href'     => '',
				'children' => $system
			);
		}
		
		$report = array();
						
		if ($this->user->hasPermission('report/online')) {
			$report[] = array(
				'name'	   => $this->language->get('text_online'),
				'href'     => $this->url->link('report/online', '', true),
				'children' => array()		
			);
		}
		
		$data['menus'][] = array(
			'id'       => 'menu-report',
			'icon'	   => 'fa-bar-chart-o', 
			'name'	   => $this->language->get('text_reports'),
			'href'     => '',
			'children' => $report
		);	
		
		return $this->load->view('common/column_left', $data);
	}
}