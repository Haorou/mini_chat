<?php 

    session_start();

    try
    {
        $bdd = new PDO ("mysql:host=localhost;dbname=chat;charset=utf8","root","",
                         array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e)
    {
        die($e->getMessage());
    }

    # PHP POUR SE DECONNECTER
    if(isset($_POST["deconnexion"]))
    {
        session_destroy();
        session_start();
    }

    if(isset($_POST["before"]) && $_SESSION["start"] > 0 )
    {
        $_SESSION["start"] -= 5;
    }

    $countMessage = $bdd->prepare("SELECT * FROM utilisateurs_messages");
    $countMessage->execute();
    $countMessage = $countMessage->rowCount();

    if(isset($_POST["after"]) && $_SESSION["start"] < $countMessage)
    {
        $_SESSION["start"] += 5;
    }

    # PHP SE CONNECTER A UN UTILISATEUR
    if(isset($_POST["pseudoConnexion"]) && isset($_POST["passwordConnexion"]))
    {
        $selectUtilisateur = $bdd->prepare("SELECT id, pseudo, password FROM utilisateurs
                                WHERE pseudo=:pseudo AND password=:password");

        if($selectUtilisateur->execute(array("pseudo" => $_POST["pseudoConnexion"], 
                                "password" => $_POST["passwordConnexion"])))
        {

            $row = $selectUtilisateur->fetch();
            if($row)
            {
                $_SESSION["data"] = $row;
                setcookie('pseudoChat', $_SESSION["data"]["pseudo"], time() + 365*24*3600, null, null, false, true);
            }

            $selectUtilisateur->closeCursor();
        }
    }

    # PHP POUR CAPTURER L'ENSEMBLE DES MESSAGES
    $bdd->query("SET lc_time_names = 'fr_FR' ");
    $selectMessage = $bdd->prepare("SELECT message, DATE_FORMAT(date, 'le %d %M %Y à %H:%i:%s') AS date, pseudo FROM utilisateurs_messages 
                                LEFT JOIN utilisateurs ON utilisateurs.id = utilisateurs_messages.utilisateur_id 
                                ORDER BY date DESC LIMIT 5 OFFSET :start");

    if(!isset($_SESSION["start"]))
        $_SESSION["start"] = 0;

    $selectMessage->bindValue('start', $_SESSION["start"], PDO::PARAM_INT);
    $selectMessage->execute();   

    $nomUtilisateurCookie = "";
    if(isset($_COOKIE["pseudoChat"])) 
    {
         $nomUtilisateurCookie = $_COOKIE["pseudoChat"];
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="style.css" />
        <title>Mini-chat</title>
    </head>

    <body>
        <?php include("index_champs_utilisateur.php"); ?>

        <?php include("index_afficher_message.php"); ?>

        <?php include("index_envoyer_message.php")?>

        <?php include("index_prec_suiv_refresh.php")?>
    </body>
</html>