<?php
class Scripts extends CI_Model {

	function __construct() {
        parent::__construct();
        $this->load->database();
		$this->load->library('session');
	}
	
	
	/* FRONT PAGE FUNCTIONS */
	
		public function GetAllData($Query){
			$Query 					= 	$this->db->query($Query);
			$data['Rows_Count']		=	$Query->num_rows();
			$data['Result']			=	$Query->result();
			return $data;
		}
		
	/* FRONT PAGE FUNCTIONS */
	
	public function gettitle(){
	
	$query = $this->db->query('SELECT * FROM settings WHERE ID = 1');	
	$result = $query->result();
	$Title = $result[0];
	$Title = $Title->Title;
	return $Title;
		
	}

	public function login($Email, $PasswordEncrypted, $table) {
			$this->db->select('*');
			$this->db->from($table);
			$this->db->where('EmailAddress', $Email);
			$this->db->where('Password', $PasswordEncrypted);
			$this->db->limit(1);
			return $query = $this->db->get();
			
	}
	
	public function login_check($Email, $Pass) {
			
			$table = 'employee';
			$query = $this->login($Email, $Pass, $table);
			if($query->num_rows() != 1){
			$table = 'employer';
			$query = $this->login($Email, $Pass, $table);	
			}
			$var['table'] = $table;
			$var['query']   = $query;			
			return $var;
			
	}
	
	
	public function checkpass($PasswordEncrypted, $ID, $table) {
			$this->db->select('*');
			$this->db->from($table);
			$this->db->where('ID', $ID);
			$this->db->where('Password', $PasswordEncrypted);
			$this->db->limit(1);
			return $query = $this->db->get();
			
	}
	
	public function forgot($Email, $table) {
			$this->db->select('*');
			$this->db->from($table);
			$this->db->where('EmailAddress', $Email);
			$this->db->limit(1);
			return $query = $this->db->get();
			
	}
	
	public function dateob($DOB){
	 $birthDate = $DOB;
	 //explode the date to get month, day and year
	 $birthDate = explode("/", $birthDate);
	 $count = count($birthDate);
	 if($count == 3){
	 //get age from date or birthdate
	 $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
	 return $age;
	 } else {
		 return $age = 0;
	 }
	 }
	
	public function do_change_pass($Table, $data, $ID){
		    $this->db->where('ID', $ID);
			$query = $this->db->update($Table, $data); 
			return $query;
	}
	
	public function Select($Table, $Where){
			$query = $this->db->query('SELECT * FROM `'.$Table.'` '.$Where.'  Order BY `ID` DESC');
			return $query->result();
	}
	
	public function SelectOrder($Table, $Where, $Order){
			$query = $this->db->query('SELECT * FROM `'.$Table.'` '.$Where.' ' . $Order);
			/*
			if($Table == 'highest_qualification'){
			echo $this->db->last_query();
			die();
			}
			*/
			return $query->result();
	}
	
	public function Select2($Table, $Where){
			$query = $this->db->query('SELECT * FROM `'.$Table.'` '.$Where.'  Order BY `ID` ASC');
			return $query->result();
	}
	
	public function Select_resume($Table, $Where, $field, $Orderby){
			$query = $this->db->query('SELECT * FROM `'.$Table.'` '.$Where.'  Order BY `' . $field . '`' . $Orderby);
			return $query->result();
	}
	
	public function Select_Jobs($ID){
			$query = $this->db->query('SELECT a.ID, a.`Title`, a.Status, a.SEO, a.`Description`, b.`Country`, c.`City`, d.`JobTitle`, count(e.`ID`) AS Response FROM `jobs` AS a LEFT JOIN `country` AS b ON b.`ID` = a.`Country` LEFT JOIN `city` AS c ON c.`ID` = a.`city` LEFT JOIN `job_title` AS d ON d.`ID` = a.`JobTitle` LEFT JOIN `jobs_response` AS e ON a.`ID` = e.`JobsID` WHERE a.`EmployerID` = ' . $ID . ' GROUP BY a.`ID` Order BY a.`ID` DESC ');
			return $query->result();
	}
	
	public function Select_Contacts($ID){
			$query = $this->db->query('SELECT a.ID, a.Status, b.FirstName, b.LastName, b.SEO, a.CreateDate FROM `contacts` a, `employee` b WHERE a.EmployeeID = b.ID AND a.EmployerID = ' . $ID . ' Order BY a.`ID` DESC ');
			return $query->result();
	}
	
	public function Select_Jobs_Response($ID){
			$query = $this->db->query('SELECT a.ID, b.`Title`, b.`SEO` AS JobSEO, c.`FirstName`, c.`LastName`, c.`SEO`, a.CreateDate FROM `jobs_response` a, `jobs` b, `employee` c WHERE a.`JobsID` = b.`ID` AND c.`ID` = a.`EmployeeID` AND a.`EmployerID` = ' . $ID);
			return $query->result();
	}
	
	public function count_data($table){
			$query = $this->db->query('SELECT count(1) AS Total FROM `'.$table.'` WHERE Status = 1');
			return $query->result();
	}
	
	public function view_all($table){
			$query = $this->db->query('SELECT * FROM `'.$table.' `Order BY `ID` DESC');
			return $query->result();
	}
	
	public function option($Table, $Field){
			$query = $this->db->query('SELECT * FROM `'.$Table.'` WHERE `Status` = "1" Order BY ' . $Field .' ASC');
			return $query->result();
	}
	
	public function do_freelance($table, $fieldname, $input){
	
			$ID = $this->session->userdata('ID');
			$batch = array();
			
			$freelance = $input;
				foreach($freelance as $row){
					$batch[] = array(
					$fieldname => $row,
					'EmployeeID'	=> $ID
				);
			}
			
			$this->db->where('EmployeeID', $ID);
			$this->db->delete($table); 
			
			$this->db->insert_batch($table, $batch);
	}
	
	public function do_add($table, $data){
			$query 			= $this->db->insert($table, $data); 
			/*
			echo $this->db->last_query();
			die();
			*/
			$var['Created'] = $this->db->insert_id();
			$var['query']   = $query;			
			return $var;
	}
	
	public function do_edit($Table, $data, $Id){
		    $this->db->where('ID', $Id);
			$query = $this->db->update($Table, $data); 
			return $query;
	}
	
	public function do_update_ols($Table, $data, $Id){
		    $this->db->where('SOID', $Id);
			$query = $this->db->update($Table, $data); 
			return $query;
	}
	
	
	
	public function select_by_id($Table, $ID){
		$query = $this->db->query('SELECT * FROM `'.$Table.'` WHERE `ID` = "'.$ID.'"');
		return $query->result();
	}

	public function select_fl_id($Table, $ID){
		$query = $this->db->query('SELECT * FROM `'.$Table.'` WHERE `EmployeeID` = "'.$ID.'"');

		return $query->result();
	}
	
	public function select_by_code($Table, $Code){
		$this->db->select('*');
		$this->db->from($Table);
		$this->db->where('Verify', $Code);
		$this->db->limit(1);
		return $query = $this->db->get();
		
	}
	
	public function select_by_seo($Table, $SEO){
		$this->db->select('*');
		$this->db->from($Table);
		$this->db->where('SEO', $SEO);
		$this->db->limit(1);
		return $query = $this->db->get();
		
	}
	
	public function update_status($Table, $Code, $Data){
		    $this->db->where('Verify', $Code);
			$query = $this->db->update($Table, $Data); 
			return $query;
	}
	
	public function status($table, $data, $i, $ac){
		    $this->db->where('ID',(int)base64_decode($i));
			$query = $this->db->update($table, $data);; 
			return $query;
	}
	public function delete($table, $Id){
		    $this->db->where('ID',(int)base64_decode($Id));
			$query = $this->db->delete($table); 
			return $query;
	}
	
	public function deletesave($table, $ID, $EID){
		    $this->db->where('JobsID', $ID);
		    $this->db->where('EmployeeID', $EID);
			$query = $this->db->delete($table); 
			return $query;
	}
	
	public function deletesaveemployee($table, $ID, $EID){
		    $this->db->where('EmployeeID', $ID);
		    $this->db->where('EmployerID', $EID);
			$query = $this->db->delete($table); 
			return $query;
	}
	
	public function search($table, $Where2, $Orderby, $limit, $start){
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where("($Where2)"); 
		$this->db->order_by("$Orderby");
		$this->db->limit($limit, $start);
		$this->db->last_query();
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
		
        return false;
	}
	
	public function new_search($cquery){
			$query = $this->db->query($cquery);
			return $query->result();
    }
	
	public function record_count($cquery){
			$query = $this->db->query($cquery);
			return $query->result();
    }
    
    public function records_counts($table, $Where){
   $query = $this->db->query('SELECT * FROM `'.$table.'` ' .$Where);
   return $query->result();
    }
	public function select_by_id_key($table, $GetID, $KeyID){
		$query = $this->db->query('SELECT * FROM `'.$table.'` WHERE `'.$KeyID.'` = "'.$GetID.'"  Order BY `ID` DESC');
		return $query->result();
	}
}
?>
