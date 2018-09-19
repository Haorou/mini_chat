<div id="conteneur_utilisateur">
    <div class="element_utilisateur" >
        <?php if(!isset($_SESSION["data"])) {?>
            
            <form method="post" action="index.php">
                <p>Pseudo : <input type="text" name="pseudoConnexion" id="pseudoConnexion" 
                value=<?php echo htmlspecialchars($nomUtilisateurCookie) ?>><br>
                Password : <input type="password" name="passwordConnexion" id="passwordConnexion" value = ""><br>
                <input type="submit" value="Connexion"></p>
            </form>

        <?php } else { echo htmlspecialchars($_SESSION["data"]["pseudo"]); ?>

            <form method="post" action="index.php">
                <input type="submit" value="deconnexion" name="deconnexion"/>
            </form>
        
        <?php } ?>
    </div>

    <div class="element_utilisateur">

        <form method="post" action="sql_save_request.php">
            <p>Pseudo : <input type="text" name="pseudoCreation" id="pseudoCreation" value=""><br>
            Password : <input type="password" name="passwordCreation" id="passwordCreation" value=""><br> 
            <input type="submit" value="Creer compte"></p>
        </form>
    </div>
</div>