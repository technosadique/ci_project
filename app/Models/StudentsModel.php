<?php namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class StudentsModel extends Model {
    protected $db;   
	
	public function get_students(){		
		
		$builder = $this->db->table('students');
        $builder->select("*");
        $result =  $builder->get()->getResultArray();		
        return $result;	
	}
	
	public function check_login($post){		
		
		$builder = $this->db->table('admin');
        $builder->select("*");
        $builder->where(['username'=>$post['username'],'password'=>$post['password']]);
        $result =  $builder->get()->getLastRow('array');		
		//echo $this->db->getLastQuery();die;
        return $result;	
	}
	

	public function edit_data($id){		
		
		$builder = $this->db->table('students');
        $builder->select("*");
        $builder->where(['id'=>$id]);
        $result = $builder->get()->getLastRow("array");	
        return $result;	
	}

	public function delete_customer($post)
    {        
        if (!empty($post["id"])) {
            // delete in mysql db
            $builder = $this->db->table('customers');
            $builder->where('id', $post["id"]);
            $builder->update(['in_deleted' => 1]);           
            echo "success";
        }
    }
   
    public function bulkdeletecustomer($post)
    {
        $idArr = explode(',', $post['selectedid']);
        foreach ($idArr as $key => $id) {
            if ($idArr[$key] != '') {
                $data = array("in_deleted" => 1, "id" => $id);                                
                // Delete in mysql db
                $builder = $this->db->table('customers');
                $builder->where(['id' => $id]);
                $builder->update($data);
            }
        }
        return 1;
    }

	public function save_data($post) {	
            
			if (empty($post["student_id"])) {
			   $data = array(  
								"fname"		=> ($post["fname"])?$post["fname"]:'',
								"lname"		=> ($post["lname"])?$post["lname"]:'',
								"class"			=> ($post["class"])?$post["class"]:'',
								"section"				=> ($post["section"])?$post["section"]:'',															
							);             
            $builder = $this->db->table('students');
            $builder->insert($data);
            $lastInsertId = $this->db->insertId();  
			return $lastInsertId;
			}
			  
			else{		
			// edit 
			$data = array(  
								"fname"		=> ($post["fname"])?$post["fname"]:'',
								"lname"		=> ($post["lname"])?$post["lname"]:'',
								"class"		=> ($post["class"])?$post["class"]:'',
								"section"	=> ($post["section"])?$post["section"]:'',															
							);         
        
			$builder = $this->db->table('students');
			$builder->where('id', $post["student_id"]);
			$builder->update($data); 
			return 1;
		}    
	}	
	
	
	public function remove($id) {           
			$builder = $this->db->table('students');
			$builder->where('id', $id);
			$builder->delete(); 
			return 1;	   
	}	
}
