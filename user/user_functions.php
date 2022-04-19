<?php

/* include common function */
include_once (ABS_PATH . '/include/common_function.php');

class User_functions extends common_function {

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     */
    public function __construct($shop = '') {
        /* call parent's (common_function) constructor */
        parent::__construct($shop);
    }

    /* When undefined method call that time this function will run */

    public function __call($method, $args) {
        return true;
    }

    public function remove_code($storeuserid = 0, $curshop = '') {
       
        $store_user_id = $this->store_user_id;
        
        if($storeuserid) {
            $store_user_id = $storeuserid;
        }
        
        $response = array('result' => 'fail', 'msg' => 'Something went wrong');
        if (isset($store_user_id) && is_numeric($store_user_id) && $store_user_id > 0) {
            //by Shoaib actually in Post data_key is not coming then I will get the data_key from database of this current user
            $datakey = ((!empty($_POST['data_key'])) ? $_POST['data_key'] : "" );
            $token = '';
            $shop = '';
            
            
            if (empty($datakey)) {
                $selected_field = 'data_key, token, shop';
                $where = array('store_user_id' => $store_user_id);
                $user_store = $this->select_row(TABLE_USER_STORES, $selected_field, $where);
                if (!empty($user_store)) {
                    $datakey = $user_store['data_key'];
                    $token = $user_store['token'];
                    $shop = $user_store['shop'];
                }
            }
            
            
            //$script = '<script data-key="' . $datakey . '" data-name="CookieXray" src="https://cmp.seersco.com/script/cb.js" type="text/javascript"></script>';
            //fix by Shoaib for scripts added in old way start
            $script = '<script(.*?)src="https://cmp.seersco.com/script/cb.js"(.*?)>(.*?)</script>';
            $script2 = '<script(.*?)src="https://seersco.com/script/cb.js"(.*?)>(.*?)</script>';
            
            $themes = $this->prepare_api_condition(array('themes'), array('role' => 'main'), 'GET', '0', '', $curshop);
            if (!empty($themes['body']['themes'])) {
                
            $theme_id = $themes['body']['themes'][0]['id'];

            $url_param_arr = array('asset' => array('key' => 'layout/theme.liquid'));
                $theme_responce = $this->prepare_api_condition(array('themes', $theme_id, 'assets'), $url_param_arr, 'GET', '0', '', $curshop);
            $theme_value = $theme_responce['body']['asset']['value'];

                //$html = str_replace($script, "", $theme_value);
                $html = preg_replace('#'. $script . '#is', '', $theme_value);
                $html = preg_replace('#'. $script2 . '#is', '', $html);
            $url_param_arr = array('asset' => array('key' => 'layout/theme.liquid', 'value' => $html));
                $theme_update = $this->prepare_api_condition(array('themes', $theme_id, 'assets'), $url_param_arr, 'PUT', '0', '', $curshop);
                
            }
            // old way fix end.
            
            // ----- new way remove tags start ---------
            $arrsrc = ['https://cmp.seersco.com/script/cb.js', 'https://seers-application-assets.s3.amazonaws.com/scripts/cbattributes.js?key=' . $datakey . '&name=CookieXray'];
            $cbattrjspath = 'https://seers-application-assets.s3.amazonaws.com/scripts/cbattributes.js';


            //get all avialable tags
            $allscriptags = $this->prepare_api_condition(array('script_tags'), array(), 'GET', '0', $token, $shop);

            //print_r($allscriptags);

            if(!empty($allscriptags['body']) && !empty($allscriptags['body']['script_tags'])) {

                foreach ($allscriptags['body']['script_tags'] as $thescript) {

                    if (strcasecmp($thescript['src'], $arrsrc[0]) === 0) {
                        //remove the script
                        $scriptdel = $this->prepare_api_condition(array('script_tags', $thescript['id']), array(), 'DELETE', '0', $token, $shop);
                    } else if (stripos($thescript['src'], $cbattrjspath) !== false && strcasecmp($thescript['src'], $arrsrc[1]) !== 0) {
                        //remove the script
                        $scriptdel = $this->prepare_api_condition(array('script_tags', $thescript['id']), array(), 'DELETE', '0', $token, $shop);
                    }
                }


            }
            // ----- new way remove tags end ---------
            
            
            $response = array('result' => 'success', 'msg' => 'Code Remove successfully.');
        }
        return $response;
    }



    public function change_appStatus(){

        $cf_obj = new common_function();
        $shop = isset($_POST['shop']) ? $_POST['shop'] : $_GET['shop'];
        $store_user_id = $this->store_user_id;
        $data_status = $_POST['data_status'];
        $user_domain   = $_POST['user_name'];
        $user_email    = $_POST['user_email'];

        $user_key      = $_POST['data_key'];

        if($data_status=='true')
        {
            $data_status =  '1';
        }else{
            $data_status =  '0';
        }


        $selected_field = '*';
        $where = array('shop' => $shop,'store_user_id' => $store_user_id);
        $is_store_exist = $cf_obj->select_row(TABLE_USER_STORES, $selected_field, $where);

        $already_toggle_status = $is_store_exist['toggle_status'];
        $domain = $is_store_exist['domain'];
        $email  = $is_store_exist['email'];
        $token  = $is_store_exist['token'];
        $shop   = $is_store_exist['shop'];
        if(!empty($is_store_exist)){
            // SEND API CALL
            $data = array(
                'domain' => $domain,
                'user_domain' => $domain,
                'email' => $email,
                'user_email' => $email,
                'secret' => '$2y$10$9ygTfodVBVM0XVCdyzEUK.0FIuLnJT0D42sIE6dIu9r/KY3XaXXyS',
                'platform' => 'shopify',
                'status'=>$data_status,
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
           
            //var_dump($result);
            //exit;
            //by Shoaib in reponse there is no element of banner_enable
            // {"key":"$2y$10$ZtDil0sCM95w..QVVdqOielWh7YRbySFOPDgzR.K4iukb5I7ewF4G","status":0,"message":"success"}

          //$banner_status = $result['banner_enable'];
          $banner_status = ((!empty($result['banner_enable'])) ? $result['banner_enable'] : ((isset($result['status'])) ? $result['status'] : $already_toggle_status ) );
          
          if(!empty($result['key'])){
                $user_key = $result['key']; 
           }else{
                $user_key = $_POST['data_key'];
           }
          
            //$banner_status = '1';
           $jsonresponse = array('result' => 'fail', 'msg' => 'Something went wrong');
                
            if($banner_status=='1'){
                $jsonresponse = array('result' => 'success', 'key'=>$user_key, 'msg' => "<p><span class ='banner-tick'></span>Banner is enabled on your store. <br> <span style='margin-left:18px;'></span>Please refresh your store home page to see the effect.</p>");
                $this->snippest_insert($shop, $token, $domain, $email);
          }else{
              $jsonresponse = array('result' => 'success', 'key'=>$user_key, 'msg' => 'Banner is disabled on your store');
                $this->remove_code();
            }
            /** Update Banner Status */
            $this->updateToogelStatus($cf_obj, $shop, $banner_status,$user_domain,$user_email,$user_key);

            if (!empty($result['message']) && strcasecmp($result['message'], 'success') === 0)
            {
                return $jsonresponse;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateToogelStatus($cf_obj, $shop, $banner_status,$user_domain,$user_email,$user_key){


        $shop_details = array(
            'status'=>'1',
            'updated_on'=>DATE,
            'toggle_status'=>$banner_status,
            'domain'=>$user_domain,
            'email'=>$user_email,
            'data_key'=>$user_key
        );

        $where = array('shop' => $shop,'store_user_id' => $this->store_user_id);
        $last_id = $cf_obj->update(TABLE_USER_STORES, $shop_details, $where);
}
}
