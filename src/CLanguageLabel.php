<?php

class CLanguageLabel {
	var $def_lang = 'en';
	
	public function __construct($_pdo){
		$this->pdo = $_pdo;
		
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
			$lang_list = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
			$sql = "SELECT id FROM lang";
			$stmt = $this->pdo->Query($sql);
			$availLangs = $stmt->fetchAll();
			foreach($lang_list as $lang){
				if(in_array($lang_abbr,$availLangs)){
					$this->def_lang = $lang_abbr;
				}
			}
		}
	}
	
	public function add_label($label){
		$check = $this->pdo->prepare("SELECT * FROM lang_label WHERE label = ?");
		$check->execute(array($label));
		if($check->rowCount() < 1){
			$ins = $pdo->prepare("INSERT INTO lang_label (label) VALUES(?)");
			return $ins->execute(array($label));
		}
		return false;
	}
	
	public function delete_label($label){
		$select = $this->pdo->prepare("SELECT id FROM lang_label WHERE label = ?");
		$select->execute(array($label));
		$res1 = $select->fetchAll(PDO::FETCH_ASSOC);
		
		$del1 = $this->pdo->prepare("DELETE FROM lang_label WHERE id = ?");
		$del2 = $this->pdo->prepare("DELETE FROM lang_label_data WHERE labelid = ?");
		$del1->execute(array($res1[0]['id']));
		$del2->execute(array($res1[0]['id']));
	}
	
	public function get_label_value($label,$lang = ''){
		$langid = ($lang == '') ? $this->def_lang : $lang;
		$lth = $this->pdo->prepare("SELECT id FROM lang_label WHERE label = ?");
		$vth = $this->pdo->prepare("SELECT value FROM lang_label_data WHERE labelid = ? AND langid = ?");
		$lth->execute(array($label));
		$res1 = $lth->fetchAll(PDO::FETCH_ASSOC);
		$vth->execute(array($res1[0]['id'],$langid));
		return $vth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function get_label_tip($label,$lang = ''){
		$langid = ($lang == '') ? $this->def_lang : $lang;
		$lth = $this->pdo->prepare("SELECT id FROM lang_label WHERE label= ?");
		$vth = $this->pdo->prepare("SELECT tip_value FROM lang_label_data WHERE labelid= ? AND langid= ?");
		$lth->execute(array($label));
		$res1 = $lth->fetchAll(PDO::FETCH_ASSOC);
		$vth->execute(array($res1[0]['id'],$langid));
		return $vth->fetchAll(PDO::FETCH_ASSOC);
	}

	public function add_language($id,$name){
		$check = $this->pdo->prepare("SELECT * FROM lang WHERE id = ?");
		$check->execute(array($id));
		if($check->rowCount() < 1){
			$ins = $this->pdo->prepare("INSERT INTO lang (id,name) VALUES(?,?)");
			return $ins->execute(array($id,$name));
		}
		return false;
	}
	
	public function get_languages(){
		$sql = "SELECT * FROM lang";
		$stmt = $this->pdo->Query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}