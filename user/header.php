<?php
/* include main config file file */
include_once ('../include/config.php');

/* include main client function file */
include_once ('user_functions.php');
/* client function class variable */
if (isset($_REQUEST['shop']) && $_REQUEST['shop'] != '') {
    $shop = $_REQUEST['shop'];
    $uf_obj = new User_functions($shop);
    $current_user = $uf_obj->get_store_detail_obj();
    if (empty($current_user)) {
        header('Location:' . PAGE_404 . '');
        exit;
    }
} else {
    header('Location:' . PAGE_404 . '');
    exit;
}
$custom_client_mode_time = filemtime('../assets/css/custom.css');
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo SITE_NAME; ?> | <?php echo $shop; ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSS -->
        <link rel="stylesheet" media="screen" href="../assets/css/polaris.css" />
        <link rel="stylesheet" media="screen" href="../assets/css/custom.css?v=<?php echo $custom_client_mode_time; ?>" />
        
        <!-- Js -->
        <script type="text/javascript">var shop = '<?php echo $shop; ?>'; var mode = '<?php echo MODE; ?>';</script>
        <script type="text/javascript" src="../assets/js/jquery-2.1.1.min.js"></script>
        <?php include_once('app_bridge.php'); ?>
        <script type="text/javascript" src="../assets/js/custom.js?v=<?php echo filemtime('../assets/js/custom.js'); ?>"></script>
    </head>
    <body>
        <div class="Polaris-Page Polaris-Page--fullWidth">