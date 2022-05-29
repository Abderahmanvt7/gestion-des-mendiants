
    <?php
        // messages de success
        if (isset($operation_success) && $operation_success) { ?>
            <div  class="alert alert-success alert-dismissible fade show errorphp-alert" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php echo $message_response ?>
            </div>
        <?php }
        // messages d'echec
        if (isset($operation_success) && !$operation_success) { ?>
            <div  class="alert alert-danger alert-dismissible fade show errorphp-alert" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php echo $message_response ?>
            </div>
        <?php } ?>
        <?php
        $pages_dont_use_main_js_file = !isset($page_name_index) && !isset($page_name_hebergement) && !isset($page_name_administrateur);
        if ($pages_dont_use_main_js_file) { 
            ?>
            <script src="<?php echo $js; ?>main.js"></script>
            <?php  }
    ?>

    <script src="<?php echo $js; ?>vendors/mk_charts.js"></script>
    </body>
</html>