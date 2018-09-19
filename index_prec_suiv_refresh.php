<div id="pages">
    
    <div class="element_pages">
        <?php if($_SESSION["start"] !=0) { ?>
        <form method="post" action="index.php"> 
            <input type="submit" value="Page précente" name="before">
        </form>
        <?php } ?>
    </div>

    <div class="element_pages">
        <?php if(($_SESSION["start"] + 5) < $countMessage) { ?>
        <form method="post" action="index.php"> 
            <input type="submit" value="Page suivante" name="after">
        </form>
        <?php } ?>
    </div>

</div>

    <div id="div_rafraichir">
        <form method="post" action="index.php"> 
            <input type="submit" value="Rafraichir">
        </form>
    </div>