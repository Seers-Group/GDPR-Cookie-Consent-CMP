<?php
header("Access-Control-Allow-Origin: *");
/* include main config file file */
include_once ('../include/config.php');
/* include main client function file */
include_once ('user_functions.php');

//ob_clean();

$is_bad_shop = 0;
if (isset($_POST['shop']) && $_POST['shop'] != '') {
    
    if(isset($_POST['is_analytics']) && $_POST['is_analytics']=='1'){
        include_once ('analytics.php');
        $uf_obj = new Analytics($_POST['shop']);
    }else{
        $uf_obj = new User_functions($_POST['shop']);
    }

    $current_user = $uf_obj->get_store_detail_obj();

    if (!empty($current_user)) {
        /* used for called function (comes from ajax call) */
        if (isset($_POST['method_name']) && $_POST['method_name'] != '') {
            $response = call_user_func(array($uf_obj, $_POST['method_name']));
            echo json_encode($response);
            exit;
        }
    } else {
        $is_bad_shop ++;
    }
} else {
    $is_bad_shop ++;
}

if ($is_bad_shop > 0) {
    $response = array('result' => 'fail', 'msg' => 'Opps! Bad request call!', 'code' => '403');
    echo json_encode($response);
}