<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS only -->
    <link href="<?= base_url() ?>/bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="<?= base_url() ?>/bootstrap-5.1.3-dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="<?= base_url() ?>/bootstrap-icons-1.7.2/bootstrap-icons.css" />
    <title>PG-UBMA</title>
<style>
    .vertical-center {
  min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
  min-height: 100vh; /* These two lines are counted as one :-)       */

  display: flex;
  align-items: center;
}

</style>
</head>

<body>
  <div class="container" >
        <br>
        <br>
        <br> 
        <br>
            <div class="row justify-content-md-center" style="background:white">
                <div class="col col-lg-2">
                
                        <img style="width: 45mm; height: 45mm;"
                            src="<?php echo base_url(); ?>/unniv_logo.png" />
                    
                </div>
                <div class="col-md-auto">
                    <div class="card " style="max-width: 50rem; ">
                        <div class="card-header" style="background:lightsteelblue;"><b>PG-UBMA > Se connecter</b></div>
                        <div class="row">
                            <div class="col">
                                <?php if (session()->getFlashdata('msg')) : ?>
                                    <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
                                <?php endif; ?>
                                <form action=<?= site_url() . "/Login/auth" ?> method="post">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" style="background:lightsteelblue;" id="basic-addon1"><b>E-mail:</b></span>
                                        <input style="background:moccasin" required type="email" name="email" class="form-control" id="InputForEmail" value="<?= set_value('email') ?>">
                                        <div class="invalid-feedback">
                                            email invalide!
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" style="background:lightsteelblue;" id="basic-addon1"><b>Mot de passe:</b></span>
                                        <input style="background:moccasin" required type="password" name="pwd" class="form-control" id="InputForPassword">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Connecter</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    </div>
            </div><br>
            <span class="d-flex justify-content-center" id="basic-addon1"><b>Copyright VRPG, Badji Mokhtar - Annaba University (c) 2022</b></span>
</body>

</html>