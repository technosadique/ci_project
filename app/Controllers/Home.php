<?php namespace App\Controllers;
use App\Models\StudentsModel;
use CodeIgniter\Files\File;
use Mpdf\Mpdf;

class Home extends BaseController
{
	protected $helpers = [];
	public function __construct(){
		$session = \Config\Services::session();		
		$validation = \Config\Services::validation();		
		helper(['url', 'form', 'custom','custom_email','my_helper']);			
	}
	public function index()
	{	// hello();
	    $data=[];
		
		//fetch data from helper 
		//$student_detail = get_single_row_helper("students", "*", "in_deleted=0 and id='19'");
		//$student_data = get_list_helper('students', "*", "in_deleted=0");
		//print_r($student_data); die;
		
	    $model=new StudentsModel();
		//print_r($_POST); die;
		if(isset($_POST) && $_POST['username'] !='' && $_POST['password'] !=''){
			
			$check_credential=$model->check_login($_POST);
			//print_r($check_credential); die;
			if(isset($check_credential) && count($check_credential) > 0){
				$_SESSION['username'] = $check_credential['username'];
				
				return redirect()->to(BASE_URL.'listing');exit;
			}
			else{
				session()->setFlashdata('error', 'Wrong username/password!');
				return redirect()->to(BASE_URL);exit;
			}	
			
		}
		
		
		return view('login-view',$data);
	}
	
	public function listing()
	{	
	
		if(!$_SESSION['username']){
			return redirect()->to(BASE_URL);exit;
		}
	
	    $data=[];
	    $model=new StudentsModel();
		$search = $this->request->getVar('search');
		$sort = $this->request->getVar('sort') ?? 'id';
        $order = $this->request->getVar('order') ?? 'asc';
		
		if($search !=''){
		$data['students']=$model->orderBy($sort, $order)->where('fname',$search)->paginate(5);
		}
		else{
		$data['students']=$model->orderBy($sort, $order)->paginate(5);	
		}
		
		$data['pager'] = $model->pager;	
		$data['sort'] = $sort;
        $data['order'] = ($order === 'asc') ? 'desc' : 'asc';
		
		return view('home-view',$data);
	}
	
	
	public function add_student(){
		if(!$_SESSION['username']){
			return redirect()->to(BASE_URL);exit;
		}
		$data['title'] = 'Add Student';
		return view('student-add',$data);
	}
	
	public function save_data(){
		if(!$_SESSION['username']){
			return redirect()->to(BASE_URL);exit;
		}
		$post = $_POST;		
		$model=new StudentsModel();
		
		$check=$this->validate(
			[   
				'fname'=>'required',
				'lname'=>'required',
				'class'=>'required',
				'section'=>'required',	
				'image' => [
                'rules' => 'uploaded[image]|is_image[image]|max_size[image,2048]|mime_in[image,image/jpg,image/jpeg,image/png]',
                'label' => 'Image'
				]
			]
		);
		
		if(!$check){ 
			return view('student-add',['validation'=>$this->validator]);
		}
		// Get the uploaded file
        $file = $this->request->getFile('image');
		// Move the file to a directory (e.g., public/uploads)
		$uploadPath = 'public/uploads/';
		$fileName = $file->getRandomName();
		$file->move($uploadPath, $fileName);
		
		$model->save([
            'fname' => $this->request->getPost('fname'),
            'lname' => $this->request->getPost('lname'),
            'class' => $this->request->getPost('class'),
            'section' => $this->request->getPost('section'),
            'doc' => $fileName,
        ]);
		
		if($post['student_id'] == ''){ 
		session()->setFlashdata('message', 'Added successfully.');
		}		
		return redirect()->to(BASE_URL.'listing');exit;
	}
	
	
	public function update_data(){
		if(!$_SESSION['username']){
			return redirect()->to(BASE_URL);exit;
		}
		$post = $_POST;		
		$model=new StudentsModel();		
		$check=$this->validate(
			[   
				'fname'=>'required',
				'lname'=>'required',
				'class'=>'required',
				'section'=>'required',		
			]
		);
		
		if(!$check){ 		
		$data['students'] = $model->find($post['student_id']);
		$data['validation']=$this->validator;
		return view('student-edit',$data);			
		}	

		// Get the uploaded file
        $file = $this->request->getFile('image');
		if($file !=''){
		// Move the file to a directory (e.g., public/uploads)
		$uploadPath = 'public/uploads/';
		$fileName = $file->getRandomName();
		$file->move($uploadPath, $fileName);
		}
		
		if($fileName !=''){
		$model->update($post['student_id'], [
            'fname' => $this->request->getPost('fname'),
            'lname' => $this->request->getPost('lname'),
            'class' => $this->request->getPost('class'),
            'section' => $this->request->getPost('section'),
			'doc' => $fileName,
        ]);
		}
		else{
			$model->update($post['student_id'], [
            'fname' => $this->request->getPost('fname'),
            'lname' => $this->request->getPost('lname'),
            'class' => $this->request->getPost('class'),
            'section' => $this->request->getPost('section'),			
        ]);
		}
		
		
		
		session()->setFlashdata('message', 'Updated successfully.');		
		return redirect()->to(BASE_URL.'listing');exit;
	}
	
	public function edit($id = null){
		if(!$_SESSION['username']){
			return redirect()->to(BASE_URL);exit;
		}
		$data=[];
		$model=new StudentsModel();		
		$data['students'] = $model->find($id);
		$imageUrl=base_url('public/uploads/' . $data['students']['doc']);
		$data['imageUrl'] = $imageUrl;
		$data['title'] = 'Edit Student';
		return view('student-edit',$data);
	}
	
	public function remove($id = null){ 
		if(!$_SESSION['username']){
			return redirect()->to(BASE_URL);exit;
		}		
		$model=new StudentsModel();		
		$model->delete($id);
		
		session()->setFlashdata('message', 'Deleted successfully.');
		return redirect()->to(BASE_URL.'listing');exit;
	}
	
	public function send_email(){ 
		$to='sadik.jsr@gmail.com';
		$toname='sadik';
		$subject='Test Email from local';
		$message='Test content from local';
		$attachment='';
		$status = send_email($to, $toname, $subject, $message, $attachment);
		
		print_r($status); die;
		session()->setFlashdata('message', 'Email Sent successfully.');
		return redirect()->to(BASE_URL.'listing');exit;
	}
	
	public function generate_pdf(){
		if(!$_SESSION['username']){
			return redirect()->to(BASE_URL);exit;
		}

		$model=new StudentsModel();
		//$data['students']=$model->get_students();
		$data['students']=$model->findAll();
		
		$html = view('sample-pdf-view',$data);   		
		$mpdf =new \Mpdf\Mpdf(['default_font' => 'A_Nefel_Botan']);			
	    $stylesheet = file_get_contents(BASE_URL.'public/assets/css/pdf.css'); // external css
		$mpdf->WriteHTML($stylesheet,1);  
		$mpdf->WriteHTML($html,2);   
	    $mpdf->Output("receipt_" . date("ymd") . ".pdf", 'I');
        exit;
	   
	}
	
	public function generate_csv(){
        if(!$_SESSION['username']){
			return redirect()->to(BASE_URL);exit;
		}
		$model=new StudentsModel();
		//$report_data=$model->get_students();	
		$report_data=$model->findAll();
		$f = fopen('php://memory', 'w'); // Set header
		$seq = 1;
        $header = ['Sl No.', 'Name', 'Class', 'Section'];
		fputcsv($f, $header, ',');
		
		foreach ($report_data as $row) {
			$row_data = [	
                    $seq++,	
					ucwords($row['fname']),
					($row['class']),
					($row['section'])					
				];				
				// Generate csv lines from the inner arrays
				fputcsv($f, $row_data, ','); 
		}
		fseek($f, 0);
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="Student_Report' . '_' . date('dmy') . '.csv";');
		fpassthru($f);	
		
	}
	
	public function logout()
	{
	    session()->remove('username');
		return redirect()->to(BASE_URL);exit;
	}
	
}