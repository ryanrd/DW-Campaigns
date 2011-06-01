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
function &civicrm_contributionsoft_get( &$params ) {
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

    $contributionSoft  =& civicrm_contributionsoft_search( $params );
    
	if ( count( $contributionSoft ) != 1 &&
		 ! CRM_Utils_Array::value( 'returnFirst', $params ) ) {

			if( ! CRM_Utils_Array::value( 'returnAll', $params) ) {
		 
				$error = civicrm_create_error( ts( '%1 contributionSoft matching input params', array( 1 => count( $contributionSoft ) ) ),
									   $contributionSoft );
			} else {
				$return = array(
							'count' => count( $contributionSoft ), 
							'results' => $contributionSoft
						);
						
				return $return;
				
			}
		return $error;
	}

    if ( civicrm_error( $contributionSoft ) ) {
        return $contributionSoft;
    }

    $contributionSoft = array_values( $contributionSoft );
    return $contributionSoft[0];
}

function civicrm_contributionsoft_add( &$params ) {
	return civicrm_contributionsoft_create( $params );
}

function civicrm_contributionsoft_create( &$params ) {
	_civicrm_initialize( );
	require_once 'CRM/Contribute/BAO/Contribution.php';
    
	$softContribution = CRM_Contribute_BAO_Contribution::addSoftContribution( $params );
	_civicrm_object_to_array($softContribution, $softContributionArray);
	
	return $softContributionArray;
}


function &civicrm_contributionsoft_search( &$params ) {

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
		//CRM_Contribute_BAO_Query::defaultReturnProperties( CRM_Contact_BAO_Query::MODE_CONTRIBUTIONPAGE );
		$returnProperties = array ( 
								'id' 					=> 1,
								'contribution_id' 		=> 1,
								'contact_id' 			=> 1,
								'amount'				=> 1,
								'pcp_id'				=> 1,
								'pcp_display_in_roll'	=> 1,
								'pcp_roll_nickname'		=> 1,
								'pcp_personal_note'		=> 1,
								'currency'				=> 1
							);
	}

    $newParams =& CRM_Contact_BAO_Query::convertFormValues( $params );
    $query = new CRM_Contact_BAO_Query( $newParams, $returnProperties, null );
	
	// I'm having a problem understanding how this part works, so I'm just working around it for now...
	/*
    list( $select, $from, $where ) = $query->query( );
    
    $sql = "$select $from $where";  
	*/
	$query->_element = array_merge($query->_element, $returnProperties);
	
	
	$sql = "select * from civicrm_contribution_soft";
	$extra = '';
	
	$possible = array('id', 'contribution_id', 'contact_id', 'pcp_id');
	foreach($possible as $key) {
		if ( CRM_Utils_Array::value( $key , $params) ) {
			$extra .= " and $key='" . mysql_real_escape_string($params[$key]) . "'";
		}
	}
	
	if (!empty($extra)) {
		$extra = substr($extra, 4);
		$sql .= " where $extra";
	}
	// end work around
	
	if ( ! empty( $sort ) ) {
        $sql .= " ORDER BY $sort ";
    }

    $sql .= " LIMIT $offset, $rowCount ";
    $dao =& CRM_Core_DAO::executeQuery( $sql, CRM_Core_DAO::$_nullArray );
    
    $contributionPage = array( );
    while ( $dao->fetch( ) ) {
		$contributionSoft[$dao->id] = $query->store( $dao );
    }

    $dao->free( );

    return $contributionSoft;

}

