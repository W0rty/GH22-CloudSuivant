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
if($_SERVER["REQUEST_METHOD"] === "POST")
{
    if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password2"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["password2"]))
    {
        if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
        {
            if(strlen($_POST["email"]) <= 100)
            {
                if($_POST["password"] === $_POST["password2"])
                {
                    include("core/database/db_connect.php");
                    $sth = $db->prepare("SELECT * FROM users WHERE email = :e");
                    $sth->bindValue(":e",htmlspecialchars($_POST["email"]));
                    $sth->execute();
                    $res = $sth->fetchAll();
                    if(sizeof($res) > 0)
                    {
                        $msgError = "Cette adresse email est déjà utilisée.";
                    }
                    else
                    {
                        $sth = $db->prepare("INSERT INTO users VALUES(0,:e,:p)");
                        $sth->bindValue(":e",htmlspecialchars($_POST["email"]));
                        $sth->bindValue(":p",md5($_POST["password"]));
                        $sth->execute();
                        @mkdir("/var/data/".htmlspecialchars($_POST["email"]));
                        header("Location: /?page=login");
                        die();
                    }
                }else $msgError = "Les mots de passe ne sont pas identiques.";
            }else $msgError = "L'adresse email est trop longue.";
        }
        else $msgError = "L'adresse email n'est pas valide.";
    }else $msgError = "Il manque des paramètres.";
}
include("core/templates/header.php");
?>
<body style="background-color: #0082c8">
<div class="container">
    <div class="row">
        <div class="col text-center">
            <img src="/assets/img/logo.png" width="100" height="100">
            <p class="lead" style="color: white;">Bienvenue sur l'instance de cloud 100% française !</p>
            <?php if(isset($msgError)) echo '<p class="lead" style="color: red;">'.$msgError.'</p>'; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="exampleInputEmail1">Votre adresse email</label>
                    <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="worty@grehack2022.fr" required>
                    <small id="emailHelp" class="form-text text-muted"><span style="color: white;">Votre adresse email reste anonyme.</span></small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="***************" required>
                </div>  
                <div class="form-group">
                    <label for="password2">Confirmation du mot de passe</label>
                    <input type="password" class="form-control" id="password2" name="password2" placeholder="***************" required>
                </div>  
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>
</div>
</body>
<?php include("core/templates/footer.php"); ?>