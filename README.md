# PayPal Express Checkout Web App
The application consists of two web pages written in PHP. The calls to the PayPal API are made through HTTP post requests to the PayPal's server which returns NVP data. 

All requests made to the PayPal API return an ACK denoting if the transaction was successful. The scripts verify this after each API call before proceeding.

The two scripts powering the web application are

- ###  paypalwebsite

    The script in this webpage lists the products the user can choose from. The script then proceeds to perform a request to call the PayPal server's SetExpressCheckout method. This method is invoked using the API username, API password and the API signature attached to the facilitator(business account) account. Upon successfull invocation of this method, the script receives the token for the checkout. Upon reception of the token, the user is redirected to login to their buyer's account.
    
    Upon successful authorization from the buyer, the paypal server redirects the user to the SUCCESS_URL which was specified in the call to SetExpressCheckout which also has the payment amount appended to it as a GET parameter. 
    
    If the user cancel's the transaction, the paypal server redirects the user to the CANCEL_URL which was specified in the call to SetExpressCheckout. 
    
- ###  dotransaction
This script receives the token and the PayerID corresponding to the transaction. It also receives payment amount as a GET parameter from the paypalwebsite script. It uses the facilitor's API details, the token, the PayerID and the payment amount to perform the transaction. If successful, an API call to the PayPal server is made to retrieve the billing/shipping details and these details are presented to the user.
