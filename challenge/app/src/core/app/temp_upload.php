<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}
include("core/templates/header.php");
if($_SERVER["REQUEST_METHOD"] === "POST")
{
    if(isset($_FILES["fileUpload"]) && isset($_POST["email"]))
    {
        if(strlen($_POST["email"]) < 100)
        {
            if($_FILES["fileUpload"]["size"] > 1000)
            {
                $msgError = "Le fichier est trop volumineux.";
            }
            else
            {
                if(preg_match('/[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+/', $_POST["email"]))
                {
                    include("core/database/db_connect.php");
                    include("config/conf.php");
                    $sth = $db->prepare("SELECT * FROM users WHERE email = :e");
                    $sth->bindValue(":e",$_POST["email"]);
                    $sth->execute();
                    $res = $sth->fetchAll();
                    if(sizeof($res) > 0)
                    {
                        $msgError = "Cette adresse email est déjà utilisée, veuillez en choisir une autre.";
                    }
                    else
                    {
                        @mkdir($upload_folder.$_POST["email"]);
                        $name = preg_replace("/[^a-zA-Z0-9._-]+/", "",basename($_FILES["fileUpload"]["name"]));
                        $location = $upload_folder.$_POST["email"]."/".$name;
                        if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $location))
                        {
                            $uniq_id = uniqid("cloudsuivant_");
                            $sth = $db->prepare("INSERT INTO temp_files VALUES(0,:n,:u,:e)");
                            $sth->bindValue(":n", htmlspecialchars($name));
                            $sth->bindValue(":u",$uniq_id);
                            $sth->bindValue(":e", htmlspecialchars($_POST["email"]));
                            $sth->execute();
                            $msgSuccess = "Votre fichier est accessible ici : /?page=temp_download&id=".$db->lastInsertId()."&uuid=".$uniq_id;
                        }else $msgError = "Erreur lors du téléversement du fichier, veuillez réessayer.";
                    }
                }else $msgError = "L'adresse email est invalide.";
            }
        }else $msgError = "L'adresse email est trop longue.";
    }else $msgError = "Il manque des paramètres.";
}
?>
<body style="background-color: #0082c8">
<div class="container">
    <div class="row">
        <div class="col text-center">
            <img src="/assets/img/logo.png" width="100" height="100">
            <p class="lead" style="color: white;">Bienvenue sur le cloud !</p>
            <p class="lead" style="color: white;">Cette page vous sert à téléverser un fichier sans être authentifié sur la plateforme.</p>
            <p class="lead" style="color: white;">Attention, les fichiers seront supprimés 10 minutes après et sont limités à 1Mo.</p>
            <?php if(isset($msgError)) echo '<p class="lead" style="color: red;">'.$msgError.'</p>'; ?>
            <?php if(isset($msgSuccess)) echo '<p class="lead" style="color: white;">'.$msgSuccess.'</p>'; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="exampleInputEmail1">Votre adresse email</label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="worty@grehack2022.fr" required>
                    <small id="emailHelp" class="form-text text-muted"><span style="color: white;">Votre adresse email reste anonyme.</span></small>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Fichier à téléverser</label>
                    <input type="file" class="form-control" name="fileUpload" id="fileUpload" aria-describedby="fileHelp" required>
                    <small id="fileHelp" class="form-text text-muted"><span style="color: white;">Nous ne regardons pas vos fichiers.</span></small>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>
</div>
</body>
<?php include("core/templates/footer.php"); ?>