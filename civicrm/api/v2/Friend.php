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
 * File for the CiviCRM APIv2 ContributionPage functions
 *
 * @package CiviCRM_APIv2
 * @subpackage API_Contribute
 *
 * @copyright CiviCRM LLC (c) 2004-2011
 * @version $Id: ContributionType.php 31024 2010-12-02 06:14:30Z yashodha $
 *
 */

/**
 * Include utility functions
 */
require_once 'api/v2/utils.php';
require_once 'CRM/Utils/Rule.php';
require_once 'CRM/Contribute/PseudoConstant.php';
require_once 'CRM/Friend/BAO/Friend.php';

/**
 * create a friend
 *
 * @param  array   $params           (reference ) input parameters - need id and contribution_type_id
 *
 * @return (none)
 * @static void
 * @access public
 */
function &civicrm_friend_create( &$params ) {
    _civicrm_initialize( );
	
    $values  = array( );
   
    require_once 'CRM/Contribute/BAO/ContributionType.php';

    $ids     = array( );
    if ( CRM_Utils_Array::value( 'id', $params ) ) {
    
	}
	
    $friend = CRM_Friend_BAO_Friend::create( $params );
    if ( is_a( $friend, 'CRM_Core_Error' ) ) {
        return civicrm_create_error( ts( 'Failed to create Friend' ) );
    }

    _civicrm_object_to_array($friend, $friendArray);
    
    return array('message' => 'Friend Added');
}
