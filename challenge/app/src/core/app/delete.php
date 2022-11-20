<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}
if(!isset($_SESSION["email"]))
{
    header("Location: /?page=login");
    die();
}

if(isset($_GET["id"]) && !empty($_GET["id"]))
{
    include("core/database/db_connect.php");
    $sth = $db->prepare("SELECT name, associateUser, uuid FROM files WHERE id = :i");
    $sth->bindValue(":i",htmlspecialchars($_GET["id"]));
    $sth->execute();
    $res = $sth->fetchAll();
    if(sizeof($res) > 0)
    {
        $sth = $db->prepare("SELECT id FROM users WHERE email = :e");
        $sth->bindValue(":e",htmlspecialchars($_SESSION["email"]));
        $sth->execute();
        $res_user = $sth->fetchAll();
        if(sizeof($res_user) > 0)
        {
            if($res_user[0][0] === $res[0][1])
            {
		include("config/conf.php");
		$location = $upload_folder.$_SESSION["email"]."/".$res[0][0];
                $sth = $db->prepare("DELETE FROM files WHERE id = :i");
                $sth->bindValue(":i",htmlspecialchars($_GET["id"]));
                $sth->execute();
                unlink($location);
                header("Location: /?page=myfile");
            }else header("Location: /?page=403&msg=Le fichier auquel vous tentez d'accéder ne vous appartient pas.");
        }else header("Location: /?page=logout");
    }else header("Location: /?page=myfile");
}else header("Location: /?page=myfile");
