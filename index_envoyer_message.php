<?php if(isset($_SESSION["data"])) 
            { ?>

            <div id="div_message">
                <form method="post" action="sql_save_request.php">
                    <input type="textarea" name="message" id="message"> 
                    <input type="submit" value="Envoyer">
                </form>
            </div>
        <?php } ?>