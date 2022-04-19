<?php
include_once '../include/config.php';
include_once '../include/common_function.php';

$shop = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];

$cf_obj = new common_function($shop);

$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];

/* Here we get all information about customer */
$shop_update = file_get_contents('php://input');

/* Todo: checked verify_webhook response(return type ) than set condition according to it */
$verified = $cf_obj->verify_webhook($shop_update, $hmac_header);

if (!empty($cf_obj) && $verified && $cf_obj->is_json($shop_update)) {
    /* shop detail array */
    $shop_detail_arr = json_decode($shop_update, TRUE);
    $fields = array(
        'currency' => $shop_detail_arr['currency'],
        'money_format' => mysqli_real_escape_string($cf_obj->db_connection, $shop_detail_arr['money_format']),
        'owner' => $shop_detail_arr['shop_owner'],
        'shop_plan' => $shop_detail_arr['plan_name'],
        'address1' => $shop_detail_arr['address1'],
        'address2' => $shop_detail_arr['address2'],
        'city' => $shop_detail_arr['city'],
        'country_name' => $shop_detail_arr['country_name'],
        'phone' => $shop_detail_arr['phone'],
        'province' => $shop_detail_arr['province'],
        'zip' => $shop_detail_arr['zip'],
        'timezone' => $shop_detail_arr['timezone'],
        'iana_timezone' => $shop_detail_arr['iana_timezone'],
        'domain' => $shop_detail_arr['domain'],
        'weight_unit' => $shop_detail_arr['weight_unit'],
        );
    
    $selected_field = 'shop_plan, store_user_id';
    $where = array('shop' => $shop);
    $shop_info_db = $cf_obj->select_row(TABLE_USER_STORES, $selected_field, $where);
}
?>