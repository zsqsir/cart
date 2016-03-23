<?php
class ModelImportGoodslist extends Model {

	public function getgoods($limit=9) {
		$query = "SELECT des from goods order by id limit $limit";
		$query = $this->db2->query($query);

		$data=array();
		foreach ($query->rows as $item) {
			$data[] = iconv('GBK','UTF-8',$item['des']);
		}

		return $data;
	}

	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");

		return $query->row['total'];
	}
	
	public function getTotalCategoriesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}	
}
