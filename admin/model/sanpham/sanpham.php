<?php
class ModelSanPhamSanPham extends Model {
	//Thành phần của Sản Phẩm
	public function getTotalSanPham() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "sanpham`");

		return $query->row['total'];
	}

	//Lấy tất cả sản phẩm ra ngoài
	public function getSanPham($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "sanpham`";

		$sort_data = array(
			'ten_sp',
			'mota_sp',
			'price',
			'time'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY ten_sp";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	//Thêm sản phẩm vào Data
	public function addSanPham($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "sanpham` SET ten_sp = '" . $this->db->escape($data['tensanpham']) . "',price = '" . intval($data['price']) . "', mota_sp = '" . $this->db->escape($data['mota']) . "',time = ".(time()+7*3600));
	
		return $this->db->getLastId();
	}

	//Xóa sản phẩm
	public function deleteSanPham($id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "sanpham` WHERE id = '" . (int)$id . "'");
	}

	//Sửa sản phẩm
	public function editSanPham($id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "sanpham` SET ten_sp = '" . $this->db->escape($data['tensanpham']) . "',price = '" . intval($data['price']) . "', mota_sp = '" . $this->db->escape($data['mota']) . "', time = '" . (time()+7*3600) . "' WHERE id = '" . (int)$id . "'");
	}

	//Get Sản Phẩm
	public function getInfoSanPham($id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sanpham` WHERE id = " . intval($id));

		return $query->row;
	}
}