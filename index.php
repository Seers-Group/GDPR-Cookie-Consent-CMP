<?php

include_once 'include/config.php';
include_once 'include/common_function.php';

/* * ****************************************
 *            WEBHOOK ARRAY               *
 * **************************************** */
/*
 * When we need to add webhook you need to add topic into 
 * array list and need to make(add) file with same as topic name 
 * just replace "/" (slash) with "-" (hypehn,minus) sign
 * for e.g app-unistalled.php
 */

$__webhook_arr = array(
    'app/uninstalled',
    'shop/update'
);

/* * ****************************************
 *          WEBHOOK ARRAY END             *
 * **************************************** */

/* create object common function */
$cf_obj = new common_function();

if (isset($_REQUEST['shop']) && $_REQUEST['shop'] != "") {
    $shop = isset($_POST['shop']) ? $_POST['shop'] : $_GET['shop'];
    $selected_field = 'store_user_id, token';
    $where = array('shop' => $shop, 'status' => '1');
    $store_row = $cf_obj->select_row(TABLE_USER_STORES, $selected_field, $where);

    if (isset($_GET['code'])) {
        $thehost = (!empty($_REQUEST['host']) ? $_REQUEST['host'] : "");
        $url_param_arr = array('client_id' => SHOPIFY_API_KEY, 'client_secret' => SHOPIFY_SECRET, 'code' => $_GET['code']);
        $responce = $cf_obj->prepare_api_condition(array('oauth', 'access_token'), $url_param_arr, 'POST', 0, '', $shop);
        
        $token = $responce['body']['access_token'];
        if (!empty($store_row)) {
            header('Location: ' . SITE_USER_URL . '?shop=' . $shop);
        } else {
            $responce = $cf_obj->prepare_api_condition(array('shop'), array(), 'GET', 0, $token, $shop);
            
            $shopinfo = $responce['body']['shop'];
            /* Register Webhook */
            if (!empty($__webhook_arr)) {
                foreach ($__webhook_arr as $topic) {
                    $file_name = str_replace('/', '-', $topic) . '.php';
                    $url_param_arr = array('webhook' => array(
                            'topic' => $topic,
                            'address' => SITE_URL . 'webhook/' . $file_name,
                            'format' => 'json'
                    ));
                    $cf_obj->prepare_api_condition(array('webhooks'), $url_param_arr, 'POST', 0, $token, $shop);
                }
            }

            $email = $shopinfo['email'];
            $domain = $shopinfo['domain'];
            $timezone = $shopinfo['iana_timezone'];
            $shop_name = $shopinfo['name'];
            $shop_details = array(
                'email' => $email,
                'name' => mysqli_real_escape_string($cf_obj->db_connection, $shopinfo['name']), /* e.g example */
                'shop' => $shop, /* e.g example.myshopify.com */
                'host' => $thehost,
                'domain' => $shopinfo['domain'],
                'token' => $token,
                'owner' => $shopinfo['shop_owner'],
                'shop_plan' => $shopinfo['plan_name'],
                'money_format' => mysqli_real_escape_string($cf_obj->db_connection, strip_tags($shopinfo['money_format'])),
                'currency' => $shopinfo['currency'],
                'address1' => $shopinfo['address1'],
                'address2' => $shopinfo['address2'],
                'city' => $shopinfo['city'],
                'country_name' => $shopinfo['country_name'],
                'phone' => $shopinfo['phone'],
                'province' => $shopinfo['province'],
                'zip' => $shopinfo['zip'],
                'timezone' => $shopinfo['timezone'],
                'iana_timezone' => $shopinfo['iana_timezone'],
                'weight_unit' => $shopinfo['weight_unit'],
                'toggle_status' =>$shopinfo['toggle_status']
            );

            $selected_field = '*';
            $where = array('shop' => $shop);
            $is_store_exist = $cf_obj->select_row(TABLE_USER_STORES, $selected_field, $where);
            /* if store already available than */
            $fields = $shop_details;
            /* need to add bcoz its not exist in $shop_details array */
            $fields['status'] = '1';
            $fields['updated_on'] = DATE;
            if (!empty($is_store_exist)) {
                $where = array('shop' => $shop);
                $last_id = $cf_obj->update(TABLE_USER_STORES, $fields, $where);
                $store_user_id = $is_store_exist['store_user_id'];
            } else {
                /* need to add bcoz its not exist in $shop_details array */
                $fields['created_on'] = DATE;
                $store_user_id = $cf_obj->insert(TABLE_USER_STORES, $fields);
            }
            //active this plugin
            $cf_obj->plugin_active_inactive($fields, 1);
            $cf_obj->snippest_insert($shop, $token, $domain, $email);
            header('Location: ' . SITE_USER_URL . '?shop=' . $shop);
            exit;
        }
    } else {
        /* Check store is active or not */
        if (!empty($store_row)) {
            header('Location: ' . SITE_USER_URL . '?shop=' . $shop);
        } else {
            $install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . SHOPIFY_API_KEY . "&scope=" . urlencode(SHOPIFY_SCOPE) . "&redirect_uri=" . urlencode(SITE_PATH);
            header("Location: " . $install_url);
            exit;
        }
    }
} else {
    echo 'Directory access is forbidden.';
    exit;
}
?>
