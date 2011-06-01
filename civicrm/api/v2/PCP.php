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
 * create / update a PCP entry
 *
 * @param  array   $params           (reference ) input parameters - need id and contribution_type_id
 *
 * @return array (reference )        PCP / PCPBlock result
 * @static void
 * @access public
 */
function &civicrm_pcp_create( &$params ) {
    _civicrm_initialize( );
	
    $values  = array( );
   
    require_once 'CRM/Contribute/BAO/PCP.php';

    $pcpBlock = CRM_Utils_Array::value( 'pcpBlock', $params, TRUE );
	// true creates/updates a pcpBlock, false creates/updates a users pcp
	$pcp = CRM_Contribute_BAO_PCP::add( $params, $pcpBlock );	
	
    if ( is_a( $pcp, 'CRM_Core_Error' ) ) {
        return civicrm_create_error( ts( 'Failed to create pcp' ) );
    }

    _civicrm_object_to_array($pcp, $pcpArray);
    
    return $pcpArray;
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
function &civicrm_pcp_get( &$params ) {
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

    $pcp  =& civicrm_pcp_search( $params );
    

    if ( count( $pcp ) != 1 &&
         ! CRM_Utils_Array::value( 'returnFirst', $params ) ) {
			if( ! CRM_Utils_Array::value( 'returnAll', $params ) ) {
				$error = civicrm_create_error( ts( '%1 pcps matching input params', array( 1 => count( $pcp ) ) ),
                                       $pcp );
				return $error;
			} else {
				$return = array(
							'count' => count( $pcp ), 
							'results' => $pcp
						);			
				
				return $return;
				
			}
	}

    if ( civicrm_error( $pcp ) ) {
        return $pcp;
    }

    $pcp = array_values( $pcp );
    return $pcp[0];
}


function &civicrm_pcp_search( &$params ) {

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

    $pcpBlock = CRM_Utils_Array::value( 'pcpBlock', $params, TRUE );
	
    require_once 'CRM/Contribute/BAO/Query.php';
    require_once 'CRM/Contact/BAO/Query.php'; 
    
	if ( empty( $returnProperties ) ) {
		if ( $pcpBlock ) {
			$returnProperties = array(
									'id' 					=> 1,
									'entity_table'  		=> 1,
									'supporter_profile_id'  => 1,
									'is_approval_needed'    => 1,
									'is_tellfriend_enabled' => 1,
									'tellfriend_limit'      => 1,
									'link_text'             => 1,
									'is_active'             => 1,
									'notify_email'          => 1
								);			
		} else {
			$returnProperties = array(  
									'id'                    => 1,
									'contact_id'            => 1,
									'title'                 => 1,
									'intro_text'            => 1,
									'page_text'             => 1,
									'donate_link_text'      => 1,
									'contribution_page_id'  => 1,
									'is_thermometer'        => 1,
									'is_honor_roll'         => 1,
									'goal_amount'           => 1,
									'referer'               => 1,
									'is_active'             => 1,
									'currency'              => 1
								);			
		}
	}

    $newParams =& CRM_Contact_BAO_Query::convertFormValues( $params);
    $query = new CRM_Contact_BAO_Query( $newParams, $returnProperties, null );
	
	// I'm having a problem understanding how this part works, so I'm just working around it for now...
	/*
    list( $select, $from, $where ) = $query->query( );
    
    $sql = "$select $from $where";  
	*/
	$where = '';
	
	$query->_element = array_merge($query->_element, $returnProperties);
	if ( !$pcpBlock ) {
		$key = 'id';
		$sql = "select * from civicrm_pcp";
		
/*
		if( CRM_Utils_Array::value( 'id', $params) ) {
			$sql .= " where id=" . mysql_real_escape_string($params['id']);
		}
*/
		foreach($params as $tmp_key => $value) {
			if(isset($returnProperties[$tmp_key])) {
				$where.= sprintf(" and %s='%s'", mysql_real_escape_string($tmp_key), mysql_real_escape_string($value));
			}
		}
	} else {
		$key = 'entity_id';
		$sql = "select * from civicrm_pcp_block";
		if( CRM_Utils_Array::value( 'entity_id', $params) ) {
			$sql .= " where entity_id=" . mysql_real_escape_string($params['entity_id']);
		}
	}
	if(!empty($where)) {
		$sql .= " where " . substr($where, 4);
	}
	// end work around
	
	if ( ! empty( $sort ) ) {
        $sql .= " ORDER BY $sort ";
    }

    $sql .= " LIMIT $offset, $rowCount ";
    $dao =& CRM_Core_DAO::executeQuery( $sql, CRM_Core_DAO::$_nullArray );
    
    $pcp = array( );
    while ( $dao->fetch( ) ) {
		$pcp[$dao->$key] = $query->store( $dao );
    }

    $dao->free( );

    return $pcp;

}

function &civicrm_pcp_sync_titles( &$params ) {

    if( ! is_array($params) ) {
        return civicrm_create_error( 'Params need to be of type array!' );
    }
	
    civicrm_verify_mandatory ($params,null,array ('title', 'contribution_page_id'));
    
    $sql = sprintf("update civicrm_pcp set title='%s' where contribution_page_id='%d'", mysql_real_escape_string($params['title']), mysql_real_escape_string($params['contribution_page_id']));
    $dao =& CRM_Core_DAO::executeQuery( $sql, CRM_Core_DAO::$_nullArray );

    $ret = array('rows_updated' => $dao->affectedRows( ));
    
    return $ret;
}

function civicrm_pcp_search_owner(&$params) {

    if( ! is_array($params) ) {
        return civicrm_create_error( 'Params need to be of type array!' );
    }
	
    civicrm_verify_mandatory ($params,null,array ('search'));
    
    require_once 'api/v2/Contact.php';
    
    $search_term = CRM_Utils_Array::value( 'search', $params, TRUE );
	
    $query = "SELECT DISTINCT(cc.id) as id, pcp.title, CONCAT_WS( ' :: ', display_name, sort_name, email, phone, street_address, city, ste.name, coy.name ) as data FROM civicrm_pcp pcp left join civicrm_contact cc on ( pcp.contact_id = cc.id ) LEFT JOIN civicrm_email eml ON ( cc.id = eml.contact_id AND eml.is_primary = 1 )  LEFT JOIN civicrm_phone phe ON ( cc.id = phe.contact_id AND phe.is_primary = 1 )  LEFT JOIN civicrm_address sts ON ( cc.id = sts.contact_id AND sts.is_primary = 1)   LEFT JOIN civicrm_state_province ste ON ( sts.state_province_id = ste.id  )   LEFT JOIN civicrm_country coy ON ( sts.country_id = coy.id  ) 
WHERE pcp.title like '%" . mysql_real_escape_string($search_term) . "%' or sort_name LIKE '%" . mysql_real_escape_string($search_term) . "%' or display_name LIKE '%" . mysql_real_escape_string($search_term) . "%' ORDER BY sort_name";

    $dao = CRM_Core_DAO::executeQuery( $query );

    while ( $dao->fetch( ) ) {
	// we don't care about our very strange data string
	//$civi_matches[$dao->id] = $dao->data;
	
	$params			= array(
	    'contact_id'	=> $dao->id
	);
	$contact 		= civicrm_contact_get($params);
	$contact		= array_pop($contact);
        $contact['pcp_title']   = $dao->title;
	$civi_matches[$dao->id]	= $contact;
    }
    $results = array('Results' => $civi_matches);
    return $results;
}
