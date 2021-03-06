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
require_once 'CRM/Core/Payment.php';

/**
 * create a contribution page
 *
 * @param  array   $params           (reference ) input parameters - need id and contribution_type_id
 *
 * @return array (reference )        contributionType id and other fields
 * @static void
 * @access public
 */
function &civicrm_payment_dodirectpayment( &$params ) {
    _civicrm_initialize( );

    if( ! is_array($params) ) {
        return civicrm_create_error( 'Params need to be of type array!' );
    }	
	
    civicrm_verify_mandatory ($params,null,array ('mode', 'component'));
    
    $mode               = CRM_Utils_Array::value( 'mode', $params );
    $component          = CRM_Utils_Array::value( 'component', $params ); // 'Contribute'
    unset($params['mode']);
    unset($params['component']);
    $paymentProcessor   = $params['paymentProcessor'];
    unset($params['paymentProcessor']);

    $form               = NULL; // todo add that in later
    
    $payment            =& CRM_Core_Payment::singleton($mode, $paymentProcessor, $form);
    $result             =& $payment->doDirectPayment( $params );
    
    if(is_object($result) && get_class($result) == 'CRM_Core_Error') {
        return civicrm_create_error($result->_errors[0]['code']);
    }

    return $result;
}

