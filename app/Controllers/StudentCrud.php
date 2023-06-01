<?php 
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\FacultyModel;
use App\Models\EnseignantModel;
use App\Models\DoctorantModel;
use App\Models\InscriptionModel;
use App\Models\DepartementModel;
use App\Models\DomaineModel;
use App\Models\FiliereModel;
use App\Models\SpecialiteModel;
use App\Models\demande_changement_sujetModel;
use App\Models\demande_changement_Encadrant;
use App\Models\User_FacultyModel;
use PhpParser\Comment\Doc;

class StudentCrud extends Controller
{
    /**
     * ADMIN
     * Lister les Doctorants pour la faculté.
     * Selon le departement ID ou bien selon
     * le type.
     * departement_id=-1 --> lister tous les départements.
     * $type==2 --> lister tous les types.
     */
    public function ListerStudentByFacID($departement_id=null,$type){
        $session = session();
        if($session->get('role')>1){
        $db      = \Config\Database::connect();
        $builder = $db->table('Doctorant'); 
        $builder->select(' 
        Doctorant.id,Doctorant.nom_fr,Doctorant.prenom_fr,
        Doctorant.nom_ar,Doctorant.prenom_ar,
        Doctorant.token,
        DatePremiereInscription,departement.nom as dept, Doctorant.filiere,
        Doctorant.specialite,Doctorant.type, 
        Doctorant.reserveDossierPapier,
        enseignant.nom_ar as encadrantNom, 
        enseignant.prenom_ar as encadrantPrenom,
        inscription.id as inscrit,
        inscription.approuver as inscit_approuver,
        Etape,
        DateSoutenance');
        $builder->join('faculty', 'Doctorant.fac_id=faculty.id');
        $builder->join('departement','faculty.id=departement.faculty_id');
        $builder->join('enseignant','Doctorant.encadrant_id=enseignant.id');
        $builder->join('inscription','Doctorant_id=Doctorant.id ','left');
        $builder->join('dossierSoutenance','Doctorant.id=dossierSoutenance.Doctorant_id','left');
        $builder->where('faculty.id', $session->get('faculty_id'));
        $builder->where('departement.id=Doctorant.dept_id
        AND (Etape is NULL OR (Etape <> 14 AND Etape <> 11))
        AND (DateSoutenance is NULL OR date_format(str_to_date(DateSoutenance, \'%Y-%m-%d\'), \'%Y-%m-%d\') 
        >= \''.Date("Y-m-d").'\' )
        AND (dossierPapierValide is NULL or (dossierPapierValide<>2 AND dossierPapierValide<>3))
        ', NULL, FALSE);
        if($departement_id!=-1)
            $builder->where('departement.id',$departement_id);
        if($type!=2)
            $builder->where('Doctorant.type',$type);
        $builder->orderBy('Doctorant.nom_fr','ASC');
        $query = $builder->get();
        $doctorantList=$query->getResultArray();
        $data["doctorants"]=$doctorantList;
        //-----
        $departementModel=new DepartementModel();
        $departementList=$departementModel->where(
            'faculty_id',$session->get('faculty_id')
            )->findAll();
        $data['departementList']=$departementList;
        $data['departement_id']=$departement_id;
        $data['type']=$type;
        $data['numberOfStudent']=count($doctorantList);
        //--
        return view('fac-admin', $data);
    }
       else echo "Access Denied";
    }

    public function ListerStudentByDeptID($departement_id=null){
       
        $session = session();
        if(($session->get('departement_id')==$departement_id)||
        ($session->get('role')>=3)){
            $db      = \Config\Database::connect();
            $builder = $db->table('Doctorant');
            $builder->select(' 
            Doctorant.id,
            Doctorant.nom_fr,
            Doctorant.prenom_fr,
            Doctorant.nom_ar,
            Doctorant.prenom_ar,
            Doctorant.token,
            DatePremiereInscription,departement.nom as dept, Doctorant.filiere,
            Doctorant.specialite,Doctorant.type, faculty.nom as fac,
            Doctorant.reserveDossierPapier,
            enseignant.nom_ar as encadrantNom, 
            enseignant.prenom_ar as encadrantPrenom,
            Etape,
            DateSoutenance
            ');
            $builder->join('enseignant','Doctorant.encadrant_id=enseignant.id');            
            $builder->join('faculty', 'Doctorant.fac_id=faculty.id');
            $builder->join('departement','faculty.id=departement.faculty_id');
            $builder->join('dossierSoutenance','Doctorant.id=dossierSoutenance.Doctorant_id','left');
            $builder->where('departement.id=Doctorant.dept_id 
            AND (Etape is NULL OR (Etape <> 14 AND Etape <> 11))
            AND (DateSoutenance is NULL OR date_format(str_to_date(DateSoutenance, \'%Y-%m-%d\'), \'%Y-%m-%d\') 
            >= \''.Date("Y-m-d").'\' )
            AND (dossierPapierValide is NULL or (dossierPapierValide<>2 AND dossierPapierValide<>3))
            ', NULL, FALSE);

            if($departement_id!=-1) 
                $builder->where('departement.id=', $departement_id);
            $builder->orderBy('Doctorant.nom_fr','ASC');
            $query = $builder->get();
            $doctorantList=$query->getResultArray();
            $data["doctorants"]=$doctorantList;
            $departementModel=new DepartementModel();
            $departementList=$departementModel->findAll();
            $data['departementList']=$departementList;
            $data['departement_id']=$departement_id;
            $data['numberOfStudent']=count($doctorantList);
            if($session->get('role')==1 )return view('dept-admin', $data);
            else if ($session->get('role')==3 )return view('vrpg-admin', $data);
       }
       else echo "Access Denied";
    }

    public function ListerStudentByDeptIDVRPG($faculty_id=null,$departement_id=null,$type=null,$avecDossier=null){
       
        $session = session();
        if(($session->get('role')>=3)){
            $db      = \Config\Database::connect();
            $builder = $db->table('Doctorant');
            $builder->select(' 
                Doctorant.id,
                Doctorant.nom_fr,
                Doctorant.prenom_fr,
                Doctorant.nom_ar,
                Doctorant.prenom_ar,
                Doctorant.token,
                DatePremiereInscription,
                departement.nom as dept, 
                Doctorant.specialite,
                Doctorant.type, 
                Doctorant.reserveDossierPapier,
                faculty.nom as fac, 
                enseignant.nom_ar as encadrantNom, 
                enseignant.prenom_ar as encadrantPrenom,
                dossierPapierValide,
                reserveDossierPapier,
                dossierSoutenance.DateSoutenance');
            $builder->join('faculty', 'Doctorant.fac_id=faculty.id');
            $builder->join('departement','faculty.id=departement.faculty_id');
            $builder->join('enseignant','Doctorant.encadrant_id=enseignant.id','left');
            $builder->join('dossierSoutenance','Doctorant.id=dossierSoutenance.Doctorant_id','left');
            // $builder->where('(dossierPapierValide is NULL or
            // (dossierPapierValide<>2 AND dossierPapierValide<>3)) 
            // AND departement.id=Doctorant.dept_id
            // AND (DateSoutenance is NULL OR date_format(str_to_date(DateSoutenance, \'%Y-%m-%d\'), \'%Y-%m-%d\') 
            // >= \''.Date("Y-m-d").'\' )
            // ', NULL, FALSE);

            //list all departement if == -1
            if($session->get('role')==3){
            if($faculty_id!=-1) 
                $builder->where('faculty.id=', $faculty_id);
			if($departement_id!=-1) 
                $builder->where('departement.id=', $departement_id);
            }
            if($type!=2) 
                $builder->where('type=', $type);

            if ($avecDossier == 3) //3: sans dossier
            {
                $builder->where('dossierPapierValide=',3);
                $builder->where('departement.id=Doctorant.dept_id
                ');
            }
            else 
                $builder->where('(dossierPapierValide is NULL or
                (dossierPapierValide<>2 AND dossierPapierValide<>3)) 
                AND departement.id=Doctorant.dept_id
                AND (DateSoutenance is NULL OR date_format(str_to_date(DateSoutenance, \'%Y-%m-%d\'), \'%Y-%m-%d\') 
                >= \''.Date("Y-m-d").'\' )
                ', NULL, FALSE);
                if($session->get('role')==4)
                {
                    $filterByFacID=[];    
                    $i=0;
                    $User_Faculty=new User_FacultyModel();
                    $userFacRes=$User_Faculty->where('id_user',$session->get('id'))->findAll();
                    foreach($userFacRes as $ufItem){
                        $filterByFacID[$i]=$ufItem['id_faculty'];
                        $i++;
                    }
                    $builder->whereIn('Doctorant.fac_id',$filterByFacID);  
        
                }                
                
            $builder->orderBy('Doctorant.nom_fr','ASC');
            $query = $builder->get();
            $doctorantList=$query->getResultArray();
            $data["doctorants"]=$doctorantList;
            
            $facultyModel = new FacultyModel();
            $data['faculty'] = $facultyModel->orderBy('nom', 'ASC')->findAll();
            $data['faculty_id']=$faculty_id;
			$data['departement_id']=$departement_id;
            $data['avecDossier']=$avecDossier;
			$departementModel=new DepartementModel();
            $departementList=$departementModel->where('faculty_id',$faculty_id)->findAll();
			$data['departementList']=$departementList;
            $data['type']=$type;
            $data['numberOfStudent']=count($doctorantList);
            return view('vrpg-admin', $data);
       }
       else echo "Access Denied";
    }

    public function ListerStudentByDeptID_JSON($departement_id=null){
        $session = session();
        if($session->get('role')>2){
            $db      = \Config\Database::connect();
            $builder = $db->table('Doctorant');
    
            $builder->select(' Doctorant.id,Doctorant.nom_fr,Doctorant.prenom_fr,
            DatePremiereInscription,departement.nom as dept, Doctorant.filiere,
            Doctorant.specialite,Doctorant.type');
            $builder->join('faculty', 'Doctorant.fac_id=faculty.id');
            $builder->join('departement','faculty.id=departement.faculty_id');
            $builder->where('departement.id=Doctorant.dept_id');
            $builder->where('departement.id=', $departement_id);
            $builder->orderBy('Doctorant.nom_fr','ASC');
            $query = $builder->get();
            $user=$query->getResultArray();
            echo json_encode($user);
       }
       else echo "Access Denied";
    }

    /**
     * ADMIN 
     * Delete a Student.
     */
    public function vrpg_delete_doctorant($id){
        $session = session();
        if($session->get('role')==3){
            
            $userModel = new UserModel();
            $doctorantModel   = new DoctorantModel();
            $inscriptionModel=new InscriptionModel();
            //first we get the doctorant itm.
            $doctorant_itm=$doctorantModel->where('id',$id)->first();
            //delete its inscription if exist.
            $inscriptionModel->where('Doctorant_id', $id)->delete();
            //delete its user if exist.
            $userModel->where('id',$doctorant_itm['user_id'])->delete();
            log_message('info',' Deleting Doctorant id '.$id.' User  '.$session->get('email'));
            //delete the doctorant.
            $doctorantModel->where('id', $id)->delete($id);
            return $this->response->redirect(site_url('/vrpg-admin/-1/-1/2/-1'));
        }
    }

    /**
     * ADMIN
     * Lister les Doctorants par faculty_id.
     */
    public function ListerStudentAll($faculty_id=null){
        $session = session();
        if($session->get('role')>=3){
            $db      = \Config\Database::connect();
            $builder = $db->table('Doctorant');
            $builder->select(' Doctorant.id,Doctorant.nom_fr,Doctorant.prenom_fr,
            DatePremiereInscription,departement.nom as dept, Doctorant.filiere,
            Doctorant.specialite,Doctorant.type, faculty.nom as fac');
            $builder->join('faculty', 'Doctorant.fac_id=faculty.id');
            $builder->join('departement','faculty.id=departement.faculty_id');
            $builder->where('departement.id=Doctorant.dept_id');
            $builder->orderBy('Doctorant.nom_fr','ASC');
            $query = $builder->get();
            $doctorantList=$query->getResultArray();
            $departementModel=new DepartementModel();
            $departementList=$departementModel->findAll();
            $data['departementList']=$departementList;
            $data["doctorants"]=$doctorantList;
            $data['numberOfStudent']=count($doctorantList);
        return view('vrpg-admin', $data);
     }
      else echo "Access Denied";
    }
    public function create($departement_id=null){
        $session = session();
        //only VRPG can add student.
        if($session->get('role')==3){
        $data[]="";
        $EnseignantModel = new EnseignantModel();
		$data['enseignant'] = $EnseignantModel->orderBy('nom_ar', 'ASC')->findAll();
        $domaineModel = new DomaineModel();
        $data['domaine'] = $domaineModel->orderBy('domaine_ar', 'ASC')->findAll();
        $filiereModel = new FiliereModel();
        $data['filiere'] = $filiereModel->orderBy('nom_ar', 'ASC')->findAll();
        return view("add_student_form",$data);
        }
        else echo "Access Denied";
    }

    /**
     * Load a Doctorant to edit.
     */
    public function load_doctorant_edit($id){
        $session = session();
        if($session->get('role')>=3){
            $data[]="";
            $EnseignantModel = new EnseignantModel();
            $data['enseignant'] = $EnseignantModel->orderBy('nom_ar', 'ASC')->findAll();
            $domaineModel = new DomaineModel();
            $data['domaine'] = $domaineModel->orderBy('domaine_ar', 'ASC')->findAll();
            $filiereModel = new FiliereModel();
            $data['filiere'] = $filiereModel->orderBy('nom_ar', 'ASC')->findAll();
            $doctorantModel   = new DoctorantModel();
            $doctorant=$doctorantModel->where('id', $id)->first();    
            $data['doctorant']=$doctorant;   
            if($session->get('role')==1)       
            return view("edit_student_form",$data);
            else if (($session->get('role')==3)||
            ($session->get('role')==2)||
            ($session->get('role')==4))
            return view("vrpg_edit_student_form",$data);
        } else echo "Access Denied !";
    }

    /**
     * save updated infotmation of the doctorant.
     */
    public function submit_form_doctorant_update(){
        $session = session();
        if($session->get('role')>=1){
        $doctorantModel   = new DoctorantModel();
        $userModel = new UserModel();
        $inscriptionModel=new InscriptionModel();
        
        $db      = \Config\Database::connect();
                
        $domaine=$this->request->getVar('domaine');
        $domaine_ar=$this->request->getVar('domaine_ar');
       
        $filiere=$this->request->getVar('filiere');
        $filiere_ar=$this->request->getVar('filiere_ar');
        
        $specialite=$this->request->getVar('specialite');
        $specialite_ar=$this->request->getVar('specialite_ar');
        $DatePremiereInscription=$this->request->getVar('DatePremiereInscription');
        
        $co_encadrant_id=($this->request->getVar('co_encadrant_id')==-1)?null:$this->request->getVar('co_encadrant_id');

        $nom_ar=$this->request->getVar('nom_ar');
            $nom_ar=str_replace("الله"
            ,"اللـه",
            $nom_ar);
            $prenom_ar=$this->request->getVar('prenom_ar');
            $prenom_ar=str_replace("الله"
            ,"اللـه",
            $prenom_ar);

        $dataDoc=[
            'nom_fr'=>$this->request->getVar('nom_fr'),
            'prenom_fr'=>$this->request->getVar('prenom_fr'),
            'date_nec'=>$this->request->getVar('date_nec'),
            'lieu_nec'=>$this->request->getVar('lieu_nec'),
            'nom_ar'=>$nom_ar,
            'prenom_ar'=>$prenom_ar,
            //'sujet'=>$this->request->getVar('sujet'),
            'sexe'=>$this->request->getVar('sexe'),
            'type'=>$this->request->getVar('type'),
            'filiere'=>$filiere,
            'specialite'=>$specialite,
            'domaine'=>$domaine,
            'filiere_ar'=>$filiere_ar,
            'specialite_ar'=>$specialite_ar,
            'domaine_ar'=>$domaine_ar,
            'date_nec'=>$this->request->getVar('date_nec'),
            'lieu_nec'=>$this->request->getVar('lieu_nec'),
            'lieu_nec_arabe'=>$this->request->getVar('lieu_nec_arabe'),
           // 'encadrant_id'=>$this->request->getVar('encadrant_id'),
           // 'co_encadrant_id'=>$co_encadrant_id,
            'dateDecretDoctorat'=>$this->request->getVar('dateDecretDoctorat'),
            'NumeroDecretDoctorat'=>$this->request->getVar('NumeroDecretDoctorat'),
            'Cotutelle'=>$this->request->getVar('chCotutelle'),
        ]; 
        if($session->get('role')>=1)
            $dataDoc['DatePremiereInscription']=$DatePremiereInscription;
        $id_doctorant=$this->request->getVar('id');
        if($session->get('role')>=3){
            $dataDoc['sujet']=$this->request->getVar('sujet');
            $dataDoc['encadrant_id']=$this->request->getVar('encadrant_id');
            $dataDoc['co_encadrant_id']=$co_encadrant_id;
        }
        if($doctorantModel->update($id_doctorant,$dataDoc )==true){
           $session->setFlashdata('msg', 'Modification effectuée avec succes');
           log_message('info',' Updating Doctorant id '.$id_doctorant.' User '.$session->get('email'));
        }
        else $session->setFlashdata('msg', 'Erreur, Modification non valide!');           
        if ($session->get('role')==1)
         return $this->response->redirect(site_url('/dept-admin/'.$session->get('departement_id')));      
        else if (($session->get('role')==3))
        return $this->response->redirect(site_url('/vrpg-admin/-1/-1/2/-1'));
        else if ($session->get('role')==4)
        return $this->response->redirect(site_url('/vrpg-admin/-1/-1/2/-1'));
        else if ($session->get('role')==2)
        return  $this->response->redirect(site_url('/fac-admin/-1/2/-1'));
     }
    }
      
     // Save the student  data.
    public function store(){
        $session = session();
        if($session->get('role')==3){
        $doctorantModel   = new DoctorantModel();
        $userModel = new UserModel();
        $inscriptionModel=new InscriptionModel();

        $str_domaine=$this->request->getVar('domaine');
        $str_domaine_ar=$this->request->getVar('domaine_ar');
       
        $str_filiere=$this->request->getVar('filiere');
        $str_filiere_ar=$this->request->getVar('filiere_ar');
        
        $str_specialite=$this->request->getVar('specialite');
        $str_specialite_ar=$this->request->getVar('specialite_ar');

        $db      = \Config\Database::connect();
        $builder = $db->table('user');
        $data = [
            'email'  => $this->request->getVar('email'),
            'pwd' => password_hash('test',PASSWORD_DEFAULT),
            'role' => 0,
            'departement_id'=>$session->get('departement_id'),
            'faculty_id'=>$session->get('faculty_id'),
        ];
        $builder->insert($data);
        $user_id= $db->insertID();
        
        $id_domaine=$this->request->getVar('domaine');
        $domaineModel= new DomaineModel();
        $domaine= $domaineModel->where('id',$id_domaine)->first();

        $id_filiere=$this->request->getVar('filiere');
        $filiereModel= new FiliereModel();
        $filiere= $filiereModel->where('id',$id_filiere)->first();

        $id_specialite=$this->request->getVar('specialite');
        $specialiteModel= new SpecialiteModel();
        $specialite= $specialiteModel->where('id',$id_specialite)->first();

        $co_encadrant_id=($this->request->getVar('co_encadrant_id')==-1)?null:$this->request->getVar('co_encadrant_id');

        $nom_ar=$this->request->getVar('nom_ar');
            $nom_ar=str_replace("الله"
            ,"اللـه",
            $nom_ar);
            $prenom_ar=$this->request->getVar('prenom_ar');
            $prenom_ar=str_replace("الله"
            ,"اللـه",
            $prenom_ar);

        $dataDoc=[
            'nom_fr'=>$this->request->getVar('nom_fr'),
            'prenom_fr'=>$this->request->getVar('prenom_fr'),
            'date_nec'=>$this->request->getVar('date_nec'),
            'lieu_nec'=>$this->request->getVar('lieu_nec'),
            'nom_ar'=>$nom_ar,
            'prenom_ar'=>$prenom_ar,
            'sujet'=>$this->request->getVar('sujet'),
            'sexe'=>$this->request->getVar('sexe'),
            'situation'=>0,//when created the situation is "nouveau inscrit".
            'dept_id'=>$session->get('departement_id'),
            'fac_id'=>$session->get('faculty_id'),
            'user_id'=>$user_id,
            'type'=>$this->request->getVar('type'),
            'filiere'=>$str_filiere,//['nom_fr'],
            'specialite'=>$str_specialite,//['nom_fr'],
            'domaine'=>$str_domaine,//['domaine_fr'],
            'filiere_ar'=>$str_filiere_ar,//['nom_ar'],
            'specialite_ar'=>$str_specialite_ar,//['nom_ar'],
            'domaine_ar'=>$str_domaine_ar,//['domaine_ar'],
            'date_nec'=>$this->request->getVar('date_nec'),
            'lieu_nec'=>$this->request->getVar('lieu_nec'),
            'lieu_nec_arabe'=>$this->request->getVar('lieu_nec_arabe'),
            'encadrant_id'=>$this->request->getVar('encadrant_id'),
            'co_encadrant_id'=>$co_encadrant_id,
            'DatePremiereInscription'=>$this->request->getVar('DatePremiereInscription'),
            'NumeroDecretDoctorat'=>$this->request->getVar('NumeroDecretDoctorat'),
            'dateDecretDoctorat'=>$this->request->getVar('dateDecretDoctorat'),
            
        ];
        $builder = $db->table('Doctorant');
        $builder->insert($dataDoc);
        $doctorant_id= $db->insertID();

        $dataInscription=[
            'date'=>date('Y-m-d'),
            'avancementTXT'=>"0",
            'avancementTAUX'=>0,
            'approuver'=>0,
            'Inscription_date'=>date('Y-m-d'),
            'Doctorant_id'=>$doctorant_id,
        ];
        $inscriptionModel->insert($dataInscription);
        log_message('info',' Inscription Doctorant id '.$doctorant_id);
        return $this->response->redirect(site_url('/dept-admin/'.$session->get('departement_id')));      
     }  else echo "<b>Access Denied</b>";
    } 

    
    public function createDemandeChangementEncadrant($id_doctorant){
        $session = session();
        if($session->get('role')==1){           
        $docModel= new DoctorantModel();
        $doctorant=$docModel->where('id', $id_doctorant)->first();
        $EnseignantModel = new EnseignantModel();

        $data['encadreur']=$EnseignantModel->where('id',$doctorant['encadrant_id'])->first();
        $data['coencadreur']=$EnseignantModel->where('id',$doctorant['co_encadrant_id'])->first();
		$data['enseignant'] = $EnseignantModel->orderBy('nom_ar', 'ASC')->findAll();
        $data['doctorant']=$doctorant;
        return view('dept_form_demandeChangementEncadrant',$data);
        } else echo "<b>Access Denied</b>";
    }

    public function SaveDemandeChangementEncadrant(){
        $session = session();
        if($session->get('role')==1){
        $dcsmodel= new demande_changement_Encadrant();
        $dataDCS=[
            'doctorant_id'=>$this->request->getVar('id_doctorant'), 
            'nouveau_encadrant_id'=>$this->request->getVar('nouveauEncadrant'),
            'approuver'=>0,
            'date_csd'=>$this->request->getVar('date_csd'),
            'ancien_encadrant_id'=>$this->request->getVar('ancien_encadrant_id'),
            'causeChangement'=>$this->request->getVar('causeChangement'),];
        $CDS_id=$dcsmodel->insert($dataDCS);
        $departement_id=$session->get('departement_id');
        return redirect()->to('/dept-ListerDemandeChangementByDeptID/'.$departement_id);
        } else echo "Access Denied";
    }

     /**
     * Load edit form to update a demande de changement d'encadrant
     */
    public function demandeChangementEncadrantLoadForUpdate($id_dcs){
        $session = session();
        if($session->get('role')==1){           
        $dcsmodel= new demande_changement_Encadrant();
        $currentDCS=$dcsmodel->where('id', $id_dcs)->first();
        $docModel= new DoctorantModel();
        $doctorant=$docModel->where('id', $currentDCS['doctorant_id'])->first();
        $EnseignantModel = new EnseignantModel();

        $data['encadreur']=$EnseignantModel->where('id',$doctorant['encadrant_id'])->first();
        $data['coencadreur']=$EnseignantModel->where('id',$doctorant['co_encadrant_id'])->first();
		$data['enseignant'] = $EnseignantModel->orderBy('nom_ar', 'ASC')->findAll();
       
        $data['doctorant']=$doctorant;
        $data['dcs']=$currentDCS;
        return view('dept_edit_demande_changement_Encadrant',$data);
        } else echo "<b>Access Denied</b>";
    }
/**
 * Update du demande de changement d'encadrant.
 */
    public function demandeChangementEncadrant_update(){
        $session = session();
        if($session->get('role')==1){  
            $dcsmodel= new demande_changement_Encadrant();
            $data = [
                'nouveau_encadrant_id' => $this->request->getVar('nouveauEncadrant'),
                'date_csd'=>$this->request->getVar('date_csd'),
                'causeChangement'=>$this->request->getVar('causeChangement'),
            ];
            $dcsmodel->update($this->request->getVar('id'), $data);
            return redirect()->to('/dept-ListerDemandeChangementByDeptID/'.$session->get('departement_id'));
        }
    }

/** 
 * remplir le formulaire de demande de changement de sujet.
 */
    public function createDemandeChangementSujet($id_doctorant){
        $session = session();
        if($session->get('role')==1){           
        $docModel= new DoctorantModel();
        $doctorant=$docModel->where('id', $id_doctorant)->first();
        $EnseignantModel = new EnseignantModel();

        $data['encadreur']=$EnseignantModel->where('id',$doctorant['encadrant_id'])->first();
        $data['coencadreur']=$EnseignantModel->where('id',$doctorant['co_encadrant_id'])->first();
		$data['enseignant'] = $EnseignantModel->orderBy('nom_ar', 'ASC')->findAll();
        $data['doctorant']=$doctorant;
        return view('dept_form_demandeChangementSujet',$data);
        } else echo "<b>Access Denied</b>";
    }

    /**
     * Sauvegarder la demande de changement de sujet.
     */
    public function SaveDemandeChangementSujet(){
        $session = session();
        if($session->get('role')==1){
        $dcsmodel= new demande_changement_sujetModel();
        $dataDCS=[
            'id_doctorant'=>$this->request->getVar('id_doctorant'), 
            'nouveau_sujet'=>$this->request->getVar('nouveau_sujet'),
            'approuver'=>0,
            'date_csd'=>$this->request->getVar('date_csd'),
            'ancient_sujet'=>$this->request->getVar('ancient_sujet')];
            $CDS_id=$dcsmodel->insert($dataDCS);
        $departement_id=$session->get('departement_id');
        return redirect()->to('/dept-ListerDemandeChangementByDeptID/'.$departement_id);
        } else echo "Access Denied";
    }

    /**
     * Load edit form to update a demande de changement de sujet
     */
    public function demandeChangementSujetLoadForUpdate($id_dcs){
        $session = session();
        if($session->get('role')==1){           
        $dcsmodel= new demande_changement_sujetModel();
        $currentDCS=$dcsmodel->where('id', $id_dcs)->first();
        $docModel= new DoctorantModel();
        $doctorant=$docModel->where('id', $currentDCS['id_doctorant'])->first();
        $EnseignantModel = new EnseignantModel();

        $data['encadreur']=$EnseignantModel->where('id',$doctorant['encadrant_id'])->first();
        $data['coencadreur']=$EnseignantModel->where('id',$doctorant['co_encadrant_id'])->first();
		$data['enseignant'] = $EnseignantModel->orderBy('nom_ar', 'ASC')->findAll();
        $data['doctorant']=$doctorant;
        $data['dcs']=$currentDCS;
        return view('dept_edit_demande_changement_sujet',$data);
        } else echo "<b>Access Denied</b>";
    }

    public function demandeChangementSujet_update(){
        $session = session();
        if($session->get('role')==1){  
            $dcsmodel= new demande_changement_sujetModel();
            $data = [
                'nouveau_sujet' => $this->request->getVar('nouveau_sujet'),
                'date_csd'=>$this->request->getVar('date_csd'),
                'causeChangement'=>$this->request->getVar('causeChangement'),
            ];
            $dcsmodel->update($this->request->getVar('id_dcs'), $data);
            return redirect()->to('/dept-ListerDemandeChangementByDeptID/'.$session->get('departement_id'));
        }
    }

    /**
     * supprimer la demande de changement de sujet
     */

     public function deleteDemandeDeChangementSujet($id){
        $session = session();
        if($session->get('role')==1){  
            $dcsmodel= new demande_changement_sujetModel();
            $dcsmodel->where('id', $id)->delete($id);
            return redirect()->to('/dept-ListerDemandeChangementByDeptID/'.$session->get('departement_id'));
        }
         else echo "Access Denied";
     }
  /**
     * supprimer la demande de changement d'Encadrant
     */

    public function deleteDemandeDeChangementEncadrant($id){
        $session = session();
        if($session->get('role')==1){  
            $dcsmodel= new demande_changement_Encadrant();
            $dcsmodel->where('id', $id)->delete($id);
            return redirect()->to('/dept-ListerDemandeChangementByDeptID/'.$session->get('departement_id'));
        }
         else echo "Access Denied";
     }

     public function ApprouveDemandeDeChangementSujet($id){
        $session = session();
        if($session->get('role')>=1){  
            $dcsmodel= new demande_changement_sujetModel();
            $currentDCS=$dcsmodel->where('id', $id)->first();
            $approuveValue=$session->get('role')+1;
            $saveVal=($currentDCS['approuver']==0)? $approuveValue:0;
            
            $data = [
                'approuver' => $saveVal
            ];
            $dcsmodel->update($id, $data);
            if($session->get('role')==1)
            return redirect()->to('/dept-ListerDemandeChangementByDeptID/'.$session->get('departement_id'));
            else if($session->get('role')==2)                 
            return redirect()->to('fac-ListChangementSujetByFacultyID/'.session()->get('faculty_id')); 
            else if($session->get('role')==3)                 
            return redirect()->to('fac-ListChangementSujetByFacultyID/-1');                                            
        }
     }

     public function ApprouveDemandeDeChangementEncadrant($id){
        $session = session();
        if($session->get('role')>=1){  
            $dcsmodel= new demande_changement_Encadrant();
            $currentDCS=$dcsmodel->where('id', $id)->first();
            $approuveValue=$session->get('role')+1;
            $saveVal=($currentDCS['approuver']==0)?$approuveValue:0;
            $data = [
                'approuver' => $saveVal
            ];
            $dcsmodel->update($id, $data);
            if($session->get('role')==1)
                return redirect()->to('/dept-ListerDemandeChangementByDeptID/'.$session->get('departement_id'));
            else if($session->get('role')==2)                 
                return redirect()->to('fac-ListChangementSujetByFacultyID/'.session()->get('faculty_id'));                
                else if($session->get('role')==3)                 
                return redirect()->to('fac-ListChangementSujetByFacultyID/-1');                                
        }
     }

     public function AppliquerChangementEncadrant($id){
        $session = session();
        if($session->get('role')==2){  
            $dcsmodel= new demande_changement_Encadrant();
            $currentDCS=$dcsmodel->where('id', $id)->first();

            $data = [
                'approuver' => 4
            ];
            $dcsmodel->update($id, $data);

            $id_newEncadrant=$currentDCS['nouveau_encadrant_id'];
            $data = [
                'id'=>$currentDCS['doctorant_id'],
                'encadrant_id' => $id_newEncadrant
            ];
            $doctorantModel=new DoctorantModel();
            $doctorantModel->save($data);             
                return redirect()->to('fac-ListChangementSujetByFacultyID/'.session()->get('faculty_id'));                                
        }
     }

     /**
      * Appliquer le changement de sujet sur la base.
      */
     public function AppliquerChangementSujet($id){
        $session = session();
        if($session->get('role')==2){  
            $dcsmodel= new demande_changement_sujetModel();
            $currentDCS=$dcsmodel->where('id', $id)->first();

            $saveVal=5;
            $data = [
                'approuver' => 4
            ];
            $dcsmodel->update($id, $data);

            $new_sujet=$currentDCS['nouveau_sujet'];
            $data = [
                'id'=>$currentDCS['id_doctorant'],
                'sujet' => $new_sujet
            ];
            $doctorantModel=new DoctorantModel();
            $doctorantModel->save($data);             
                return redirect()->to('fac-ListChangementSujetByFacultyID/'.session()->get('faculty_id'));                                
        }
     }

    /**
     * Lister les demandes de changements par département
     */

     public function ListChangementSujetByDepartementID($departement_id){
        $session = session();
        if($session->get('role')==1){           
        $db      = \Config\Database::connect();
        $builder = $db->table('demande_changement_sujet');
        $builder->select('
        Concat(Concat(Doctorant.nom_ar," "),Doctorant.prenom_ar) as nom,
            demande_changement_sujet.id, 
            demande_changement_sujet.approuver,
        demande_changement_sujet.date_csd, 
        0 as type');

        $builder->join('Doctorant', 'Doctorant.id=demande_changement_sujet.id_doctorant');
        $builder->join('departement','Doctorant.dept_id=departement.id');
        $builder->where('departement.id',$departement_id);
        $builder->orderBy('nom','ASC');
        $query = $builder->get();
        $demandesList=$query->getResultArray();
        // Lister les demandes de changements d'encadrant.
        $builder = $db->table('demande_changement_encadrant');
        $builder->select('
        Concat(Concat(Doctorant.nom_ar," "),Doctorant.prenom_ar) as nom,
            demande_changement_encadrant.id, 
            demande_changement_encadrant.approuver,
            demande_changement_encadrant.date_csd, 
            1 as type');

        $builder->join('Doctorant', 'Doctorant.id=demande_changement_encadrant.doctorant_id');
        $builder->join('departement','Doctorant.dept_id=departement.id');
        $builder->where('departement.id',$departement_id);
        $builder->orderBy('nom','ASC');
        $query = $builder->get();
        $CE_demandesList=$query->getResultArray();    

        $data['demandesList']=array_merge($demandesList,$CE_demandesList);
       // $data['CE_demandesList']=$CE_demandesList;
        return view('dept_demande_changement_list',$data);
        }
        else echo "Access Denied";

     }
       /**
     * Lister les demandes de changements par faculté
     */

    public function ListChangementSujetByFacultyID($faculty_id){
        $session = session();
        if($session->get('role')>=2){           
        $db      = \Config\Database::connect();
        $builder = $db->table('demande_changement_sujet');
        $builder->select('
            Concat(Concat(Doctorant.nom_ar," "),Doctorant.prenom_ar) as nom,
                demande_changement_sujet.id, 
                demande_changement_sujet.approuver,
                demande_changement_sujet.date_csd,
                departement.nom as nom_departement,
                0 as type');

        $builder->join('Doctorant', 'Doctorant.id=demande_changement_sujet.id_doctorant');
        $builder->join('faculty','Doctorant.fac_id=faculty.id');
        $builder->join('departement','Doctorant.dept_id=departement.id','left');
        if($faculty_id!=-1)
            $builder->where('faculty.id',$faculty_id);
        $builder->orderBy('nom','ASC');
        $query = $builder->get();
        $demandesList=$query->getResultArray();

        $builder = $db->table('demande_changement_encadrant');
        $builder->select('
            Concat(Concat(Doctorant.nom_ar," "),Doctorant.prenom_ar) as nom,
            demande_changement_encadrant.id, 
            demande_changement_encadrant.approuver,
            demande_changement_encadrant.date_csd,
                departement.nom as nom_departement,
                1 as type');

        $builder->join('Doctorant', 'Doctorant.id=demande_changement_encadrant.doctorant_id');
        $builder->join('faculty','Doctorant.fac_id=faculty.id');
        $builder->join('departement','Doctorant.dept_id=departement.id','left');
        if($faculty_id!=-1)
            $builder->where('faculty.id',$faculty_id);
        $builder->orderBy('nom','ASC');
        $query = $builder->get();
        $CE_demandesList=$query->getResultArray();

        $data['demandesList']=array_merge($demandesList,$CE_demandesList);
        if($session->get('role')==2)
        return view('fac-demandeDeChangementSujet_list',$data);      
        else if ($session->get('role')==3)
        return view('vrpg-DemandeChangementList',$data);      
        }
        else echo "Access Denied";

     }

     /**
      * Change the status of the student record when his/her phyics documents are OK.
      */
     public function vrpgStudentDossierCompletAJAX($id){
        $session = session();
        if(($id!=null) && ($session->get('role')>=3)){
            $doctorantModel = new DoctorantModel();
            $data = [
                'id'=>$id,
                'dossierPapierValide'=> 1
            ];
            $doctorantModel->save($data);
            echo json_encode("OK");
        }
        else echo "Error";          
     }

     /**
      * Change the status of the student stat to 'sans dossier classé par l'agent'.
      */
      public function vrpgStudentDossierSansDossierClasseAJAX($id){
        $session = session();
        if(($id!=null) && ($session->get('role')>=3)){
            $doctorantModel = new DoctorantModel();
            $data = [
                'id'=>$id,
                'dossierPapierValide'=> 3
            ];
            $doctorantModel->save($data);
            echo json_encode("OK");
        }
        else echo "Error";          
     }

     /**
      * Change the status of the student record when his/her phyics documents are not OK.
      */
      public function vrpgStudentDossierInCompletAJAX(){
        $session = session();
        $id=$this->request->getVar('id_doctorant');
        if(($id!=null) && ($session->get('role')>=3)){
            $doctorantModel = new DoctorantModel();
            $data = [
                'id'=>$this->request->getVar('id_doctorant'),
                'dossierPapierValide'=> -1,
                'reserveDossierPapier'=>$this->request->getVar('reserveDossierPapier')
            ];
            $doctorantModel->save($data);
            echo json_encode("OK");
        }
        else echo "Error";          
     }

     /**
      * Change the status of the student (Soutenu classé par l'agent).
      */
      public function vrpgStudentDossierSoutenuClasseAJAX(){
        $session = session();
        $id=$this->request->getVar('id_doctorant');
        if(($id!=null) && ($session->get('role')>=3)){
            $doctorantModel = new DoctorantModel();
            $data = [
                'id'=>$this->request->getVar('id_doctorant'),
                'dossierPapierValide'=> 2,
                'DateDoutenanceRemplitAgent'=>$this->request->getVar('dateSoutenance')
            ];
            $doctorantModel->save($data);
            echo json_encode("OK");
        }
        else echo "Error";          
     }
     
 }