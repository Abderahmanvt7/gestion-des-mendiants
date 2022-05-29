<?php

    //include_once "$init_php";
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css; ?>all.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>components/navbar.css">
    <link rel="stylesheet" href="<?php echo $css; ?>components/box.css">
    <link rel="stylesheet" href="<?php echo $css; ?>components/table.css">
    <link rel="stylesheet" href="<?php echo $css; ?>components/form.css">
    <link rel="stylesheet" href="<?php echo $css; ?>components/popup.css">
    <link rel="stylesheet" href="<?php echo $css; ?>components/alert.css">
    <link rel="stylesheet" href="<?php echo $css; ?>components/button.css">
    <link rel="stylesheet" href="<?php echo $css; ?>components/loading.css">
    <link rel="stylesheet" href="<?php echo $css; ?>vendors/mk_charts.css">
    <link rel="stylesheet" href="<?php echo $css; ?>main.css">
    <?php if (isset($page_name_hebergement)) { // spacial page pour hebergement?> 
        <link rel="stylesheet" href="<?php echo $css; ?>pages/hebergements.css">
    <?php } ?>
    <title></title>
</head>
<body>


