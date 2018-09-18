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
    $selectMessage = $bdd->prepare("SELECT message, date, pseudo, image FROM utilisateurs_messages 
                                INNER JOIN utilisateurs ON utilisateurs.id = utilisateurs_messages.utilisateur_id 
                                ORDER BY date DESC LIMIT 5 OFFSET :start");

    if(!isset($_SESSION["start"]))
        $_SESSION["start"] = 0;

    $selectMessage->bindValue('start', $_SESSION["start"], PDO::PARAM_INT);
    $selectMessage->execute();        

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="style.css" />
        <title>Mini-chat</title>
    </head>

    <body>
        <div id="conteneur_utilisateur">
            <div class="element_utilisateur" >
                <?php if(!isset($_SESSION["data"])) {?>
                    
                    <form method="post" action="index.php">
                        <p>Pseudo : <input type="text" name="pseudoConnexion" id="pseudoConnexion" 
                        value=<?php if(isset($_COOKIE["pseudoChat"]))
                        {
                             echo htmlspecialchars($_COOKIE["pseudoChat"]);
                        }
                        else { echo htmlspecialchars("");} ?>><br>
                        Password : <input type="password" name="passwordConnexion" id="passwordConnexion" value = ""><br>
                        <input type="submit" value="Connexion"></p>
                    </form>
                <?php 
                } 
                else 
                { 
                    echo htmlspecialchars($_SESSION["data"]['pseudo']);
                ?>
                    <form method="post" action="index.php">
                        <input type="submit" value="deconnexion" name="deconnexion"/>
                    </form>
                
                <?php
                } 
                ?>
            </div>

            <div class="element_utilisateur">

                <form method="post" action="sql_save_request.php">
                    <p>Pseudo : <input type="text" name="pseudoCreation" id="pseudoCreation" value=""><br>
                    Password : <input type="password" name="passwordCreation" id="passwordCreation" value=""><br> 
                    <input type="submit" value="Creer compte"></p>
                </form>
            </div>
        </div>

            <?php while($dataMessage = $selectMessage->fetch()) { ?>
                <div id="conteneur_message">
                    <div class="element_message">
                        <?php echo htmlspecialchars($dataMessage["pseudo"]) ?><br/>
                        <img href=<?php echo htmlspecialchars($dataMessage["image"])?> />
                    </div>
                    <div class="element_message">
                        <p><?php echo htmlspecialchars($dataMessage["message"]) ?></p>
                    </div>
                </div>
            
            <?php } $selectMessage->closeCursor(); ?>

    <?php if(isset($_SESSION["data"])) 
        { ?>

        <form method="post" action="sql_save_request.php">
            <input type="textarea" name="message" id="message"> 
            <input type="submit" value="Envoyer">
        </form>
 
  <?php } ?>

    <div id="pages">
        <div class="element_pages">
            <?php if($_SESSION["start"] !=0) { ?>
            <form method="post" action="index.php" id="poster_message"> 
                <input type="submit" value="Page précente" name="before">
            </form>
            <?php } ?>
        </div>

        <div class="element_pages">
            <?php if($_SESSION["start"] < $countMessage) { ?>
            <form method="post" action="index.php"> 
                <input type="submit" value="Page suivante" name="after">
            </form>
            <?php } ?>
        </div>
    </div>


        <form method="post" action="index.php"> 
            <input type="submit" value="Rafraichir">
        </form>
    </body>
</html>