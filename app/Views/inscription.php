<html>


<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription-Portail doctorants</title>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>pfetry.css">
    <link rel="icon" href="<?=base_url()?>unniv_logo.png">
      <!--bootstrap css-->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;1,100&family=Roboto:ital,wght@0,100;0,500;1,300&display=swap" rel="stylesheet">
  <script type="text/javascript" src="http://gc.kis.v2.scr.kaspersky-labs.com/FD126C42-EBFA-4E12-B309-BB3FDD723AC1/main.js?attr=7CEf3X_Pgb8JtUz3kWDBeVwIYeRXIlCUtgw6RilMobTdhOoD5GgwcdyfUkoWqjsL-Kfvb2ySAsI2v0zbfRizbw" charset="UTF-8"></script>
</head>

<body> 
    <nav class="navbar navbar-expand-lg " >
        
        <a class="navbar-brand" href="prpage.php">
            <img src="<?=base_url()?>LogoUbma.png" alt="logo" width="50%">
        </a>
        
        <!--<div class="logout">
            <a class="darkmode"><i><ion-icon name="moon-outline"></ion-icon></i></a>
            <a class="deconnexion">Se deconnecter</a>  
        </div>-->
        <div class="logout">
        
        <div class="deconnexion">
          <span>Se deconnecter</span>
        </div>
    </nav>
   <div class="main">
    <div class="lien">
        <a href="https://post-graduation-php.univ-annaba.dz/public/index.php/student_inscription/<?=$doctorant["id"]?>/ <?=$doctorant["token"]?>">
        <button class="lienbtn">Voir lien d'inscription</button>
        </a>
    </div> 
    </div> 
   
    <div class="side-menu">
        <div class="side-nav-content">
        <ul class=" nav-list ">
            <li class="nav-list-item ">
            <a href="<?=site_url()?>/accueil"> <i><ion-icon name="home-outline"></ion-icon></i>
            <span>
                Accueil
            </span></a>
            </li>
            <li class="nav-list-item ">
            <a href="<?=site_url()?>/information"><i><ion-icon name="bookmark-outline"></ion-icon></i>
                <span>
                    Mes informations 
                </span></a>
            </li>
            <li class="nav-list-item ">
            <a href="<?=site_url()?>/demande"><i><ion-icon name="attach-outline"></ion-icon></i>
                <span>
                Suivre mon dossier
                </span></a>
            </li>
            <li class="nav-list-item ">
            <a href="<?=site_url()?>/soutenance"><i><ion-icon name="school-outline"></ion-icon></i>
                <span>
                 Dossier de soutenance 
                </span></a>
            </li>
            
            <li class="nav-list-item active ">
            <a href="<?=site_url()?>/inscription"><i><ion-icon name="link-outline"></ion-icon></i>
                <span>
                    Lien d'inscription 
                </span></a>
            </li>
            <li class="nav-list-item ">
            <a href="<?=site_url()?>/autres"><i><ion-icon name="ellipsis-horizontal-outline"></ion-icon></i>
                <span>
                    Autres
                </span>
                </a>
            </li>
            <hr>
            <div class="bottom-content">
                <!--<li class="nav-list-item">
                    <a href="#">
                        <i><ion-icon name="log-out-outline"></ion-icon></i>
                        <span>Se deconnecter</span>
                    </a>
                </li>-->
                <li class="nav-list-item mode">
                    <div class="moon-sun">
                       <i class="moon"> <ion-icon name="moon-outline"></ion-icon></i>
                        <i class="sun"><ion-icon name="sunny-outline"></ion-icon></i>
                       
                    </div>
                    <span>Mode sombre</span>
                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
            </div>
        </ul>
    </div>
    </div>
    
    <!--<div class="main">
    <div class="lien">
        <button class="lienbtn">Voir lien d'inscription</button>

    </div> 
    </div> -->
    <div class="toggle">
        <ion-icon name="menu-outline" class="open"></ion-icon>
        <ion-icon name="close-outline" class="close"></ion-icon>
     </div>
     
     



















       <!--js link-->
       <script src="<?=base_url()?>script.js"></script>
       <!--bootstrap js-->
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
       <!--ionicons-->
       <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
       <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>