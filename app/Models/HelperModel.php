<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use mysqli;
use App\Controllers\ESscript;

class HelperModel extends Model
{
    protected $db;


    // Return the table data (parameter $columns => comma seperated string value )
    // Order  [id,asc] or [id,desc] => [column_name,type]
    public function get_list($table, $where = "", $select = "", $order_by = [], $group_by = '', $limit = "")
    {

        $builder = $this->db->table($table);

        if ($select != "")
            $builder->select($select);

        if ($where != "")
            $builder->where($where);

        if (!empty($order_by)) {
            $builder->orderBy($order_by[0], $order_by[1]);
        }

        if ($group_by != "")
            $builder->groupBy($group_by);

        if ($limit != ""){ 
            if(is_array($limit) && !empty($limit))
                $builder->limit($limit[0], $limit[1]);
            else
                $builder->limit($limit);
        }

        $res = $builder->get()->getResultArray();
        //echo $this->db->getLastQuery();
        return $res;
    }

    // Return the single row table data (parameter $columns => comma seperated string value )
    // Order  [id,asc] or [id,desc] => [column_name,type]
    public function get_single_row($table, $where = "", $select = "", $order_by = [])
    {
        $builder = $this->db->table($table);

        if ($select != "")
            $builder->select($select);

        if ($where != "")
            $builder->where($where);

        if (!empty($order_by)) {
            $builder->orderBy($order_by[0], $order_by[1]);
        }
        $res = $builder->limit(1)->get()->getResultArray();
       //echo $this->db->getLastQuery();
        return (!empty($res) ? $res[0] : $res);
    }

    //Get all rows from table
    public function get_all_rows($table, $where = "", $select = "")
    {
        $builder = $this->db->table($table);

        if ($select != "")
            $builder->select($select);

        if ($where != "")
            $builder->where($where);

        $res = $builder->get()->getResultArray();
        return (!empty($res) ? $res[0] : $res);
    }

    // Inserting single record
    public function insert_single_record($table, $data)
    {
        $builder = $this->db->table($table);
        $result = $builder->insert($data);

        return $result;
    }

    // Return db atble column info
    public function set_db_collation()
    {
        $stmt = "SHOW tables";
        $query = $this->db->query($stmt);
        $result = $query->getResultArray();

        $result = array_map(function ($row) {
            return $row["Tables_in_" . DB_NAME];
        }, $result);

        foreach ($result as $table) {
            $res = $this->db->query("ALTER TABLE $table CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
        }
    }

    // Return db atble column info
    public function set_db_client_configuration($client_id = '')
    {
        // Set config types first (default count mapped to value col)
        $type_data = [
            ['id' => '1', 'name' => 'Staff Count', 'default_count' => '10'],
            ['id' => '2', 'name' => 'User Count', 'default_count' => '10'],
            ['id' => '3', 'name' => 'Email Count', 'default_count' => '100'],
            ['id' => '4', 'name' => 'SMS Count', 'default_count' => '100'],
            ['id' => '5', 'name' => 'WhatsApp Count', 'default_count' => '100'],
            ['id' => '6', 'name' => 'Vehicle Ads count', 'default_count' => '10'],
            ['id' => '7', 'name' => 'Refund Policy (Days)', 'default_count' => '28'],
            ['id' => '8', 'name' => 'Ads Unique Key', 'default_count' => '0'],
            ['id' => '9', 'name' => 'Ads Allowed Link', 'default_count' => '0'],
            ['id' => '10', 'name' => 'Tax Calculation (Inclusive)', 'default_count' => '0'],
            ['id' => '11', 'name' => 'Vehicle Ads Client ID (OCD)', 'default_count' => '0'],
            ['id' => '12', 'name' => 'Traffic Fine Service Charge', 'default_count' => '0'],
            ['id' => '13', 'name' => 'Toll Service Charge', 'default_count' => '0'],
            ['id' => '14', 'name' => 'Multi Currency Enabled', 'default_count' => '0'],
            ['id' => '15', 'name' => 'Custom Invoice / Term Template', 'default_count' => '0'],
            ['id' => '16', 'name' => 'Invoice Remianing Amount PDF (Optional)', 'default_count' => '0'],
            ['id' => '17', 'name' => 'Tax for fine and tolls[FINE,SALIK,DARB]', 'default_count' => '[0,0,0]'],
            ['id' => '18', 'name' => 'Fee Auto Calculation', 'default_count' => '0'],
            ['id' => '19', 'name' => 'External Company (Vehicle Listing)', 'default_count' => '0'],
            ['id' => '20', 'name' => 'Start booking No', 'default_count' => '0'],
            ['id' => '21', 'name' => 'Client Color Code', 'default_count' => '#fba501'],
            ['id' => '22', 'name' => 'Tax Label for Invoice', 'default_count' => 'Invoice'],
            ['id' => '23', 'name' => 'Parking Service Charge', 'default_count' => '0'],
        ];
        $builder = $this->db->table('client_configuration_type');

        $builder->truncate();
        $builder->insertBatch($type_data);

        // Costruct data client/company configs
        if ($client_id != "") {
            $client_list = [["id" => $client_id]];
        } else {
            $client_list = get_list_helper('company', "id", "in_deleted=0");
        }

        $data = [];
        foreach ($client_list as $client) {
            // Check config already exist and insert if not exist only
            foreach ($type_data as $config_type) {
                // Check for config already exist
                $client_config = get_single_row_helper('client_configuration', "id", "client_id=" . $client['id'] . " and client_configuration_type_id=" . $config_type['id'] . " and in_deleted=0");

                if (empty($client_config)) {
                    if ($config_type['id'] == 17) {

                        $config_type['default_count'] = json_decode($config_type['default_count'], true);  
                        $value = is_array($config_type['default_count']) ? json_encode($config_type['default_count']) : '';
                        
                    } else {
                        $value = ($config_type['default_count'] == 0 ? '' : $config_type['default_count']);
                    }
                    
                    $data[] = [
                        'client_id' => $client['id'],
                        'client_configuration_type_id' => $config_type['id'],
                        'value' => $value
                    ];
                  
                 
                }

                

                
            }
        }

        $builder = $this->db->table('client_configuration');
        return $builder->insertBatch($data);
    }


    // Adding log of send email, sms & whatsapp
    public function add_communication_log($data)
    {
        $builder = $this->db->table('communication_log');
        return $builder->insertBatch($data);
    }

    public function get_next_inc_id($table, $inc = '')
    {
        $query = $this->db->query("SHOW TABLE STATUS LIKE '$table'");
        $row = $query->getResultArray();
        return $row[0]['Auto_increment'] + $inc;
    }


    // 1st parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables
    function db_backup_dwn($tables = false, $backup_name = false)
    {

        $mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
        $mysqli->select_db(DB_NAME);
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables    = $mysqli->query('SHOW TABLES');
        while ($row = $queryTables->fetch_row()) {
            $target_tables[] = $row[0];
        }

        $target_tables = array_values(array_diff( $target_tables, ['extesnion_data_log', 'events']));
        
        if ($tables !== false) {
            $target_tables = array_intersect($target_tables, $tables);
        }
        foreach ($target_tables as $table) {
            
            // checlong for client_id or company_id
            $table_fields = $mysqli->query("SHOW COLUMNS FROM ".$table."")->fetch_all();
            $client_id = array_filter($table_fields, function($row){ return ($row[0] == 'company_id' || $row[0] == 'client_id');});
            
            $where_condition = "";
            if(!empty($client_id) && isset($_GET['client_id']) && in_array($table, ['salik', 'receipt', 'invoice_detail'])){
                $client_id_info = array_values($client_id)[0];
                $where_condition = " WHERE ".$client_id_info[0]." = '".$_GET['client_id']."'";
            }

            $result         =   $mysqli->query('SELECT * FROM ' . $table." ".$where_condition);
            $fields_amount  =   $result->field_count;
            $rows_num = $mysqli->affected_rows;
            $res            =   $mysqli->query('SHOW CREATE TABLE ' . $table);
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n" . $TableMLine[1] . ";\n\n";

            $record_from = 0;
            if(intdiv($rows_num, 10000) > 0){
                $record_from = $rows_num - 10000;
            }
            for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
                
                while ($row = $result->fetch_row()) { //when started (and every after 100 command cycle):
                    
                    if(($st_counter < $record_from) && $table != 'invoice_detail' && $table != 'receipt'){
                        $st_counter = $st_counter + 1;
                        continue;
                    }
                        
                    if ($st_counter % 100 == 0 || $st_counter == 0) {
                        $content .= "\nINSERT INTO " . $table . " VALUES";
                    }; 
                    $content .= "\n(";
                    for ($j = 0; $j < $fields_amount; $j++) {
                        $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                        if (isset($row[$j])) {
                            $content .= '"' . $row[$j] . '"';
                        } else {
                            $content .= '""';
                        }
                        if ($j < ($fields_amount - 1)) {
                            $content .= ',';
                        }
                    }
                    $content .= ")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
                        $content .= ";";
                    } else {
                        $content .= ",";
                    }
                    $st_counter = $st_counter + 1;
                }
                $content .= "\n\n\n";
            }
        } 
        
        //$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
        $date = date("Y-m-d");
        $backup_name = $backup_name ? $backup_name : $name . ".$date.sql";
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $backup_name . "\"");
        echo $content;
        exit;
    }

    function get_db_info()
    {
        return $this->db;
    }

    function insert_invoice_detail($values){
        
        $builder = $this->db->table("invoice_detail");
        $rows = $builder->insertBatch($values);
        
        
        $new_invid= $this->db->insertID();
        $es = new ESscript();
        for($i=0;$i<$rows;$i++){
        $es->update_es('invoice_detail',  $new_invid+$i);
        }
        return 1;
    }
}
