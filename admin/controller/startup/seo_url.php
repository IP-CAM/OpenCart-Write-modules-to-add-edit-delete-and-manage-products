<?php
class ControllerStartupSeoUrl extends Controller {
	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$route = $this->request->get['_route_'];

            $query = $this->db->query("SELECT route FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($route) . "'")->row;

            if ($query) {
                $this->request->get['route'] = $query['route'];
            } else {
                $this->request->get['route'] = $route;
            }

            unset($this->request->get['_route_']);

            $request = $this->request->get;

			foreach ($request as $key => $value) {
                $this->request->get[$key] = $value;
			}
		}
	}

	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

        if (isset($data['route'])) {
            $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE route = '" . $this->db->escape($data['route']) . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'")->row;

            if ($query) {
                $url .= '/' . $query['keyword'];
            } else {
                $url .= '/' . $data['route'];
            }

            unset($data['route']);

            $query = '';

            if ($data) {
                foreach ($data as $key => $value) {
                    $query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
                }
    
                if ($query) {
                    $query = '?' . str_replace('&', '&amp;', trim($query, '&'));
                }
            }
    
            return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
        }

        return $link;
	}
}
