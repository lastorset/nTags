<?
// SOFTWARE NAME: nTags
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.


// Fetch objects with particular keywords or combinations of keywords.
// Based on eZ publish's version.

class nTagsFunctions
{

	//Returns an array( 'result' => array( 'keyword' => keyword, 'link_object' => node_id );
    //By default fetchKeyword gets a list of (not necessary unique) nodes and respective keyword strings
    //Search keyword provided in $alphabet parameter.
    //Setting $includeDuplicates parameter to false makes fetchKeyword('Skien') to return just
    //one entry for objects with multiple keywords.

	// Only exact matches are supported when using multiple keywords.
	// Last parameter indicates whether to use "and" or "or".

	// TODO: Proper function documentation. Improvements: automatically trim strings, include subtree
    static public function fetchKeyword( $alphabet,
                           $classid,
                           $offset,
                           $limit,
                           $owner = false,
                           $sortBy = array(),
                           $parentNodeID = false,
                           $includeSubtree = false,
                           $includeDuplicates = true)
    {
		if (!is_array( $alphabet ))
		{
			$alphabets = array( trim( $alphabet ) );
		}
		else
		{
			$alphabets = array_map( "trim", $alphabet );
		}
		unset( $alphabet );

        $classIDArray = array();
        if ( is_numeric( $classid ) )
        {
            $classIDArray = array( $classid );
        }
        else if ( is_array( $classid ) )
        {
            $classIDArray = $classid;
        }

        $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( true, false );
        $limitation = false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

        $db_params = array();
        $db_params['offset'] = $offset;
        $db_params['limit'] = $limit;

        $keywordNodeArray = array();
        $lastKeyword = '';

        $db = eZDB::instance();

        $sortingInfo = array();
        $sortingInfo['attributeFromSQL'] = ', ezcontentobject_attribute a1';
        $sortingInfo['attributeWhereSQL'] = '';
        $sqlTarget = 'ezcontentobject_tree.node_id';

        if ( is_array( $sortBy ) && count ( $sortBy ) > 0 )
        {
            switch ( $sortBy[0] )
            {
                case 'keyword':
                case 'name':
                {
                    $sortingString = '';
                    if ( $sortBy[0] == 'name' )
                        $sortingString = 'ezcontentobject.name';
                    elseif ( $sortBy[0] == 'keyword' )
                        $sortingString = 'ezkeyword.keyword';

                    $sortOrder = true; // true is ascending
                    if ( isset( $sortBy[1] ) )
                        $sortOrder = $sortBy[1];
                    $sortingOrder = $sortOrder ? ' ASC' : ' DESC';
                    $sortingInfo['sortingFields'] = $sortingString . $sortingOrder;
                } break;
                default:
                {
                    $sortingInfo = eZContentObjectTreeNode::createSortingSQLStrings( $sortBy );

                    if ( $sortBy[0] == 'attribute' )
                    {
                        // if sort_by is 'attribute' we should add ezcontentobject_name to "FromSQL" and link to ezcontentobject
                        $sortingInfo['attributeFromSQL']  .= ', ezcontentobject_name, ezcontentobject_attribute a1';
                        $sortingInfo['attributeWhereSQL'] .= ' ezcontentobject.id = ezcontentobject_name.contentobject_id AND';
                        $sqlTarget = 'DISTINCT ezcontentobject_tree.node_id';
                    }
                    else // for unique declaration
                        $sortingInfo['attributeFromSQL']  .= ', ezcontentobject_attribute a1';

                } break;
            }
        }
        else
        {
            $sortingInfo['sortingFields'] = 'ezkeyword.keyword ASC';
        }
        $sortingInfo['attributeWhereSQL'] .= " a1.version=ezcontentobject.current_version
                                             AND a1.contentobject_id=ezcontentobject.id AND";

        //Adding DISTINCT to avoid duplicates,
        //check if DISTINCT keyword was added before providing clauses for sorting.
        if ( !$includeDuplicates && substr( $sqlTarget, 0, 9) != 'DISTINCT ' )
        {
            $sqlTarget = 'DISTINCT ' . $sqlTarget;
        }

        $sqlOwnerString = is_numeric( $owner ) ? "AND ezcontentobject.owner_id = '$owner'" : '';
		if ( is_numeric( $parentNodeID ))
		{
			if ( $includeSubtree )
			{
				$parentNodeIDString = is_numeric( $parentNodeID ) ? "AND ezcontentobject_tree.path_string LIKE '%/$parentNodeID/%'" : '';
			}
			else
			{
				$parentNodeIDString = is_numeric( $parentNodeID ) ? "AND ezcontentobject_tree.parent_node_id = '$parentNodeID'" : '';
			}
		}
		else
		{
			$parentNodeIDString = '';
		}

        $sqlClassIDString = '';
        if ( is_array( $classIDArray ) and count( $classIDArray ) )
        {
            $sqlClassIDString = 'AND ezkeyword.class_id IN (' . $db->implodeWithTypeCast( ',', $classIDArray, 'int' ) . ')';
        }

		$sqlMatching = "";
		foreach ( $alphabets as $i => $alphabet )
		{
			$sqlMatching .= "ezkeyword.keyword = '$alphabet'";
			if ( $i != count( $alphabets ) - 1 )
			{
				$sqlMatching .= " OR ";
			}
		}

		$query = "SELECT $sqlTarget
				  FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass
					   $sortingInfo[attributeFromSQL]
					   $sqlPermissionChecking[from]
				  WHERE
				  $sortingInfo[attributeWhereSQL]
				  ($sqlMatching)
				  $showInvisibleNodesCond
				  $sqlPermissionChecking[where]
				  $sqlClassIDString
				  $sqlOwnerString
				  $parentNodeIDString
				  AND ezcontentclass.version=0
				  AND ezcontentobject.status=".eZContentObject::STATUS_PUBLISHED."
				  AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
				  AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
				  AND ezcontentclass.id = ezcontentobject.contentclass_id
				  AND a1.id=ezkeyword_attribute_link.objectattribute_id
				  AND ezkeyword_attribute_link.keyword_id = ezkeyword.id ORDER BY {$sortingInfo['sortingFields']}";

        $keyWords = $db->arrayQuery( $query, $db_params );

        $trans = eZCharTransform::instance();

        foreach ( $keyWords as $keywordArray )
        {
            $nodeID = $keywordArray['node_id'];
            $nodeObject = eZContentObjectTreeNode::fetch( $nodeID );

            if ( $nodeObject != null )
            {
				$NodeArray[] = $nodeObject;
            }
        }
        return array( 'result' => $NodeArray );
    }

    static public function fetchKeywordCount( $alphabet,
                                $classid,
                                $owner = false,
                                $parentNodeID = false,
                                $includeDuplicates = true )
    {
		if (!is_array( $alphabet ))
		{
			$alphabets = array( trim($alphabet) );
		}
		else
		{
			$alphabets = array_map( "trim", $alphabet );
		}
		unset( $alphabet );

        $classIDArray = array();
        if ( is_numeric( $classid ) )
        {
            $classIDArray = array( $classid );
        }
        else if ( is_array( $classid ) )
        {
            $classIDArray = $classid;
        }

        $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( true, false );
        $limitation = false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

        $db = eZDB::instance();

        $alphabet = $db->escapeString( $alphabet );

        $sqlOwnerString = is_numeric( $owner ) ? "AND ezcontentobject.owner_id = '$owner'" : '';
		if ( is_numeric( $parentNodeID ))
		{
			if ( $includeSubtree )
			{
				$parentNodeIDString = is_numeric( $parentNodeID ) ? "AND ezcontentobject_tree.path_string LIKE '%/$parentNodeID/%'" : '';
			}
			else
			{
				$parentNodeIDString = is_numeric( $parentNodeID ) ? "AND ezcontentobject_tree.parent_node_id = '$parentNodeID'" : '';
				
			}
		}
		else
		{
			$parentNodeIDString = '';
		}

        $sqlClassIDs = '';
        if ( $classIDArray != null )
        {
            $sqlClassIDs = 'AND ezkeyword.class_id IN (' . $db->implodeWithTypeCast( ',', $classIDArray, 'int' ) . ') ';
        }

          //will use SELECT COUNT( DISTINCT ezcontentobject.id ) to count object only once even if it has
          //several keywords started with $alphabet.
          //COUNT( DISTINCT fieldName ) is SQL92 compliant syntax.
            $sqlToExcludeDuplicates = ' DISTINCT';
        // composing sql for matching tag word, it could be strict equiality or LIKE clause dependent of $strictMatching parameter.
		$sqlMatching = "";
		foreach ( $alphabets as $i => $alphabet )
		{
			$sqlMatching .= "ezkeyword.keyword LIKE '$alphabet'";
			if ( $i != count( $alphabets ) - 1 )
			{
				$sqlMatching .= " OR ";
			}
		}

        $query = "SELECT COUNT($sqlToExcludeDuplicates ezcontentobject.id) AS count
                  FROM ezkeyword, ezkeyword_attribute_link,ezcontentobject_tree,ezcontentobject,ezcontentclass, ezcontentobject_attribute
                       $sqlPermissionChecking[from]
                  WHERE ($sqlMatching)
                  $showInvisibleNodesCond
                  $sqlPermissionChecking[where]
                  $sqlClassIDs
                  $sqlOwnerString
                  $parentNodeIDString
                  AND ezcontentclass.version=0
                  AND ezcontentobject.status=".eZContentObject::STATUS_PUBLISHED."
                  AND ezcontentobject_attribute.version=ezcontentobject.current_version
                  AND ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id
                  AND ezcontentobject_attribute.contentobject_id=ezcontentobject.id
                  AND ezcontentobject_tree.contentobject_id = ezcontentobject.id
                  AND ezcontentclass.id = ezcontentobject.contentclass_id
                  AND ezcontentobject_attribute.id=ezkeyword_attribute_link.objectattribute_id
                  AND ezkeyword_attribute_link.keyword_id = ezkeyword.id";

        $keyWords = $db->arrayQuery( $query );
        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

        return array( 'result' => $keyWords[0]['count'] );
    }
 
}
?>
