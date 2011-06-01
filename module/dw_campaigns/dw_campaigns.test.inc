<?php

function dw_campaigns_test($arg) {
global $user;
//$user = user_load('96');
$user = user_load('98');
die;
    drupal_set_message('This is a friendly test', 'status');
	echo "heyhey";    
    $_COOKIE['forcetype'] = $arg;
    
    die;
    global $user;
    $params = array(
        'email'    => '456@jemeznet.com',
        'returnFirst'   => 1
    );
    var_dump(_dw_civicrm_contact_get($params));

    $user = user_load(array('uid' => '17'));
    die;
    $form = drupal_get_form('dw_campaigns_user_notify_settings_form');
    echo $form;
    die;
    _pathauto_include();
    node_pathauto_bulkupdate();
    
    $campaignPath = '2011-kentucky-derby';

    $campaignPath = '2011-kentucky-derby';
    $path               = drupal_lookup_path('source', 'content/' . $campaignPath);
    var_dump($path);
    die;
    //list(,$campaignId)  = explode('/', $path);
    var_dump(node_load($arg));
}
