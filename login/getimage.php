<?php ob_start();

/****************************************************************************
 * DRBImageVerification
 * http://www.dbscripts.net/imageverification/
 * 
 * Copyright © 2007 Don Barnes 
 ****************************************************************************/
 
	require_once('challenge.php');
	
	// Create challenge string
	createChallengeString();
	
	// Output challenge image to user
	outputChallengeImage();

?>
