<?php

namespace App\Controllers;

use App\Models\DepartementModel;
use App\Models\DoctorantModel;
use App\Models\DossiersoutenanceModel;
use App\Models\EnseignantModel;
use App\Models\FacultyModel;

class Home extends BaseController
{
    public function index()
    {
       return redirect()->to('/login');
    }

    public function accueil()
    {
       return view('accueil');
    }

    public function information()
    {
        $session=session();
        $id_doc= $session->get('doctorant_id');
        $docModel=new DoctorantModel();
        $doctorant= $docModel->where("id",$id_doc)->first();
       
        $id_encadrant= $doctorant["encadrant_id"];
        $ensModel=new EnseignantModel();
        $encadrant= $ensModel->where("id",$id_encadrant)->first();

        $id_coencadrant= $doctorant["co_encadrant_id"];
        $ensModel=new EnseignantModel();
        $coencadrant= $ensModel->where("id",$id_coencadrant)->first();


       

        $data['doctorant']=$doctorant;
        $data['encadrant']=$encadrant;
        $data['coencadrant']=$coencadrant;
    
    
       return view('informations_view',$data);
    }
    
   /* public function soutenance()
    
*/


public function soutenance()
    {
      $session=session();
        $id_doc= $session->get('doctorant_id');
        $docModel=new DoctorantModel();
        $doctorant= $docModel->where("id",$id_doc)->first();

    
      $dossierModel=new DossiersoutenanceModel();
      $dossier= $dossierModel->where("Doctorant_id",$id_doc)->first();

      $id_enseignat= $dossier["membre_1_id"];
      $ensModel=new EnseignantModel();
      $membre= $ensModel->where("id",$id_enseignat)->first();

      $id_enseignant= $dossier["membre_2_id"];
      $ensModel=new EnseignantModel();
      $membre2= $ensModel->where("id",$id_enseignant)->first();

      $id_ens= $dossier["membre_3_id"];
      $ensModel=new EnseignantModel();
      $membre3= $ensModel->where("id",$id_ens)->first();

     
      

      $id_president= $dossier["president_jury_id"];
      $ensModel=new EnseignantModel();
      $president= $ensModel->where("id",$id_president)->first();

      $id_encadrant= $doctorant["encadrant_id"];
      $ensModel=new EnseignantModel();
      $encadrant= $ensModel->where("id",$id_encadrant)->first();

      $id_coencadrant= $doctorant["co_encadrant_id"];
      $ensModel=new EnseignantModel();
      $coencadrant= $ensModel->where("id",$id_coencadrant)->first();

     
      $data['doctorant']=$doctorant;
      $data['dossier']=$dossier;
      $data['membre']=$membre;
      $data['membre2']=$membre2;
      $data['membre3']=$membre3;
      
     
      $data['encadrant']=$encadrant;
      $data['coencadrant']=$coencadrant;
      $data['president']=$president;

       return view('soutenance',$data);
      //return view('soutenance');
    }
    public function demande(){
      $session=session();
      $id_doc= $session->get('doctorant_id');
      $docModel=new DoctorantModel();
      $doctorant= $docModel->where("id",$id_doc)->first();

      $dossierModel=new DossiersoutenanceModel();
      $dossier= $dossierModel->where("Doctorant_id",$id_doc)->first();

      $num_etape= $dossier["Etape"];
      $ensModel=new DossiersoutenanceModel();
      $Etape= $ensModel->where("Etape",$num_etape)->first();

     

      $data['doctorant']=$doctorant;
      $data['dossier']=$dossier;
      $data['Etape']=$Etape;
      return view('demande',$data);
    }
    public function inscription(){
      $session=session();
      $id_doc= $session->get('doctorant_id');
      $docModel=new DoctorantModel();
      $doctorant= $docModel->where("id",$id_doc)->first();
     
      $data['doctorant']=$doctorant;
      return view('inscription',$data);
    }
}
