<?php
if(count(get_included_files()) ==1)
{
    header("Location: /?page=403&msg=L'accès direct aux fichiers du serveur n'est pas autorisé.");
    die();
}

function downloadFile($name,$email)
{
    include("config/conf.php"); 
    $location = $upload_folder.$email."/".$name;
    if(file_exists($location))
    {
        header("Cache-Control: public");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:".filesize($location));
        header("Content-Disposition: attachment; filename=".$name);
        readfile($location);
        die();
    }else die("Une erreur est survenue.");
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
        if(!isset($_GET["uuid"]) && isset($_SESSION["email"]))
        {
            $sth = $db->prepare("SELECT id FROM users WHERE email = :e");
            $sth->bindValue(":e",htmlspecialchars($_SESSION["email"]));
            $sth->execute();
            $res_user = $sth->fetchAll();
            if(sizeof($res_user) > 0)
            {
                if(isset($_SESSION["lastDownloadFile"]))
                {
                    $lastDl = $_SESSION["lastDownloadFile"];
                    if($lastDl->getLastDl() == $res[0][0])
                    {
                        header("Location: /?page=myfile&msg=Vous avez déjà téléchargé ce fichier, nous vous économisons de la place :).");
                        die();
                    }
                }
                if($res_user[0][0] === $res[0][1])
                {
                    $lastDl = new ControllerDownload($res[0][0]);
                    $_SESSION["lastDownloadFile"] = $lastDl;
                    downloadFile($res[0][0],$_SESSION["email"]);
                }else header("Location: /?page=403&msg=Le fichier auquel vous tentez d'accéder ne vous appartient pas.");
            }else header("Location: /?page=logout");
        }
        else
        {
            if(isset($_GET["uuid"]) && !empty($_GET["uuid"]))
            {
                $sth = $db->prepare("SELECT email FROM users WHERE id = :i");
                $sth->bindValue(":i",$res[0][1]);
                $sth->execute();
                $res_user = $sth->fetchAll();
                if(sizeof($res_user) > 0)
                {
                    if(isset($_GET["uuid"]) && !empty($_GET["uuid"]))
                    {
                        if($_GET["uuid"] === $res[0][2])
                        {
                            downloadFile($res[0][0],$res_user[0][0]);
                        }else header("Location: /?page=403&msg=Le fichier auquel vous tentez d'accéder ne vous appartient pas.");
                    }else header("Location: /?page=403&msg=Le fichier auquel vous tentez d'accéder ne vous appartient pas.");
                }else header("Location: /?page=404&msg=Le lien partagé est incorrect.");
            }else header("Location: /?page=404&msg=Le fichier spécifié est introuvable.");
        }
    }else header("Location: /?page=404&msg=Le fichier spécifié est introuvable.");
}else header("Location: /?page=404&msg=Le fichier spécifié est introuvable.");