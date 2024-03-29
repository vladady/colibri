<div class="container">
    <?php print $jumbotron; ?>
    <?php if(isset($alerts)) print $alerts;?>
    <div class="row">
        <div class="col-md-4">
            <h3>Finishing installation</h3>
            <p>
                After clicking on the <strong>Create my site</strong> button, Opis Colibri will
                generate a site configuration file for you and save it into
                <code><abbr title="<?php print $fullpath . '/storage/site.php';?>"><?php print $path . '/storage/site.php';?></abbr></code>.
                The content of the autogenerated file is displayed on the right side of this page
                and reflects all the settings you have made during the installation process.
            </p>
            <p>
                Modules can be managed through console commands.
                After you finish the installation process, open
                a terminal, go to <code><?php print $fullpath;?></code> and type <code>php colibri list</code>
                to get a list with all the available commands.
            </p>
            <p>
                If you created an administrator account you can mamnage your modules directly
                from your web browser by accessing <code><?php print $adminpath; ?></code>
            </p>
            
        </div>
        <div class="col-md-8">
            <?php if(isset($alerts)) print $alerts;?>
            <div class="generated-code">
                <?php print $code;?>
            </div>
        </div>
    </div>
</div>