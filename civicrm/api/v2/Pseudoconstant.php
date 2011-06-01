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
 * File for the CiviCRM APIv2 PseudoConstant functions
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
require_once 'CRM/Contribute/PseudoConstant.php';

function civicrm_pseudoconstant_countries($params) {
    return civicrm_pseudoconstant_country($params);
}

function civicrm_pseudoconstant_country($params) {
    $countries = CRM_Core_PseudoConstant::country();

    $return = array('countries' => $countries);    

    return $return;
}

function civicrm_pseudoconstant_stateProvince($params) {
    $stateProvince = CRM_Core_PseudoConstant::stateProvince();

	$return = array('stateProvince' => $stateProvince);
    
    return $return;
}

function civicrm_pseudoconstant_countryIsoCode($params) {
    $countries = CRM_Core_PseudoConstant::countryIsoCode();

	$return = array('countries' => $countries);
    
    return $return;
}

function civicrm_pseudoconstant_paymentprocessor($params) {
    $paymentprocessor = CRM_Core_PseudoConstant::paymentprocessor();

    $return = array('paymentprocessor' => $paymentprocessor);    

    return $return;
}