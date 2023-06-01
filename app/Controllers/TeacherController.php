<?php 
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\FacultyModel;
use App\Models\EnseignantModel;
use App\Models\DepartementModel;
class TeacherController extends Controller
{
    /**
     * ADMIN
     * Lister les Enseignants pour la faculté.
     * Selon le departement ID ou bien selon
     * le type.
     * departement_id=-1 --> lister tous les départements.
     */
    public function findTeacherByNameAR(){
        $session = session();
        if($session->get('role')>1){
            $nom_ar=$this->request->getVar('nom_ar');
            $prenom_ar=$this->request->getVar('prenom_ar');
                 
        $db      = \Config\Database::connect();
        $builder->select("
            enseignant.id,
            enseignant.nom_ar,
            enseignant.prenom_ar,
            enseignant.nom_fr,
            enseignant.prenom_fr,
            enseignant.grade,
            departement.nom as dept_nom");
        $builder = $db->table('enseignant');
        $builder->join('departement', 'enseignant.departement_id = departement.id');
        $query = $builder->get();
        $teacherList=$query->getResultArray();
        $data['teacherList']=$teacherList;
        if($session->get('role')==1)
        return view('list-teacher', $data);
    else if ($session->get('role')==2)
        return view('fac-list-teacher', $data);            
    else if ($session->get('role')==3)
        return view('vrpg-list-teacher', $data);
    else if ($session->get('role')==4)
        return view('vrpg-list-teacher', $data);        
    }
       else echo "<b>Access Denied</b>";
    }

    public function  findTeacherByNameAR_all(){
        $session = session();
        if($session->get('role')>=1){
        $db      = \Config\Database::connect();
        $builder = $db->table('enseignant');
        
        $builder->select("
            enseignant.id,
            enseignant.nom_ar,
            enseignant.prenom_ar,
            enseignant.nom_fr,
            enseignant.prenom_fr,
            enseignant.grade,
            enseignant.univOrigine_ar,
            departement.nom as dept_nom");

        $builder->join('departement', 'enseignant.departement_id = departement.id','left');
        $builder->orderBy('enseignant.id','DESC');
        $query = $builder->get();
        $teacherList=$query->getResultArray();
        $data['teacherList']=$teacherList;
        $facultyModel = new FacultyModel();
        $data['faculty'] = $facultyModel->orderBy('nom', 'ASC')->findAll();
        if($session->get('role')==1)
            return view('list-teacher', $data);
        else if ($session->get('role')==2)
            return view('fac-list-teacher', $data);            
        else if ($session->get('role')==3)
            return view('vrpg-list-teacher', $data);
        else if ($session->get('role')==4)
            return view('vrpg-list-teacher', $data);            
        }
        else echo "<b>Access Denied</b>";
    }

    public function storeTeacher(){
        $session = session();
        if($session->get('role')>=1){
            $teacherM=new EnseignantModel();
            $nom_ar=$this->request->getVar('nom_ar');
            $nom_ar=str_replace("الله"
            ,"اللـه",
            $nom_ar);
            $prenom_ar=$this->request->getVar('prenom_ar');
            $prenom_ar=str_replace("الله"
            ,"اللـه",
            $prenom_ar);
            $univOrigine_ar=$this->request->getVar('univOrigine');
            $univOrigine_ar=str_replace("الله"
            ,"اللـه",
            $univOrigine_ar);
         $data = [
            'grade'=>$this->request->getVar('grade'), 
            'nom_ar'=>$nom_ar,
            'prenom_ar'=>$prenom_ar,
            'departement_id',
            'faculty_id'=>$this->request->getVar('faculty_id'),
            'nom_fr'=>$this->request->getVar('nom_fr'),
            'prenom_fr'=>$this->request->getVar('prenom_fr'),
            'externe'=>$this->request->getVar('externe'),
            'email'=>$this->request->getVar('email'),
            'univOrigine_ar'=>$univOrigine_ar
        ];
        $teacherM->insert($data);
        return $this->response->redirect(site_url('/findTeacherByNameAR_frm'));      
    }
}

public function storeTeacherAJAX(){
    $session = session();
    if($session->get('role')>=1){
        $teacherM=new EnseignantModel();
        $nom_ar=$this->request->getVar('nom_ar');
        $nom_ar=str_replace("الله"
        ,"اللـه",
        $nom_ar);
        $prenom_ar=$this->request->getVar('prenom_ar');
        $prenom_ar=str_replace("الله"
        ,"اللـه",
        $prenom_ar);
        $univOrigine_ar=$this->request->getVar('univOrigine');
        $univOrigine_ar=str_replace("الله"
        ,"اللـه",
        $univOrigine_ar);
     $data = [
        'grade'=>$this->request->getVar('grade'), 
        'nom_ar'=>$nom_ar,
        'prenom_ar'=>$prenom_ar,
        'departement_id',
        'faculty_id'=>$this->request->getVar('faculty_id'),
        'nom_fr'=>$this->request->getVar('nom_fr'),
        'prenom_fr'=>$this->request->getVar('prenom_fr'),
        'externe'=>$this->request->getVar('externe'),
        'email'=>$this->request->getVar('email'),
        'univOrigine_ar'=>$univOrigine_ar
    ];
    $id=$teacherM->insert($data,true);
    $data['id']=$id;
    echo json_encode($data);
}
}

    public function updateTeacherData(){
        $session = session();
        if($session->get('role')>=1){
            $teacherM=new EnseignantModel();
            $id=$this->request->getVar('id');
            $nom_ar=$this->request->getVar('nom_ar');
            $nom_ar=str_replace("الله"
            ,"اللـه",
            $nom_ar);
            $prenom_ar=$this->request->getVar('prenom_ar');
            $prenom_ar=str_replace("الله"
            ,"اللـه",
            $prenom_ar);
            $univOrigine_ar=$this->request->getVar('univOrigine');
            $univOrigine_ar=str_replace("الله"
            ,"اللـه",
            $univOrigine_ar);
         $data = [
            'grade'=>$this->request->getVar('grade'), 
            'nom_ar'=>$nom_ar,
            'prenom_ar'=>$prenom_ar,
            'departement_id',
            'faculty_id'=>$this->request->getVar('faculty_id'),
            'nom_fr'=>$this->request->getVar('nom_fr'),
            'prenom_fr'=>$this->request->getVar('prenom_fr'),
            'externe'=>$this->request->getVar('externe'),
            'email'=>$this->request->getVar('email'),
            'univOrigine_ar'=>$univOrigine_ar
        ];
        $teacherM->update($id,$data);
        return $this->response->redirect(site_url('/findTeacherByNameAR_frm'));      
        }
        else echo "<b>Access Denied</b>"; 
    }
    public function   load_edit_teacher($id){
        $session = session();
        if($session->get('role')>=1){
            $facultyModel = new FacultyModel();
            $data['faculty'] = $facultyModel->orderBy('nom', 'ASC')->findAll();

            $ensModel = new EnseignantModel();
            $ens_obj=$ensModel->where('id', $id)->first();
            $data['ens'] = $ens_obj;
        return view('edit_teacher_form', $data);
        }
        else echo "<b>Access Denied</b>";
    }

}
