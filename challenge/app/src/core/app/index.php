<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}
if(isset($_SESSION["email"]))
{
    header("Location: /?page=myfile");
    die();
}
include("core/templates/header.php");
?>
<body style="background-color: #0082c8">
<div class="container">
    <div class="row">
        <div class="col text-center">
            <img src="/assets/img/logo.png" width="100" height="100">
            <p class="lead" style="color: white;">Bienvenue sur l'instance de cloud 100% française !</p>
            <p class="lead" style="color: white;">Que souhaitez-vous faire ?</p>
            <input type="submit" class="btn btn-primary" value="Connexion" onclick="window.location.href='/?page=login'"/>
            <input type="submit" class="btn btn-primary" value="Création de compte" onclick="window.location.href='/?page=register'"/> 
            <input type="submit" class="btn btn-primary" value="Upload de fichier temporaire" onclick="window.location.href='/?page=temp_upload'"/>
        </div>
    </div>
</div>
</body>
<?php include("core/templates/footer.php"); ?>