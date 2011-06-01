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
 * File for the CiviCRM APIv2 ContributionType functions
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

function &civicrm_contributiontype_create( &$params ) {
	return civicrm_contributiontype_add( $params );
}

/**
 * Add a contribution type
 *
 * @param  array   $params           (reference ) input parameters
 *
 * @return array (reference )        contributionType id and other fields
 * @static void
 * @access public
 */
function &civicrm_contributiontype_add( &$params ) {
    _civicrm_initialize( );
	
    $values  = array( );
   
    require_once 'CRM/Contribute/BAO/ContributionType.php';

    $ids     = array( );
    if ( CRM_Utils_Array::value( 'id', $params ) ) {
        $ids['contributionType'] = $params['id'];
		unset($params['id']); // we don't need it in params
	}
	
    $contributionType = CRM_Contribute_BAO_ContributionType::add( $params, $ids );
    if ( is_a( $contributionType, 'CRM_Core_Error' ) ) {
        return civicrm_create_error( ts( 'Failed to add contribution type' ) );
    }

    _civicrm_object_to_array($contributionType, $contributeArray);
    
    return $contributeArray;
}

