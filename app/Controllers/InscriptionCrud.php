<?php 
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\FacultyModel;
use App\Models\InscriptionModel;
use App\Models\DepartementModel;
use App\Models\DoctorantModel;
use App\Models\EnseignantModel;
use App\Models\LaboratoireModel;
use App\Models\DossierSoutenanceModel;
class InscriptionCrud extends Controller
{
    /**
     * ADMIN
     * Lister les utilisateurs du site.
     */
    public function ListerInscriptionByDoctorantID($DoctorantID=null){
        $session = session();
        if($session->get('role')!=0){
        $db      = \Config\Database::connect();
        $builder = $db->table('inscription');
        $builder->select('inscription.id as id, 
        Doctorant.nom_fr as nom_fr,
        Doctorant.prenom_fr as prenom_fr, 
        CONCAT(Concat(enseignant.nom_ar," "), enseignant.prenom_ar) as encadrant_nom, 
        departement.nom as dept, 
        inscription.Inscription_date as date, 
        CONCAT(CONCAT(coencadrant.nom_ar," "),coencadrant.prenom_ar) as coencadrantNom ');
        $builder->join('Doctorant', 'Doctorant.id=inscription.Doctorant_id');
        $builder->join('enseignant','enseignant.id=Doctorant.encadrant_id');
        $builder->join(
            'enseignant as coencadrant',
            'coencadrant.id=Doctorant.co_encadrant_id','LEFT');
        $builder->join('departement','departement.id=Doctorant.dept_id');
        $builder->where('Doctorant.id',$DoctorantID);
        $query = $builder->get();
        $data["inscriptions"]=$query->getResultArray();
        
        if($session->get('role')==3)return view('vrpg-inscription-list', $data);
        else if($session->get('role')==2) return view('fac-inscription-list', $data);
        else if($session->get('role')==1) return view('dept-inscription-list', $data);
     }
     else echo "Access Denied";
    }

    public function ListerInscriptionByDepartementID($departement_id=null){
        $session = session();
        if(($session->get('role')!=0)&&($session->get('departement_id')==$departement_id))
        {
        $db      = \Config\Database::connect();
        $builder = $db->table('inscription');
        $builder->select('
        inscription.id as id, 
        Doctorant.nom_fr as nom_fr,
        Doctorant.prenom_fr as prenom_fr,
        CONCAT(CONCAT(enseignant.nom_ar," "),enseignant.prenom_ar) as encadrant_nom, 
        CONCAT(CONCAT(enseignant.nom_fr," "),enseignant.prenom_fr) as encadrant_nom_fr, 
        departement.nom as dept, 
        inscription.Inscription_date as date, 
        CONCAT(CONCAT(coencadrant.nom_ar," "),coencadrant.prenom_ar) as coencadrantNom,
        inscription.approuver');
        $builder->join('Doctorant', 'Doctorant.id=inscription.Doctorant_id');
        $builder->join('enseignant','enseignant.id=Doctorant.encadrant_id');
        $builder->join(
            'enseignant as coencadrant',
            'coencadrant.id=Doctorant.co_encadrant_id','LEFT');
        $builder->join('departement','departement.id=Doctorant.dept_id');
        $builder->where('departement.id',$departement_id);
        //list only not approuved inscription.
        $builder->where('inscription.approuver',0);
        $builder->where('planTXT is not null');
        
        $query = $builder->get();
        $user=$query->getResultArray();
        $data["inscriptions"]=$user;
        return view('dept_approuve_inscription', $data);
     }
     else echo "Access Denied";
    }

    
    public function ListerInscriptionByFacultyID($faculty_id=null){
        $session = session();
        if(($session->get('role')!=0)&&($session->get('faculty_id')==$faculty_id)){
        $db      = \Config\Database::connect();
        $builder = $db->table('inscription');
        $builder->select('
        inscription.id as id, 
        Doctorant.nom_fr as nom_fr,
        Doctorant.prenom_fr as prenom_fr, 
        CONCAT(CONCAT(enseignant.nom_ar," "),enseignant.prenom_ar) as encadrant_nom, 
        CONCAT(CONCAT(enseignant.nom_fr," "),enseignant.prenom_fr) as encadrant_nom_fr, 
        departement.nom as dept, 
        inscription.date as date, 
        CONCAT(CONCAT(coencadrant.nom_ar," "),coencadrant.prenom_ar) as coencadrantNom,
         inscription.approuver');
        $builder->join('Doctorant', 'Doctorant.id=inscription.Doctorant_id');
        $builder->join('enseignant','enseignant.id=Doctorant.encadrant_id');
        $builder->join('enseignant as coencadrant','coencadrant.id=Doctorant.co_encadrant_id','LEFT');
        $builder->join('departement','departement.id=Doctorant.dept_id');
        $builder->join('faculty','faculty.id=departement.faculty_id');
        $builder->where('faculty.id',$faculty_id);
        //list only aprouved by department.
        $builder->where('inscription.approuver',1);
        $query = $builder->get();
        $user=$query->getResultArray();
        $data["inscriptions"]=$user;
        return view('fac_approuve_inscription', $data);
     }
     else echo "Access Denied";
    }

    // List ALL  Inscription for VRPG. 
        
    public function ListerInscription(){
        $session = session();
        if($session->get('role')==3){
        $db      = \Config\Database::connect();

        $builder = $db->table('inscription');
        $builder->select('
        inscription.id as id, 
        Doctorant.nom_fr as nom_fr,
        Doctorant.prenom_fr as prenom_fr, 
        CONCAT(CONCAT(enseignant.nom_ar," "),enseignant.prenom_ar) as encadrant_nom, 
        CONCAT(CONCAT(enseignant.nom_fr," "),enseignant.prenom_fr) as encadrant_nom_fr, 
        departement.nom as dept, 
        inscription.date as date, 
        CONCAT(CONCAT(coencadrant.nom_ar," "),coencadrant.prenom_ar) as coencadrantNom,
         inscription.approuver');
        $builder->join('Doctorant', 'Doctorant.id=inscription.Doctorant_id');
        $builder->join('enseignant','enseignant.id=Doctorant.encadrant_id');
        $builder->join('enseignant as coencadrant','coencadrant.id=Doctorant.co_encadrant_id','LEFT');
        $builder->join('departement','departement.id=Doctorant.dept_id');
        $builder->join('faculty','faculty.id=departement.faculty_id');
        $builder->where('inscription.approuver',2);
        $query = $builder->get();
        $user=$query->getResultArray();
        $data["inscriptions"]=$user;
        return view('vrpg_approuve_inscription', $data);
     }
     else echo "Access Denied";
    }



    /**
     * Get the token from the DB by using the id_doctorant.
     */
    public static function getToeknByID($id_doctorant){
        return "d45539ab0cc4a92c38f228599eabd9fb105dc0a8";
    }
    // generate an inscription form
    public function createInscripton($id_doctorant=null,$token){
        $session = session();
        $doctorantModel   = new DoctorantModel();
        $doctorant=$doctorantModel->where('id', $id_doctorant)->first();  
        if($doctorant!=false)
            $token_from_db=$doctorant['token'];
        else $token_from_db="";
        if($token==$token_from_db)
        {
            // only student can insert inscription.
            $doctorantModel   = new DoctorantModel();
            $doctorant=$doctorantModel->where('id', $id_doctorant)->first();    
            if(isset($doctorant)){
                $data['token']=$token;
                $data['InscriptionPourSoutenance']=0;
                $data['doctorant']=$doctorant;   
                $EnseignantModel=new EnseignantModel();
                $data['encadreur']=$EnseignantModel->where('id',$doctorant['encadrant_id'])->first();
                $data['co_encadreur']=$EnseignantModel->where('id',$doctorant['co_encadrant_id'])->first();
                $data['AnneeSesssionInscription']=Date("Y");
                $departementModel=new DepartementModel();
                $departement=$departementModel->find($doctorant['dept_id']);
                $data['departement']=$departement;
                $facModel= new FacultyModel();
                $faculty=$facModel->find($doctorant['fac_id']);
                $data['faculty']=$faculty;
                //Lister les laboratoires.
                $laboratoireModel = new LaboratoireModel();
				$laboratoireData = $laboratoireModel->orderBy('nom_fr', 'ASC')->findAll();
                $data['laboratoires']=$laboratoireData;
                //Vérifie si il va inserer ou update une inscription.
                $inscriptionModel=new InscriptionModel();
                $retInscription=$inscriptionModel->where('Doctorant_id',$doctorant['id'])
                ->where("Inscription_date",Date("Y"))->first();
                $data['Inscription']=$retInscription;
                $type=$doctorant['type'];
                $month=date('m');
                $todayYear=($month>=1 && $month<=8)? date('Y')-1:date('Y');
                $datePremiereInscription=$doctorant['DatePremiereInscription'];
                //3ème cycle.
                if($type==1){
                    if(($todayYear-$datePremiereInscription+1)>5) 
                    $data['retard']=($todayYear-$datePremiereInscription+1);
                }
                //Sciences.
                if($type==0){
                    if(($todayYear-$datePremiereInscription+1)>6) 
                     $data['retard']=($todayYear-$datePremiereInscription+1);
                }
                return view('student_inscription_form',$data);
            }
            else echo "Access Denied";
        } else echo "Access Denied";
    }

    // generate an inscription form
    public function createInscriptonForSoutenance($id_dossier){
        $session = session();
        $ds=new DossierSoutenanceModel();
        $dossiers = $ds->where('id', $id_dossier)->first();
        $doctorantModel   = new DoctorantModel();
        $doctorant=$doctorantModel->where('id', $dossiers['Doctorant_id'] )->first();   
        if($session->get('role')>0)
        {
            $data['token']="NoTokenNeeded";
            $data['InscriptionPourSoutenance']=1;  
                $data['doctorant']=$doctorant;   
                $EnseignantModel=new EnseignantModel();
                $data['encadreur']=$EnseignantModel->where('id',$doctorant['encadrant_id'])->first();
                $data['co_encadreur']=$EnseignantModel->where('id',$doctorant['co_encadrant_id'])->first();
                $data['AnneeSesssionInscription']=Date("Y");
                $departementModel=new DepartementModel();
                $departement=$departementModel->find($doctorant['dept_id']);
                $data['departement']=$departement;
                $facModel= new FacultyModel();
                $faculty=$facModel->find($doctorant['fac_id']);
                $data['faculty']=$faculty;
                //Lister les laboratoires.
                $laboratoireModel = new LaboratoireModel();
				$laboratoireData = $laboratoireModel->orderBy('nom_fr', 'ASC')->findAll();
                $data['laboratoires']=$laboratoireData;
                //Vérifie si il va inserer ou update une inscription.
                $inscriptionModel=new InscriptionModel();
                $retInscription=$inscriptionModel->where('Doctorant_id',$doctorant['id'])
                ->where("Inscription_date",Date("Y"))->first();
                $data['Inscription']=$retInscription;
                $type=$doctorant['type'];
                
                $month=date('m');
                $todayYear=($month>=1 && $month<=8)? date('Y')-1:date('Y');
                
                $datePremiereInscription=$doctorant['DatePremiereInscription'];
                //3ème cycle.
                if($type==1){
                    if(($todayYear-$datePremiereInscription+1)>5) 
                    $data['retard']=($todayYear-$datePremiereInscription+1);
                }
                //Sciences. 
                if($type==0){
                    if(($todayYear-$datePremiereInscription+1)>6) 
                     $data['retard']=($todayYear-$datePremiereInscription+1);
                }
                return view('student_inscription_form',$data);
        }
                else echo "Access Denied";

    }
    

    //Add inscription by a student.
    public function saveInscription(){
        helper('Util_helper');
        $session = session();
        $isValid = $this->validate([
            'email' => 'required',
            'avancementTXT'  => 'required',
            'planTXT'  => 'required',
            'telephone'  => 'required',
            'immatriculeBAC'=>'required'
        ]);
        if (!$isValid){
            $session->setFlashdata('msg', 'Les informations ne sont pas valides!');
            return redirect()->to('/student_inscription/'.$this->request->getVar('doctorantID').'/'.$this->request->getVar('token'));
        }
        else {
            $InscriptionPourSoutenance=$this->request->getVar('InscriptionPourSoutenance');
        $inscriptionModel=new InscriptionModel();
            $id_doctorant=$this->request->getVar('doctorantID');
            $doctorantModel   = new DoctorantModel();
            $doctorant=$doctorantModel->where('id', $id_doctorant)->first();    
            $data['doctorant']=$doctorant;   
            $EnseignantModel=new EnseignantModel();
            $data['encadreur']=$EnseignantModel->where('id',$doctorant['encadrant_id'])->first();
            $data['co_encadreur']=$EnseignantModel->where('id',$doctorant['co_encadrant_id'])->first();
            $data['AnneeSesssionInscription']=Date("Y");
            $departementModel=new DepartementModel();
            $departement=$departementModel->find($doctorant['dept_id']);
            $data['departement_obj']=$departement;
            $facModel= new FacultyModel();
            $faculty=$facModel->find($doctorant['fac_id']);
            $data['faculty_obj']=$faculty;
        /**Save the data */
        $inscriptionModel = new InscriptionModel();
        $planTXT=$this->request->getVar('planTXT');
        $planTXT=str_replace("الله"
        ,"اللـه",$planTXT);
        $dataToInsertToDataBase=[
            'Inscription_date' => $this->request->getVar('dateInscription'),
            'date' => date("Y-m-d"),
            'num_quittance' => $this->request->getVar('num_quittance'),
            'avancementTXT'=>$this->request->getVar('avancementTXT'),
            //'bilanTXT'=>$this->request->getVar('bilanTXT'),
            'planTXT'=> $planTXT,
            'Doctorant_id'=>$doctorant['id'],
            'approuver'=>0,
            'datePrevueDeSoutenance'=>$this->request->getVar('datePrevueDeSoutenance'),
        ];
        $retInscription=$inscriptionModel->where('Doctorant_id',$doctorant['id'])->first();

        if($retInscription!=false){
            $id_of_current_inscription=$retInscription['id'];
            $inscriptionModel->update($id_of_current_inscription,
                    $dataToInsertToDataBase);
        } else $inscriptionModel->insert($dataToInsertToDataBase);
        
        $retInscription=$inscriptionModel->where('Doctorant_id',$doctorant['id'])->first();
        $data['inscription']=$retInscription;
        //
        //saving doctorant data.
        $Doctorant_contact_data=[
            'id'=>$doctorant['id'],
            'email'=>$this->request->getVar('email'),
            'telephone'=>$this->request->getVar('telephone'),
            'nomLabo'=>$this->request->getVar('nomLabo'),
            'immatriculeBAC'=>$this->request->getVar('immatriculeBAC'),
        ];
        $doctorantModel->save($Doctorant_contact_data);
        $doctorant=$doctorantModel->where('id', $id_doctorant)->first();    
            $data['doctorant']=$doctorant;   
        // end data saving.
        //updating co-encadrant data.
        /*$encadrantData=[
            'id'=>$doctorant['encadrant_id'],
            'univOrigine_ar'=> $this->request->getVar('UnivOrigine_Encadrant'),
        ];
        $EnseignantModel->save($encadrantData);
        
        if(isset($doctorant['co_encadrant_id'])){
            $co_encadrantData=[
                'id'=>$doctorant['co_encadrant_id'],
                'univOrigine_ar'=> $this->request->getVar('UnivOrigine_COEncadrant'),
            ];
            $EnseignantModel->save($co_encadrantData);
        }*/
        $retard=false;
        $month=date('m');
        $todayYear=($month>=1 && $month<=8)? date('Y')-1:date('Y');

        $returnedView="";
        $datePremiereInscription=$doctorant['DatePremiereInscription'];
        $type=$doctorant['type'];
        //3ème cycle.
        if($type==1){
            if($InscriptionPourSoutenance==1)
              $returnedView="out_ficheInscrPourSoutenance";
            else 
            if(($todayYear-$datePremiereInscription+1)==4) 
                $returnedView="out_derogationLMD";
            else if (($todayYear-$datePremiereInscription+1)==5)
                $returnedView="out_derogationLMD";
            else if(($todayYear-$datePremiereInscription+1)==1)
                $returnedView="out_PremiereInscriptionDocLMD";
            else if(($todayYear-$datePremiereInscription+1)<4) 
                $returnedView="out_reinscriptionDocLMD";
            else $returnedView="out_ficheSuiviLMD";
        }
        //Sciences.
        if($type==0){
            if($InscriptionPourSoutenance==1)
              $returnedView="out_ficheInscrPourSoutenance";
            else if(($todayYear-$datePremiereInscription+1)==6)
                $returnedView="out_derogationSci";
            else if(($todayYear-$datePremiereInscription+1)<6) 
                $returnedView="out_reinscriptionDocSci";
            else $returnedView= "out_ficheSuiviSci";               
        }         
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetDirectionality('rtl');
        $mpdf->SetFont('dejavusans', '', 12);
        $html = view($returnedView,$data);
        $mpdf->WriteHTML($html);
        $this->response->setHeader('Content-Type', 'application/pdf');
        $mpdf->Output('Formulaire_Inscription_Doctorat_'.$doctorant['nom_fr'].'.pdf','I');     
        
        }   
    }
 
    // Approuve an inscription.
    public function approuver($id=null){
        $session = session();
        if($session->get('role')>0){
            $inscriptionModel = new InscriptionModel();
            
            $approuvedValue=$session->get('role');
            $data = [
                'approuver' => $approuvedValue,
            ];
            
            $inscriptionModel->update($id, $data);
            if($approuvedValue==1){
            $depatement_id=$session->get('departement_id');
            return redirect()->to('/dept_approuve_inscription/'.$depatement_id);}
            else if($approuvedValue==2){
                $faculty_id=$session->get('faculty_id');
            return redirect()->to('/fac_approuve_inscription/'.$faculty_id);}
            else if($approuvedValue==3){
            return redirect()->to('/vrpg_approuve_inscription');}            
        }
        else echo "Acess Denied!";
    }
}