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

            $query = $this->db->query("SELECT route, parameter FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($route) . "'")->row;

            if ($query) {
                $this->request->get['route'] = $query['route'];

                if ($query['parameter']) {
                    $parameters = explode('&', $query['parameter']);

                    foreach ($parameters as $parameter) {
                        $tmp = explode('=', $parameter);

                        $tmp = array_map('trim', $tmp);

                        $this->request->get[$tmp[0]] = $tmp[1];
                    }
                }
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
            $route = $data['route'];

            unset($data['route']);
        
            $parameter = array();

            foreach ($data as $key => $value) {
                $parameter[] = $key . '=' . $value;
            }

            $parameter = implode('&', $parameter);

            $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND route = '" . $this->db->escape($route) . "' AND parameter = '" . $this->db->escape($parameter) . "'")->row;

            if ($query) {
                $url .= '/' . $query['keyword'];

				$parameter = '';
            } else {
                $query = $this->db->query("SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' AND route = '" . $this->db->escape($route) . "'")->row;

                if ($query) {
                    $url .= '/' . $query['keyword'];
                } else {
                    $url .= '/' . $route;
                }

                $parameter = '';

                if ($data) {
                    foreach ($data as $key => $value) {
                        $parameter .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
                    }
        
                    if ($parameter) {
                        $parameter = '?' . str_replace('&', '&amp;', trim($parameter, '&'));
                    }
                }
            }
    
            return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $parameter;
        }

        return $link;
	}
}
