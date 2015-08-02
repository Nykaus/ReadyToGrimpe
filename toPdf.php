<?php
// lib to write to PDF
require_once(dirname(__FILE__)."/lib/tcpdf/tcpdf.php");
// lib to import existing PDF documents into FPDF
require_once(dirname(__FILE__)."/lib/fpdi/fpdi.php");
include_once(dirname(__FILE__)."/inc/auth.inc");
include_once(dirname(__FILE__)."/inc/PiwikTracker.php");
$piwikTracker = new PiwikTracker(1);

$sitebase = "http://".$_SERVER["HTTP_HOST"].$config["BaseUrl"];
$_SERVER["REQUEST_URI"] = ereg_replace("\?.*","",$_SERVER["REQUEST_URI"]);
if (substr_count($_SERVER["REQUEST_URI"],"/") > 1)
	$_REQUEST["id"] = ereg_replace("/",",",substr($_SERVER["REQUEST_URI"],5));

$param = explode(",",$_REQUEST["id"].",,,,,");

// recuperation du site
$q = "select site_id,site_nom,site_public from topo_site where site_id = '".$param[0]."'";
$a = $Bdd->fetch_all_array($q);
if (sizeof($a) != 1)
	die("site inexistant");
$public = ($a[0]["site_public"] == 1)?true:false;





/* on authetifie par tout les moyens possible */
if(isValidAuthLink($_GET))
{
	$piwikTracker->doTrackEvent('Authentification','Login','success');
}

if (!isAuthUser())
	isValidCookie();

/* si pas authentifier .. 'ca degage' */
if (!isAuthUser() && !$public)
	die("Forbiden (not auth)");

$piwikTracker->setUserId(getAuthUserId()." - ".getAuthUserName());

/* on verifie que le lien n'est pas un lien d'ajout de droit*/
if (isset($_GET["action"]) && $_GET["action"] == "PDF" && isValidAuthLink($_GET))
{
		addUserRight("SIPdf",$param[0]);
		$piwikTracker->doTrackEvent('Authentification','Ajout de droits','success');
}


/* on verifie l'habilitation */
$isHab = false;
/* si admin ou public bien sur gogo */
if (isAdmin() || $public)
{
	$isHab = True;
}
/* si non on verifie que le lien et ok */
else
{
	if (hasRight())
	{
		$r = getUserRight();
		if (!isset($r['SIPdf']) && isset($r['SIRead']) && is_array($r['SIRead']))
			$r['SIPdf']= $r['SIRead'];

		if (isset($r['SIPdf']) && is_array($r['SIPdf']) && in_array($param[0],$r['SIPdf']))
			$isHab = True;
	}

}
if (!$isHab)
	die("Forbiden user");

 
// the template PDF file
$filename = dirname(__FILE__)."/pdf/".$param[0].".pdf";

if (!file_exists($filename))
{
	die("Pdf non present");
}


// initiate FPDI
$pdf = new FPDI();
// set the sourcefile
$pages=$pdf->setSourceFile($filename);


switch($param[1])
{
	case "livret":
	    $piwikTracker->doTrackEvent('PDF',"Mode livret",$param[0]);

	$nb = ceil($pages/2);
	$offset = $pages % 2;

	// import pages one after the other
	for($i = 1; $i <= $nb; $i++) 
	{
		    
		$gp = $i;
		$dp = $pages-$i+1;
		if ($i % 2 == 0)
		{
			$gp = $pages-$i+1;
			$dp = $i;
		}

	    // add a page
	    $pdf->AddPage("L");
	    $tplIdx = $pdf->importPage($gp);
	    // use the imported page and place it at point 10,10 with a width of 100 mm
	    $pdf->useTemplate($tplIdx,150,0,143);
	 
	 
		if ($i==1 && false)
		{
			    $pdf->SetTextColor(22, 142, 247);
			    $pdf->SetXY(150, 120);	    
			    $pdf->Write(2,"[Télécharger en A5 sur A4]",$sitebase."pdf/".$param[0]."/2p1");
			    $pdf->SetXY(150, 130);	    
			    $pdf->Write(2,"[Télécharger en A4]",$sitebase."pdf/".$param[0]);

		}

		if ($i > $offset && $dp != $gp)
		{
			    $tplIdx = $pdf->importPage($dp);
			    // use the imported page and place it at point 10,10 with a width of 100 mm
			    $pdf->useTemplate($tplIdx,0,0,143);
		}

 	        $pdf->SetTextColor(22, 142, 247);
		$pdf->SetXY(0, 150); // X (cm),  Y (cm) 
		// Start Transformation
		$pdf->StartTransform();
		$pdf->Rotate(90);
		//$pdf->Text(0, 0, $_SESSION['user']);
		if (!isAdmin() && !$public)
			$pdf->Write(2,"Téléchagé par ".$_SESSION['user']." le ".date("d-m-Y"));
		$pdf->StopTransform();
	}
	break;

	case "2p1":
	    $piwikTracker->doTrackEvent('PDF',"Mode 2p1",$param[0]);
	    $pdf->AddPage("P");
	    $tplIdx = $pdf->importPage(1);
	    // use the imported page and place it at point 10,10 with a width of 100 mm
	    $pdf->useTemplate($tplIdx);
	    
	    if (false)
	    {
		    $pdf->SetTextColor(22, 142, 247);
		    $pdf->SetXY(10, 200);	    
	            $pdf->Write(2,"[Télécharger en A4]",$sitebase."pdf/".$param[0]);
	    }
        $lastPage = 2;
	// import pages one after the other
	for($i = 2; $i < $pages; $i=$i+2) {
	    // add a page
	    $pdf->AddPage("L");
	    $tplIdx = $pdf->importPage($i);
		$lastPage = $i;
	    // use the imported page and place it at point 10,10 with a width of 100 mm
	    $pdf->useTemplate($tplIdx,0,0,143);
	 
		if ($pages >= $i+1)
		{
			$lastPage = $i+1;
			    $tplIdx = $pdf->importPage($i+1);
			    // use the imported page and place it at point 10,10 with a width of 100 mm
			    $pdf->useTemplate($tplIdx,144,0,143);
		}
	       $pdf->SetTextColor(22, 142, 247);
		$pdf->SetXY(0, 150); // X (cm),  Y (cm) 
		// Start Transformation
		$pdf->StartTransform();
		$pdf->Rotate(90);
		//$pdf->Text(0, 0, $_SESSION['user']);
		if (!isAdmin() && !$public)
			$pdf->Write(2,"Téléchagé par ".$_SESSION['user']." le ".date("d-m-Y"));
		$pdf->StopTransform();
	}
	if ($pages >= $lastPage+1)
		{
			$pdf->AddPage("P");
	    		$tplIdx = $pdf->importPage($pages);
	    		$pdf->useTemplate($tplIdx);
		}
	    
	
	
	break;

	default:
        $piwikTracker->doTrackEvent('PDF',"Mode classic",$param[0]);
	// import pages one after the other
	for($i = 1; $i <= $pages; $i ++) {
	    // add a page
	    $pdf->AddPage();
	    $tplIdx = $pdf->importPage($i);
	    // use the imported page and place it at point 10,10 with a width of 100 mm
	    $pdf->useTemplate($tplIdx);
	 
if ($i==1 && false)
{
	    $pdf->SetTextColor(22, 142, 247);
	    $pdf->SetXY(10, 200);	    
            $pdf->Write(2,"[Télécharger en A5 sur A4]",$sitebase."pdf/".$param[0]."/2p1");

}


	       $pdf->SetTextColor(22, 142, 247);
		$pdf->SetXY(0, 150); // X (cm),  Y (cm) 
		// Start Transformation
		$pdf->StartTransform();
		$pdf->Rotate(90);
		//$pdf->Text(0, 0, $_SESSION['user']);
		if (!isAdmin() && !$public)
			$pdf->Write(2,"Téléchagé par ".$_SESSION['user']." le ".date("d-m-Y"));
		$pdf->StopTransform();
	}
	break;
}
$pdf->Output();
 
 
?>
