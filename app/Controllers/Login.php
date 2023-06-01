<?php namespace App\Controllers;
  
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\DepartementModel;
use App\Models\FacultyModel;
use App\Models\DoctorantModel;
  
class Login extends Controller
{
    public function index()
    {
        helper(['form']);
        echo view('login');
    } 
  
    /**
     * Open a session using a password ans an email.
     */
    public function auth()
    {
        $session = session();
        $model = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('pwd');
        if( empty($email) or (empty($password)) ){
            $session->setFlashdata('msg', 'Les informations de login ne peuvent être vide!');
            return redirect()->to('/login');
        }
        $data = $model->where('email', $email)->first();
        if($data){
            $pass = $data['pwd'];
            $verify_pass= password_verify($password, $pass);
            if($verify_pass){
                $deptModel=new DepartementModel();
                $doctorant_id=-1;
                if($data['role']==0){
                    $docModel=new DoctorantModel();
                    $doctorants=$docModel->where("user_id",$data['id'])->first();
                    $doctorant_id=$doctorants['id'];
                }
                $ses_data = [
                    'id'       => $data['id'],
                    'role'     => $data['role'],
                    'email'    => $data['email'],
                    'departement_id'=>$data['departement_id'],
                    'faculty_id'=>$data['faculty_id'],
                    'doctorant_id'=>$doctorant_id,
                    'logged_in'     => TRUE
                ];
                $session->set($ses_data);
                log_message('info',' User logged In'.$session->get('email'));
                if($data['role']==0){//STUDENT.
                    
                 return redirect()->to('/accueil');
                }
            }else{
                $session->setFlashdata('msg', 'Le mot de passe est incorrect !');
                return redirect()->to('/login');
            }
        }else{
            $session->setFlashdata('msg', 'Cette adresse email n\'existe pas!');
            return redirect()->to('/login');
        }
    }
  
    public function logout()
    {
        $session = session();
        log_message('info',' User logged out'.$session->get('email'));
        $session->destroy();
        return redirect()->to('/login');
    }
} 