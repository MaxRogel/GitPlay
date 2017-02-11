<?php

	require_once 'config.php';

	
	function RequestedLocus($loc, $req){	

	global $servername, $dbname, $con_name, $con_pw;	

	
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



	switch ($req) {
		case "btnFirst":
			$stmt = $conn -> prepare("SELECT Locus_ID FROM vloci ORDER BY YYYY, AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute();
			$nloc = $stmt->fetchColumn();	
			break;
			
		case "btnLast":
			$stmt = $conn -> prepare("SELECT Locus_ID FROM vloci ORDER BY YYYY DESC, AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute();
			$nloc = $stmt->fetchColumn();	
			break;
			
		case "btnNext":
			
			$stmt = $conn -> prepare("SELECT YYYY, AreaName, Locus_no FROM vloci WHERE Locus_ID = :loc");
			$stmt->execute([':loc' => $loc]);
			$res = $stmt->fetch();
			$y  = $res['YYYY'];
			$an = $res['AreaName'];
			$ln = $res['Locus_no'];
			
			$stmt = $conn -> prepare("SELECT Locus_ID FROM vloci WHERE YYYY = :y AND AreaName = :an AND Locus_no > :ln ORDER BY Locus_no ASC LIMIT 1");
			$stmt->execute([':y' => $y, ':an' => $an, ':ln' => $ln]);
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$stmt = $conn -> prepare("SELECT Locus_ID FROM vloci WHERE YYYY = :y AND AreaName > :an ORDER BY AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute([':y' => $y, ':an' => $an]);
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$stmt = $conn -> prepare("SELECT Locus_ID FROM vloci WHERE YYYY > :y ORDER BY YYYY, AreaName, Locus_no ASC LIMIT 1");
			$stmt->execute([':y' => $y]);	
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$nloc = $loc;
			break;
			
		case "btnPrev":
			//twice - put in func
			$stmt = $conn -> prepare("SELECT YYYY, AreaName, Locus_no FROM vloci WHERE Locus_ID = :loc");
			$stmt->execute([':loc' => $loc]);
			$res = $stmt->fetch();
			$y  = $res['YYYY'];
			$an = $res['AreaName'];
			$ln = $res['Locus_no'];	

			$stmt = $conn -> prepare("SELECT Locus_ID FROM vloci WHERE YYYY = :y AND AreaName = :an AND Locus_no < :ln ORDER BY Locus_no DESC LIMIT 1");
			$stmt->execute([':y' => $y, ':an' => $an, ':ln' => $ln]);
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$stmt = $conn -> prepare("SELECT Locus_ID FROM vloci WHERE YYYY = :y AND AreaName < :an ORDER BY AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute([':y' => $y, ':an' => $an]);
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$stmt = $conn -> prepare("SELECT Locus_ID FROM vloci WHERE YYYY < :y ORDER BY YYYY DESC, AreaName DESC, Locus_no DESC LIMIT 1");
			$stmt->execute([':y' => $y]);	
			if ($stmt->rowCount() > 0) {
				$nloc = $stmt->fetchColumn();	
				break;
			} 
			$nloc = $loc;
			break;
		
		case "btnGo":
			$nloc = $loc;
	}
	
	return $nloc;
} 


function GetLocusInfo($nloc){
	
	global $servername, $dbname, $con_name, $con_pw;
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $con_name, $con_pw);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	
	$stmt = $conn -> prepare("SELECT * FROM vloci WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$loc = $stmt->fetch(PDO::FETCH_ASSOC);
	
	//$ptCnt = 0;
	//$pt = 0;
	//------- PT ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM pt WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$ptCnt = $stmt->fetchColumn();
	
	if ($ptCnt > 0) {
		$stmt = $conn -> prepare("SELECT PT_ID, PT_no, Pd_text, Description, Notes, PT_date, Top_Lv, Bot_Lv, Keep FROM pt WHERE Locus_ID = :loc ORDER BY PT_no");
		$stmt->execute([':loc' => $nloc]);
		$pt = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 

	//------- AR ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM var WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$arCnt = $stmt->fetchColumn();	

	if ($arCnt > 0) {
		$stmt = $conn -> prepare("SELECT AR_ID, AR_no, Related_PT_no, Category_Name, Date, Level, Description, Notes FROM var WHERE Locus_ID = :loc ORDER BY AR_no");
		$stmt->execute([':loc' => $nloc]);
		$ar = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 	
/*	
//------- LB ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM ar WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$arCnt = $stmt->fetchColumn();	

	if ($arCnt > 0) {
		$stmt = $conn -> prepare("SELECT AR_ID, AR_no, Related_PT_no, Category, Date, Level, Description, Notes FROM ar WHERE Locus_ID = :loc ORDER BY AR_no");
		$stmt->execute([':loc' => $nloc]);
		$ar = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 	
	
//------- FL ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM ar WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$arCnt = $stmt->fetchColumn();	

	if ($arCnt > 0) {
		$stmt = $conn -> prepare("SELECT AR_ID, AR_no, Related_PT_no, Category, Date, Level, Description, Notes FROM ar WHERE Locus_ID = :loc ORDER BY AR_no");
		$stmt->execute([':loc' => $nloc]);
		$ar = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 	

//------- GS ----------
	$stmt = $conn -> prepare("SELECT COUNT(*) FROM ar WHERE Locus_ID = :loc");
	$stmt->execute([':loc' => $nloc]);
	$arCnt = $stmt->fetchColumn();	

	if ($arCnt > 0) {
		$stmt = $conn -> prepare("SELECT AR_ID, AR_no, Related_PT_no, Category, Date, Level, Description, Notes FROM ar WHERE Locus_ID = :loc ORDER BY AR_no");
		$stmt->execute([':loc' => $nloc]);
		$ar = $stmt->fetchAll(PDO::FETCH_ASSOC);			
	} 		
	
*/	
	
	
	
    //
	if($ptCnt > 0) {
			$data = array('locId' => $nloc, 'loc' => $loc, 'ptCnt' => $ptCnt, 'pt' => $pt);
	} else {
			$data = array('locId' => $nloc, 'loc' => $loc, 'ptCnt' => $ptCnt);		
	}
	//$data = array('locId' => $nloc, 'loc' => $loc, 'pt' => $pt);
	//return(json_encode($res));
	return(json_encode($data));
}
?>
