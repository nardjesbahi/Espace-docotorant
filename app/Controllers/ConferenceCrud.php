<?php
namespace App\Controllers;

class ConferenceCrud extends BaseController 
{

    public function index()
    {
        $data['conferences'] = array(
            array("Title" => "Conference", "Module" => "Cyber securite", "Date" => "12/04/2023", "Heure" => "11:00", "Lieu" => "Amphi Guellati."),
            array("Title" => "mm", "Module" => "Machine learning", "Date" => "15/04/2023", "Heure" => "14:00", "Lieu" => "Amphi Ben Aknoun."),
            array("Title" => "Hello", "Module" => "Blockchain", "Date" => "18/04/2023", "Heure" => "16:00", "Lieu" => "Amphi Ferhat Abbas."),
        );

        $data['announcements'] = array(
            array("Title" => "New Announcement", "Content" => "This is a new announcement."),
            array("Title" => "Another Announcement", "Content" => "This is another announcement."),
        );

        $this->load->view('', $data);
    }

}