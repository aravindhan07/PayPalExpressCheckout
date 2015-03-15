<?php 
	$nvp_url = 'https://api-3t.sandbox.paypal.com/nvp';
	//Check if the amount is set
	if(!isset($_GET['amount'])){
		echo "<p style = 'margin: 30'> There seems to be an error with the transaction 
		<a href = '/paypalwebsite.php'>Click Here</a> reinitiate transaction. </p><br>";
	}
	
	else{
	//constructing the http request for checking out
	$do_checkout_request = array(
						  'USER' => 'aravindhan30081993-facilitator_api1.gmail.com',
						  'PWD' => '66K2ATNFZFPL5ESK',
						  'SIGNATURE' => 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ACWGrslcCpXTXNzAacX4tegrdaZZ',
						  'VERSION' => 121.0,
						  'TOKEN' => $_GET['token'],
						  'METHOD' => 'GetExpressCheckoutDetails',
						  'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
						  'PAYMENTREQUEST_0_AMT' => $_GET['amount'],
						  'PAYERID' => $_GET['PayerID'],
						  'METHOD' => 'DoExpressCheckoutPayment'
						  );
	
	$http_do_checkout = array(
   'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
       'method'  => 'POST',
        'content' => http_build_query($do_checkout_request),
     ));

	$checkout_response = file_get_contents($nvp_url, false, stream_context_create($http_do_checkout));
	parse_str($checkout_response, $formatted_checkout_response);
	//If the transaction succeeded then print out the billing details (if the same token is used again a warning may appear but the transaction is not redone
	if($formatted_checkout_response['ACK'] == "Success" || $formatted_checkout_response['ACK'] == "SuccessWithWarning" ){
		echo "<p style = 'margin: 30'> Payment successful !! <a href = '/paypalwebsite.php'>Click Here</a> to buy more! </p><br>";
		//Constructing the request for getting checkout details
		$get_details_request = array(
							  'USER' => 'aravindhan30081993-facilitator_api1.gmail.com',
							  'PWD' => '66K2ATNFZFPL5ESK',
							  'SIGNATURE' => 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ACWGrslcCpXTXNzAacX4tegrdaZZ',
							  'VERSION' => 121.0,
							  'TOKEN' => $_GET['token'],
							  'METHOD' => 'GetExpressCheckoutDetails'
							  );

		$http_get_details = array(
	   'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		   'method'  => 'POST',
			'content' => http_build_query($get_details_request),
		 ));

		$get_details_response = file_get_contents($nvp_url, false, stream_context_create($http_get_details));
		parse_str($get_details_response, $formatted_details);
		//if successful, print the details
		if($formatted_details['ACK'] == "Success"){
		?>
			<p style = 'margin: 30'> The payment has been made on behalf of 
			<?php echo $formatted_details['PAYMENTREQUEST_0_SHIPTONAME']?>
			and is billed to  
			<?php echo $formatted_details['PAYMENTREQUEST_0_SHIPTOSTREET']?>
			<?php echo $formatted_details['PAYMENTREQUEST_0_SHIPTOCITY'] - $formatted_details['PAYMENTREQUEST_0_SHIPTOZIP']?>
			<?php echo $formatted_details['PAYMENTREQUEST_0_SHIPTOSTATE']?>
			<?php echo $formatted_details['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME']?>
			</p><br>
			<?php
		}
		else{
			"<p style = 'margin: 30'> There seems fetching the transaction details. However the transaction was successful. 
		<a href = '/paypalwebsite.php'>Click Here</a> to buy other products. </p><br>";
		}
		
	}
	//Transaction did not succeed
	else{
		echo "<p style = 'margin: 30'> There seems to be a problem processing your request. 
		<a href = '/paypalwebsite.php'>Click Here</a> to renitiate your transaction. </p><br>";
	}
	
}	
?>	
	


