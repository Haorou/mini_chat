<?php while($dataMessage = $selectMessage->fetch()) { ?>
    <div id="conteneur_message">
        <div class="element_message">
            <?php echo htmlspecialchars($dataMessage["pseudo"]) ?><br/>
            <?php echo htmlspecialchars($dataMessage["date"])?>
        </div>
        <div class="element_message">
            <p><?php echo htmlspecialchars($dataMessage["message"])?></p>
        </div>
    </div>
            
<?php } $selectMessage->closeCursor(); ?>