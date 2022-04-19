<?php
include_once '../include/config.php';
include_once '../include/common_function.php';
include_once '../user/user_functions.php';

if (MODE == 'dev') {
    $shop = $_GET['shop'];
} else {
    $shop = isset($_SERVER['X-Shopify-Shop-Domain']) ? $_SERVER['X-Shopify-Shop-Domain'] : $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
}

$cf_obj = new common_function();
$us_obj = new User_functions($shop);

$shop_name = $email = $store_user_id = '';
$where = array('shop' => $shop);
$shop_detail = $cf_obj->select_row(TABLE_USER_STORES, 'store_user_id, name, shop, email', $where);
if(!empty($shop_detail)){
    $store_user_id = $shop_detail['store_user_id'];
    $shop_name = $shop_detail['name'];
    $shopdom = $shop_detail['shop'];
    $email = $shop_detail['email'];
    
    //save plugin is deactive on plugins db this plugin
    $cf_obj->plugin_active_inactive($shop_detail, 0);
    
    //remove the js script from html
    // SEND API CALL
        $data = array(
            'domain' => $shopdom,
            'user_domain' => $shopdom,
            'email' => $email,
            'user_email' => $email,
            'secret' => '$2y$10$9ygTfodVBVM0XVCdyzEUK.0FIuLnJT0D42sIE6dIu9r/KY3XaXXyS',
            'platform' => 'shopify',
            'status'=>'0'
        );

//         /******* Curl call start *****/
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://seersco.com/api/banner-settings",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data
        ));

        $response = curl_exec($curl);
        $error_number = curl_errno($curl);
        $error_message = curl_error($curl);
        curl_close($curl);

        $result =  json_decode($response, TRUE);
}

$fields = array(
    'status' => '0',
    'app_status' => '0',
    'toggle_status' => '0'
);
$where = array('shop' => $shop);
$cf_obj->update(TABLE_USER_STORES, $fields, $where);
?>