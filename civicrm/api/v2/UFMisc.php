<?php

include_once('UFGroup.php');

/**
 *  Converts from civi ID to a Drupal (or whatever platform) id
 */
function civicrm_uf_misc_id_get($params) {
	civicrm_verify_mandatory ($params,null,array ('contact_id'));
	$contactID = CRM_Utils_Array::value( 'contact_id', $params );
	
	return civicrm_uf_id_get($contactID);
}

function civicrm_uf_misc_match_id_get($params) {
	civicrm_verify_mandatory ($params,null,array ('uf_id'));
	$ufID = CRM_Utils_Array::value( 'uf_id', $params );

	
	return civicrm_uf_match_id_get($ufID);
}