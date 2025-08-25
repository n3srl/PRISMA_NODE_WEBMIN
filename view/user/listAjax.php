<?php

$aColumns = array('name', 'surname', 'username', 'password', 'level', 'id');
$sIndexColumn = 'id';
/* Indexed column (used for fast and accurate table cardinality) */
/* DB table to use */
$sTable = 'user';
$gaSql['link'] = $db_conn;
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
 * no need to edit below this line
 */
/*
 * Local functions
 */

function fatal_error($sErrorMessage = '') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    die($sErrorMessage);
}

/*
 * MySQL connection
 */
/* if ( ! $gaSql['link'] = mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
  {
  fatal_error( 'Could not open connection to server' );
  }
  if( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
  {
  fatal_error( 'Could not select database ' );
  }
 */
/*
 * Paging
 */
$sLimit = "";
if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
    $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
            intval($_GET['iDisplayLength']);
}
/*
 * Ordering
 */
$sOrder = "";
if (isset($_GET['iSortCol_0'])) {
    $sOrder = "ORDER BY  ";
    for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
        if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
            $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
			" . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
        }
    }
    $sOrder = substr_replace($sOrder, "", -2);
    if ($sOrder == "ORDER BY") {
        $sOrder = "";
    }
}
/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
$sWhere = "";
if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
    $sWhere = "WHERE (";
    for ($i = 0; $i < count($aColumns); $i++) {
        if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
            $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch']) . "%' collate utf8_bin OR " ;
        }
    }
    $sWhere = substr_replace($sWhere, "", -3);
    $sWhere .= ')';
}
/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
    if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
        if ($sWhere == "") {
            $sWhere = "WHERE ";
        } else {
            $sWhere .= " AND ";
        }
        $sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($gaSql['link'], $_GET['sSearch_' . $i]) . "%' collate utf8_bin " ;
    }
}
/*
 * SQL queries
 * Get data to display
 */
mysqli_query($gaSql['link'], "SET CHARACTER SET utf8") or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
$sQuery = "
	SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
	FROM   $sTable
	$sWhere
	$sOrder
	$sLimit
	";
$rResult = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
/* Data set length after filtering */
$sQuery = "
	SELECT FOUND_ROWS()
	";
$rResultFilterTotal = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];
/* Total data set length */
$sQuery = "
	SELECT COUNT(" . $sIndexColumn . ")
	FROM   $sTable
	";
$rResultTotal = mysqli_query($gaSql['link'], $sQuery) or fatal_error('MySQL Error: ' . mysqli_errno($gaSql['link']));
$aResultTotal = mysqli_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];
/*
 * Output
 */
$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);
while ($aRow = mysqli_fetch_array($rResult)) {
    $row = array();
    $id = $aRow['id'];
    for ($i = 0; $i < count($aColumns); $i++) {
        if ($aColumns[$i] == 'id') {

            $row[] = '<a href="/user/edit/' . $id . '"><img src="/img/edit.png" class="UserEdit" width="20" objId="' . $id . '"></a>';
            $row[] = '<img src="/img/remove.png" class="UserRemove" width="20" onclick="defaultDelete(\'/service/user/delete/' . $id . '\')" style="cursor:pointer;">';
        } else if ($aColumns[$i] == 'level') {
            if ($aRow['level'] == UserLevel::ADMIN) {
                $row[] = "Amministratore";
            } else {
                $row[] = "Agente";
            }
        } else if ($aColumns[$i] != ' ') {
            /* General output */
            $row[] = stripslashes($aRow[$aColumns[$i]]);
        }
    }
    $output['aaData'][] = $row;
}
echo json_encode($output);
die;
?>

