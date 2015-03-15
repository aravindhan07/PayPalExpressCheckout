<div align = "justify" style = "padding: 30;">
<?php
error_reporting(E_ERROR | E_PARSE);
$amount= 0;
if(isset($_POST['chips']))
	$amount += floatval(($_POST['chips']));
if(isset($_POST['protein']))
	$amount += floatval(($_POST['protein']));
if(isset($_POST['milk']))
	$amount += floatval(($_POST['milk']));
if($amount > 0){
	// Constructing the set checkout request
	$post_set_checkout_request = array('USER' => 'aravindhan30081993-facilitator_api1.gmail.com',
						  'PWD' => '66K2ATNFZFPL5ESK',
						  'SIGNATURE' => 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ACWGrslcCpXTXNzAacX4tegrdaZZ',
						  'VERSION' => 121.0,
						  'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
						  'PAYMENTREQUEST_0_AMT' => $amount,
						  'RETURNURL' => "http://xpressco.5gbfree.com/dotransaction.php?amount=$amount",  //the amount can be modified for taxes/exchange rates if needed.
						  'CANCELURL' => 'http://xpressco.5gbfree.com/paypalwebsite.php',
						  'METHOD' => 'SetExpressCheckout');
	$url = 'https://api-3t.sandbox.paypal.com/nvp';
  	$http_set_checkout = array(
   'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
       'method'  => 'POST',
        'content' => http_build_query($post_set_checkout_request),
     ));

	$set_checkout_response = file_get_contents($url, false, stream_context_create($http_set_checkout));
	
	parse_str($set_checkout_response, $formatted_response);
	//If the set_checkout method succeeded we redirect to the user login and wait for the token in our returnurl (dotransaction.php)
	if($formatted_response['ACK'] == "Success"){
		$token = $formatted_response['TOKEN'];
		$url_redirect = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=$token"; ?>
		
		<meta http-equiv="Refresh" content="0; URL=<?php echo $url_redirect?>">
		
		<?php
	}
	
	else{
		echo "<p style = 'margin: 20'> Failed transaction. Please try again\n</p><br>";
	}
}

else{
	echo "<p style = 'margin: 20'> Please select one or more products </p><br>";
}
?>
<!-- The product list -->
<form action = "/paypalwebsite.php" method = "post">

<input style = "margin: 20;" type="checkbox" name="chips" value="4.2">Chips  (S$4.2) <br>
<input style = "margin: 20;" type="checkbox" name="protein" value="80">Protein Shake  (S$80) <br>
<input style = "margin: 20;" type="checkbox" name="milk" value="3">Milk  (S$3) <br>;

<input style = "margin: 20;" type = "image" value = "submit" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif"  > 
</form>
</div>