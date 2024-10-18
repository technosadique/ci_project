<?php namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class StudentsModel extends Model {
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fname', 'lname', 'class', 'section'];
	
	
	public function check_login($post){		
		
		$builder = $this->db->table('admin');
        $builder->select("*");
        $builder->where(['username'=>$post['username'],'password'=>$post['password']]);
        $result =  $builder->get()->getLastRow('array');		
		//echo $this->db->getLastQuery();die;
        return $result;	
	}
}



?>