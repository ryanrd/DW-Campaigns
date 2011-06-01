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

/**
 * create a contribution page
 *
 * @param  array   $params           (reference ) input parameters - need id and contribution_type_id
 *
 * @return array (reference )        contributionType id and other fields
 * @static void
 * @access public
 */
function &civicrm_contributionpage_create( &$params ) {
    _civicrm_initialize( );
	
    $values  = array( );
   
    require_once 'CRM/Contribute/BAO/ContributionPage.php';

    $ids     = array( );
    if ( CRM_Utils_Array::value( 'id', $params ) ) {
    
	}
	
    $contributionPage = CRM_Contribute_BAO_ContributionPage::create( $params );
    if ( is_a( $contributionPage, 'CRM_Core_Error' ) ) {
        return civicrm_create_error( ts( 'Failed to create contributionPage' ) );
    }

    _civicrm_object_to_array($contributionPage, $contributeArray);
    
    return $contributeArray;
}


/**
 * Retrieve a specific participant, given a set of input params
 * If more than one matching participant exists, return an error, unless
 * the client has requested to return the first found contact
 *
 * @param  array   $params           (reference ) input parameters
 *
 * @return array (reference )        array of properties, if error an array with an error id and error message
 * @static void
 * @access public
 */
function &civicrm_contributionpage_get( &$params ) {
    _civicrm_initialize( );
 
    $values = array( );
    if ( empty( $params ) ) {
        $error = civicrm_create_error( ts( 'No input parameters present' ) );
        return $error;
    }
    
    if ( ! is_array( $params ) ) {
        $error = civicrm_create_error( ts( 'Input parameters is not an array' ) );
        return $error;
    }

    $contributionPage  =& civicrm_contributionpage_search( $params );
    
	if ( count( $contributionPage ) != 1 &&
		 ! CRM_Utils_Array::value( 'returnFirst', $params ) ) {

			if( ! CRM_Utils_Array::value( 'returnAll', $params) ) {
		 
				$error = civicrm_create_error( ts( '%1 contributionPages matching input params', array( 1 => count( $contributionPage ) ) ),
									   $contributionPage );
			} else {
				$return = array(
							'count' => count( $contributionPage ), 
							'results' => $contributionPage
						);
						
				return $return;
				
			}
		return $error;
	}

    if ( civicrm_error( $contributionPage ) ) {
        return $contributionPage;
    }

    $contributionPage = array_values( $contributionPage );
    return $contributionPage[0];
}

function &civicrm_contributionpage_search( &$params ) {

    if( ! is_array($params) ) {
        return civicrm_create_error( 'Params need to be of type array!' );
    }
    
    $inputParams      = array( );
    $returnProperties = array( );
    $otherVars = array( 'sort', 'offset', 'rowCount' );
    
    $sort     = null;
    $offset   = 0;
    $rowCount = 25;
    foreach ( $params as $n => $v ) {
        if ( substr( $n, 0, 7 ) == 'return.' ) {
            $returnProperties[ substr( $n, 7 ) ] = $v;
        } elseif ( in_array ( $n, $otherVars ) ) {
            $$n = $v;
        } else {
            $inputParams[$n] = $v;
        }
    }

    require_once 'CRM/Contribute/BAO/Query.php';
    require_once 'CRM/Contact/BAO/Query.php'; 
    
	if ( empty( $returnProperties ) ) {
		$returnProperties = array(
                                'id'                        => 1,
                                'title'                     => 1,
                                'intro_text'                => 1,
                                'contribution_type_id'      => 1,
                                'payment_processor_id'      => 1,
                                'is_credit_card_only'       => 1,
                                'is_monetary'               => 1,
                                'is_recur'                  => 1,
                                'recur_requency_unit'       => 1,
                                'is_recur_interval'         => 1,
                                'is_pay_later'              => 1,
                                'pay_later_text'            => 1,
                                'pay_later_receipt'         => 1,
                                'is_allow_other_amount'     => 1,
                                'default_amount_id'         => 1,
                                'min_amount'                => 1,
                                'max_amount'                => 1,
                                'goal_amount'               => 1,
                                'thankyou_title'            => 1,
                                'thankyou_footer'           => 1,
                                'is_for_organization'       => 1,
                                'is_email_receipt'          => 1,
                                'receipt_from_name'         => 1,
                                'receipt_from_email'        => 1,
                                'cc_receipt'                => 1,
                                'bcc_receipt'               => 1,
                                'receipt_text'              => 1,
                                'is_active'                 => 1,
                                'footer_text'               => 1,
                                'amount_block_is_active'    => 1,
                                'honor_block_is_active'     => 1,
                                'honor_block_title'         => 1,
                                'honor_block_text'          => 1,
                                'start_date'                => 1,
                                'end_date'                  => 1,
                                'created_id'                => 1,
                                'created_date'              => 1,
                                'currency'                  => 1
                            );
	}

    $newParams =& CRM_Contact_BAO_Query::convertFormValues( $params);
    $query = new CRM_Contact_BAO_Query( $newParams, $returnProperties, null );
	
	// I'm having a problem understanding how this part works, so I'm just working around it for now...
	/*
    list( $select, $from, $where ) = $query->query( );
    
    $sql = "$select $from $where";  
	*/
	$query->_element = array_merge($query->_element, $returnProperties);
	
	
	$sql = "select * from civicrm_contribution_page";
	if( CRM_Utils_Array::value( 'id', $params) ) {
		$sql .= " where id=" . mysql_real_escape_string($params['id']);
	}
	// end work around
	
	if ( ! empty( $sort ) ) {
        $sql .= " ORDER BY $sort ";
    }

    $sql .= " LIMIT $offset, $rowCount ";
    $dao =& CRM_Core_DAO::executeQuery( $sql, CRM_Core_DAO::$_nullArray );
    
    $contributionPage = array( );
    while ( $dao->fetch( ) ) {
		$contributionPage[$dao->id] = $query->store( $dao );
    }

    $dao->free( );

    return $contributionPage;

}

