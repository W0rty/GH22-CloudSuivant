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
    if(isset($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["password"]))
    {
        include("core/database/db_connect.php");
        $sth = $db->prepare("SELECT * FROM users WHERE email = :e AND password = :p");
        $sth->bindValue(":e",htmlspecialchars($_POST["email"]));
        $sth->bindValue(":p",md5($_POST["password"]));
        $sth->execute();
        $res = $sth->fetchAll();
        if(sizeof($res) > 0)
        {
            $_SESSION["email"] = htmlspecialchars($_POST["email"]);
            header("Location: /?page=myfile");
        }
        else $msgError = "Les identifiants fournis ne sont pas valides.";
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
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="worty@grehack2022.fr" required>
                    <small id="emailHelp" class="form-text text-muted"><span style="color: white;">Votre adresse email reste anonyme.</span></small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="***************" required>
                </div>  
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>
</div>
</body>
<?php include("core/templates/footer.php"); ?>