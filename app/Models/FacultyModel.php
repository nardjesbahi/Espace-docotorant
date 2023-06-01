<?php

//CountryModel.php

namespace App\Models;

use CodeIgniter\Model;

class FacultyModel extends Model{

	protected $table = 'faculty';

	protected $primaryKey = 'id';

	protected $allowedFields = ['nom','nom_ar','nom_an'];

}	

?>