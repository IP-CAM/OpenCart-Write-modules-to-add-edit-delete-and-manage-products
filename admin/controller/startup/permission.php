<?php
class ControllerStartupPermission extends Controller {
	public function index() {
		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];

			// We want to ingore some pages from having its permission checked.
			$ignore = array(
				'common/dashboard',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'
			);

			if (!in_array($route, $ignore) && !$this->user->hasPermission($route)) {
				return new Action('error/permission');
			}
		}
	}
}
