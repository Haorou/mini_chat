<?php
    session_start();
    try
    {
        $bdd = new PDO ("mysql:host=localhost;dbname=chat;charset=utf8","root","",
                         array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e)
    {
            die('Erreur : '.$e->getMessage());
    }

    function issetAndNotEmpty($variableToCheck)
    {
        $issetAndNotEmpty = False;
        if(isset($variableToCheck) && $variableToCheck != "")
            $issetAndNotEmpty = True;

        return $issetAndNotEmpty;
    }
    
    # PHP POUR CREER UN UTILISATEUR
    if(issetAndNotEmpty($_POST["pseudoCreation"]) && issetAndNotEmpty($_POST["passwordCreation"]))
    {
        $insertUtilisateur = $bdd->prepare("INSERT INTO utilisateurs(pseudo, password) 
                                VALUES(:pseudo, :password)");
        $insertUtilisateur->execute(array("pseudo" => $_POST["pseudoCreation"], 
                                "password" => $_POST["passwordCreation"]));
    }

    # PHP POUR ECRIRE UN MESSAGE 
    if(issetAndNotEmpty($_POST["message"]) && isset($_SESSION["data"]))
    {
        
        $insertMessage = $bdd->prepare("INSERT INTO utilisateurs_messages(utilisateur_id,message)
                                        VALUES(:id, :message)");
        $insertMessage->execute(array("id" => $_SESSION["data"]["id"],
                                    "message" => $_POST["message"]));
    }

    header('Location: index.php');
?>