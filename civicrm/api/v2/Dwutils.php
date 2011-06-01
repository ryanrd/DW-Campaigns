<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.3                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2010                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 * File for the CiviCRM APIv2 Derby/Walk Drupal Module functions
 *
 * @package CiviCRM_APIv2
 * @subpackage API_Contact
 *
 * @copyright CiviCRM LLC (c) 2004-2011
 * @version $Id$
 *
 */

/**
 * Include utility functions
 */
require_once 'api/v2/utils.php';
require_once 'CRM/Utils/Rule.php';
require_once 'CRM/Contact/BAO/Contact.php';

function civicrm_dwutils_get_deduped_contact($params) {
    
    require_once 'CRM/Dedupe/Finder.php';
    $dedupeParams = CRM_Dedupe_Finder::formatParams($params, 'Individual');
    $ids = CRM_Dedupe_Finder::dupesByParams($dedupeParams, 'Individual');
    
    $fields = array();
    foreach($params as $key => $value) {
	if(strncmp($key, 'fields_', 7) == 0) {
	    $new_key = str_replace('fields_', '', $key);
	    $fields[$new_key] = 1;
	    unset($params[$key]);
	}
    }
    
    print_r($fields);
    
    $contact_id  = CRM_Utils_Array::value( 0, $ids );
    $contact_result =& CRM_Contact_BAO_Contact::createProfileContact( $params, $fields, $contact_id );
    return $contact_result;
}