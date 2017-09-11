<?php
	$input = json_decode(file_get_contents('php://input'), true);

	$address = escapeshellarg($input['to']);
	$amount = escapeshellarg($input['amount']);

	$qrlCharset = "Q1234567890abcdefghijklmnopqrstuvwxyz";
	$status = 0;

	$address = trim($address);
	$amount = floatval(trim($input['amount']));

	// Max send amount is 100
	if($amount > 100) {
		$status = 1;
		$transaction = "Max allowed amount is 100 QRL.";
	}

	if(($input['to'] != "") && ($status != 1)) {
		// Validate QRL Address
		$validAddress = true;

		// Valid charset
		foreach ($address as $char) {
			$pos = strpos($qrlCharset, $char);
			if ($pos === false) {
			    $validAddress = false;
			}
		}

		// Starts with capital Q
		if($address[1] != "Q") {
			$validAddress = false;
		}
		
		// Validate length
		if(strlen($address) != 71) {
			$validAddress = false;
		}

		// Process the transaction if we got a valid QRL Address
		if($validAddress) {
			// All looks good, lets do it.
			exec("/var/www/html/process/send.sh $address $amount", $result, $status);

			// Find txid element
			foreach($result as $item) {
				$pos = strpos($item, ">>> From");
				if($pos !== false) {
					$transaction = $item;
				}
			}

			// Fallback response if we don't capture the TX details
			if($transaction == "") {
				$transaction = "There may have been a problem sending your transaction! Please try again.";
			}
		} else {
			$status = 1;
			$transaction = "$address is an invalid QRL address!";
		}
	}


	// show result
    if($status == 0) {
     	echo '<p><b>Success!</b><br />TX Details: <br />'.$transaction. '</p>';
    } else {
     	echo '<p><b>Withdrawal Failed! Please try again</b><br />'. $transaction. '</p>';
    }

?>