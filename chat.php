<?php 
    try
    {
        $bdd = new PDO ("mysql:host=localhost;dbname=chat;charset=utf8","root","",
                         array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch(Exception $e)
    {
        die($e->getMessage());
    }

    if(isset($_POST["pseudoCreation"]) && $_POST["pseudoCreation"] != "" 
        && isset($_POST["passwordCreation"]) && $_POST["passwordCreation"] != "")
    {
        $insertUtilisateur = $bdd->prepare("INSERT INTO utilisateurs(pseudo, password) 
                                VALUES(:pseudo, :password)");
        $insertUtilisateur->execute(array("pseudo" => $_POST["pseudoCreation"], 
                                "password" => $_POST["passwordCreation"]));
    }

    # PHP SE CONNECTER A UN UTILISATEUR
    $dataUtilisateur = NULL;
    if(isset($_POST["pseudoConnexion"]) && isset($_POST["passwordConnexion"]))
    {
        $selectUtilisateur = $bdd->prepare("SELECT pseudo, password FROM utilisateurs
                                WHERE pseudo=:pseudo AND password=:password");

        $selectUtilisateur->execute(array("pseudo" => $_POST["pseudoConnexion"], 
                                "password" => $_POST["passwordConnexion"]));
        $dataUtilisateur = $selectUtilisateur->fetch();
        $selectUtilisateur->closeCursor();
    }

    # PHP POUR ECRIRE UN MESSAGE 
    if(isset($_POST["message"]) && $dataUtilisateur != NULL )
    {
        
        $insertMessage = $bdd->prepare("INSERT INTO utilisateurs_messages(utilisateur_id,message)
                                        VALUES(:id, :message)");
        $insertMessage->execute(array("id" => $dataUtilisateur["id"],
                                    "message" => $_POST["message"]));
    }

    # PHP POUR CAPTURER L'ENSEMBLE DES MESSAGES
    $selectMessage = $bdd->query("SELECT message, date, pseudo, image FROM utilisateurs_messages 
                                INNER JOIN utilisateurs ON utilisateurs.id = utilisateurs_messages.utilisateur_id 
                                ORDER BY date DESC");

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Mini-chat</title>
    </head>

    <body>
        <?php if($dataUtilisateur == NULL) {?>
            <form method="post" action="index.php">
                <p>CONNEXION :<br>
                Pseudo : <br>   
                <input type="text" name="pseudoConnexion" id="pseudoConnexion"><br>
                Password : <br>
                <input type="text" name="passwordConnexion" id="passwordConnexion" >    
                <input type="submit" value="Connexion"></p>
            </form>
        <?php 
        } 
        else 
        { 
            echo $dataUtilisateur["pseudo"]; 
        } ?>

        <form method="post" action="index.php">
            <p>CREATION :<br>
            Pseudo : <br>   
            <input type="text" name="pseudoCreation" id="pseudoCreation"><br>
            Password : <br>
            <input type="text" name="passwordCreation" id="passwordCreation" >    
            <input type="submit" value="Creer compte"></p>
        </form>

    <?php while($dataMessage = $selectMessage->fetch()) { ?>
        <div id="conversation">
            <div id="avatarConversation">
                <?php echo $dataMessage["pseudo"] ?><br/>
                <img href=<?php echo $dataMessage["image"]?> />
            </div>
            <div id="messageConversation">
                <p><?php echo $dataMessage["message"] ?></p>
            </div>
        </div>
    
    <?php } $selectMessage->closeCursor(); ?>

    <?php if($dataUtilisateur != NULL) { ?>
        <form method="post" action="index.php">
            <input type="textarea" name="message" id="message"> 
            <input type="submit" value="Envoyer">
        </form>
        <?php } ?>


    </body>


</html>