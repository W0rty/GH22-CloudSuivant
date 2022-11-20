<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}
if($_SERVER["REQUEST_METHOD"] === "POST")
{
    if(isset($_FILES["fileUpload"]))
    {
        if($_FILES["fileUpload"]["size"] > 10000)
        {
            $msgError = "Le fichier est trop volumineux.";
        }
        else
        {
            include("config/conf.php");
            $name = preg_replace("/[^a-zA-Z0-9.]+/", "",basename($_FILES["fileUpload"]["name"]));
            $location = $upload_folder.$_SESSION["email"]."/".$name;
            if(move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $location))
            {
                include("core/database/db_connect.php");
                $sth = $db->prepare("SELECT id FROM users WHERE email = :e");
                $sth->bindValue(":e",htmlspecialchars($_SESSION["email"]));
                $sth->execute();
                $res_user = $sth->fetchAll();
                if(sizeof($res_user) > 0)
                {
                    $id = $res_user[0][0];
                    $sth = $db->prepare("INSERT INTO files VALUES(0,:n,:u,:au)");
                    $sth->bindValue(":n", htmlspecialchars($name));
                    $sth->bindValue(":u",uniqid("cloudsuivant_"));
                    $sth->bindValue(":au",$id);
                    $sth->execute();
                    header("Location: /?page=myfile");
                    die();
                }
                else
                {
                    header("Location: /?page=logout");
                    die();
                }
            }else $msgError = "Erreur lors du téléversement du fichier, veuillez réessayer.";
        }
    }else $msgError = "Il manque des paramètres.";
}
include("core/templates/header.php");
?>
<body style="background-color: #0082c8">
<div class="container">
    <div class="row">
        <div class="col text-center">
            <img src="/assets/img/logo.png" width="100" height="100">
            <p class="lead" style="color: white;">Bienvenue votre cloud !</p>
            <?php if(isset($msgError)) echo '<p class="lead" style="color: red;">'.$msgError.'</p>'; ?>
            <form method="POST" enctype="multipart/form-data">
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
