<?php
include_once '../include/config.php';
include_once '../include/common_function.php';

/* Common function object */
$cf_obj = new common_function();

$shop_info = file_get_contents('php://input');

/* shop info array */
$shop_info = json_decode($shop_info, TRUE);

$selected_field = 'store_user_id,email';
$where = array('shop' => $shop_info['shop_domain']);
$table_shop_info = $cf_obj->select_row(TABLE_USER_STORES, $selected_field, $where);

if ($table_shop_info != '' && !empty($table_shop_info) && $table_shop_info['email'] != '') {
    $fields = array(
        'domain' => '',
        'owner' => '',
        'shop_plan' => '',
        'money_format' => '',
        'currency' => '',
        'address1' => '',
        'address2' => '',
        'city' => '',
        'country_name' => '',
        'phone' => '',
        'province' => '',
        'zip' => '',
        'timezone' => '',
        'iana_timezone' => '',
        'weight_unit' => ''
    );

    $where = array('shop' => $shop_info['shop_domain']);
    $cf_obj->update(TABLE_USER_STORES, $fields, $where);
}