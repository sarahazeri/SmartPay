# SmartPay
A PHP SDK for SmartPay Payment Gateway

Using SmartPay SDK in your project:

require 'vendor/autoload.php';

use SmartPay\SmartPay;

$smartPay = new SmartPay('YOUR_MERCHANT_ID', 'YOUR_ACCESS_CODE', 'YOUR_WORKING_KEY');


$response = $smartPay->initiatePayment(
    'ORDER12345',
    '10.50',
    'OMR',
    'https://yourwebsite.com/success',
    'https://yourwebsite.com/cancel'
);

echo $response;


$encResponse = 'RECEIVED_ENCRYPTED_RESPONSE';
$decryptedResponse = $smartPay->processResponse($encResponse);
echo $decryptedResponse;

You need a table to store transactions. You can either create it with your desired structure or use the following command to create it:

require 'vendor/autoload.php';

use SmartPay\Database\Migrations\CreateTransactionsTable;

CreateTransactionsTable::up();






SMARTPAY APPLICATION | Merchant Integration Guide
1. About This Document
1.1 Purpose
SmartPay payment integration kit allows merchants to instantly collect payments from their 
users using cards.
The SmartPay payment integration supports a seamless payment experience on your 
platform, while protecting your application from payment complexity.
1.2 Audience
This guide is written for application developers to ease the integration. It requires software 
development skills. You must write code that uses the request and response as described in 
the implementation guide.
1.3 Scope
The scope of this document primarily encompasses the SmartPay integration. However, it 
may undergo iterations over time in order to cater to the requirements or future
enhancements.
2. System Requirements
2.1. Network Requirements
a. SmartPay access should be enabled from merchant site for connectivity.
b. Merchant system should have stable internet connectivity. 
c. Merchant should ensure duplicate request should not be sent from the merchant 
server and system should wait for a request completion.
d. All communication should happen over HTTPS TLS v1.2 protocol only.
2.2. Software Requirements
a. SmartPay integration does not mandate implementation of any specific software 
stack for the merchants.
b. Merchants can deploy their own software stack, which is capable of sending and 
receiving encrypted messages to SmartPay application.
c. Merchant system should be capable for creating POST parameters request and 
response processing.
d. Merchant should maintain session validity of 30 minutes.
e. Merchant should ensure request and response logs are maintained with correct 
timestamps for POST parameters.
f. Data sanity and validity should be checked by the merchant system before 
sending a request and after receiving the response (refer section 9 & 10).
3
SMARTPAY APPLICATION | Merchant Integration Guide
g. Merchant needs to update the SmartPay integration connector in case any 
changes or updated version is rolled.
h. Adherence to industries best practices for information security by the merchant.
3. Integration Method
SmartPay supports collecting payment information using following methods. All methods 
are designed to support a friction less user-experience
3.1. Bank Hosted Integration
Avoid the hassle of developing and managing your own checkout page. Use the 
customizable transaction page provided by SmartPay which enables you to collect payments 
easily.
3.2. iFrame Integration
Fastest and easiest way to enable payments on your website. SmartPay iframe checkout is a 
pre-configured form, which validates the payment data, allows user to store card 
information to expedite the payment process in future. SmartPay iFrame checkout also 
handles PCI compliance.
3.3. Merchant Hosted Integration
Merchants can build a custom checkout form to collect order and payment information and 
pass the same to SmartPay server for payment processing. Merchants should be PCI DSS 
compliant for this integration.
4. Data Security and Authentication
SmartPay application is designed to enable merchant to interact securely using AES 
encryption methodology. Once merchant is onboarded following credentials will be 
shared which will be used for integrating with SmartPay application:
a. Merchant ID - This is a unique numeric merchant identifier which is generated by 
the SmartPay application.
b. Access Code - This code works as a static token for exchanging information along 
with encrypted request.
c. Working Key - This is 32 characters alphanumeric shared encryption key that shall 
be used for encrypting/decrypting the messages.
Merchant should ensure above mentioned sensitive onboarding details are securely stored 
on the server. All require access checks shall be applied by the merchant.
4
SMARTPAY APPLICATION | Merchant Integration Guide
5. POST Parameters
SmartPay application accepts POST request in an encrypted format to ensure 
shall validate the data before forming the encrypted message string. All 
required checks and balances should be applied by the merchant system 
before creating the encrypted request and after receiving the encrypted 
response. This includes validating the request and response parameter 
mentioned u/s 7 & 9 of this document.
Once the message is successfully formed it should be encrypted using secure symmetric 
working key.
Following two mandatory parameters should be POSTED to SmartPay Application in POST 
format via web redirect:
Request Parameter Data Type Description
access_code ALPHANUMERIC Unique merchant token generate upon 
successful onboarding on SmartPay.
encRequest ALPHANUMERIC Encrypted parameter string.
Upon receipt of POST request from merchant system, SmartPay Application shall validate 
and process the request. Post processing the request the application shall generate 
appropriate response in data string format and encrypt the response using merchant’s 
working key.
Following is the response sent by the SmartPay Application to the merchant’s redirect URL 
mentioned in the encrypted request received:
Response Parameter Data Type Description
order_id ALPHANUMERIC As passed in request and should be revalidated post decryption on below 
encrypted response by the merchant.
encResponse ALPHANUMERIC Encrypted response string.
Merchant system should log the encrypted response received and decrypt the encrypted 
data using working key. Response message validation checks should be applied by the 
merchant system before rendering/confirming the services to the end user.
6. Secure Vault
SmartPay enables the merchants to store card information of their customers for future 
transactions. 
SmartPay needs an additional parameter to identify your customer. You can send unique ID 
of the customer in your system at the time of initiating the transaction. This unique ID can 
5
SMARTPAY APPLICATION | Merchant Integration Guide
be a customer ID, mobile number or an email ID. SmartPay will store the card information 
against the customer identifier.
If there are any payment options stored against a customer identifier, SmartPay will retrieve 
and load the same for customer to make the payment. Customer will also have an option of 
paying through a new card.
7. Request String Parameters
Following are the request parameters which can be used:
Parameter Name Description Type
merchant_id Merchant Id is a unique identifier generated by SmartPay for each 
activated merchant. Integer
order_id
This ID is used by merchants to identify the order. Ensure that you 
send a unique id with each request. 
SmartPay will not check the uniqueness of this order id. As it 
generates a unique payment reference number for each order
sent by the merchant.
Alphanum(25)
amount Order amount NUMERIC 
(10,3)
currency Currency for processing transaction. Char(3)
cancel_url
Merchant response handler endpoint where encrypted response 
shall be posted in case if transaction is cancelled by 
the customer on transaction page.
varchar(200)
redirect_url Merchant response handler endpoint where encrypted response 
shall be posted post transaction processing. varchar(200)
customer_identifier Unique identifier for the customer in case Vault feature is enabled 
and card details needs to be saved post customer consent. Integer
si_type Only if MOTO transaction is enabled for the merchant. Value of this 
field shall always be ONDEMAND.
varchar(20)
si_mer_ref_no SI merchant reference number. Only if MOTO transaction is enabled 
for the merchant. varchar(30)
billing_address Billing Address of the customer Varchar(315)
billing_city Billing City of the customer Varchar(30)
billing_state Billing State of the customer Varchar(30)
billing_zip Billing Zip varchar(15)
billing_country Billing country of the customer varchar(50)
billing_email Email address of the customer varchar(70)
merchant_param1
This parameter can be used for sending additional information about 
the transaction. SmartPay will map this parameter in the 
reconciliation report.
varchar(500)
merchant_param2
This parameter can be used for sending additional information about 
the transaction. SmartPay will map this parameter in the 
reconciliation report.
Varchar(500)
merchant_param3
This parameter can be used for sending additional information about 
the transaction. SmartPay will map this parameter in the 
reconciliation report.
varchar(500)
merchant_param4 This parameter can be used for sending additional information about 
the transaction. SmartPay will map this parameter in the varchar(500)
6
SMARTPAY APPLICATION | Merchant Integration Guide
reconciliation report.
merchant_param5
This parameter can be used for sending additional information about 
the transaction. SmartPay will map this parameter in the 
reconciliation report.
varchar(500)
card_number Customer card number – 16 digits. varchar(400)
expiry_month Card expiry month in MM format. varchar(400)
expiry_year Card expiry year in YY format. varchar(400)
cvv_number CVV/CVC value at the back of the card. varchar(400)
Note - Card details will ONLY be applicable for Merchant Hosted Integration whereas, SI 
parameters will be allowed in all integration provided the same is enabled from the backend 
ADMIN panel on the merchant account and MOTO MID has been allocated.
*: Characters allowed: Alphabet (A-Z), (a-z), Numbers, # (Hash), Circular Brackets, /(slash), dot, -
(hyphen).
Parameters in italic are optional whereas, Customer ID is conditional in case if vault feature is 
enabled and card details needs to be saved.
8. Request POST Parameters
Merchant system shall form a request string on basis of integration type. Each request 
parameter name and its value should be delimited using ampersand (&). Following is sample 
for request string creation with mandatory parameters:
STEP 1: String Formation
reg_id=12345&order_id=TR261A173&currency=OMR&amount=1.001
STEP 2: String Encryption
encRequest = AES encryption of string generated in STEP 1 using working key shared against 
the Registration ID (reg_id) post onboarding. AES Encryption and Decryption detail is 
mentioned in Appendices.
STEP 3: Payment Initiation
Upon successful request encryption, following two parameters should be POSTED to 
SmartPay application:
a. access_code
b. encRequest
7
SMARTPAY APPLICATION | Merchant Integration Guide
9. Response String Parameters
SmartPay application shall process the payment request and generate appropriate 
merchant response string. The response string shall be encrypted using the
shared working key and passed in encResponse parameter either to Redirect URL or 
Cancel URL depending upon end user action on SmartPay transaction page.
Following section describes the string parameters which shall be received on basis of 
transaction status:
Parameter Name Description Type
order_id As received in the request Alphanum(25)
tracking_id
Unique payment reference number generated by SmartPay for 
each order. Bigint
bank_ref_no Reference number generated by issuing bank e.g. approval code. varchar(70)
order_status Current status of an order in SmartPay system. varchar(15)
failure_message Remarks, if any
payment_mode Payment mode for the processed transaction. Varchar(20)
card_name Card type – Mastercard / Visa varchar(100)
status_code Status code of the transaction NUMERIC(10)
status_message Authorization status remark received from the bank. varchar(200)
amount Order amount numeric(13,3)
billing_name Billing Name of the customer varchar(100)
billing_address Billing Address of the customer varchar(315)
billing_city Billing City of the customer varchar(30)
billing_state Billing State of the customer varchar(30)
billing_zip Billing Zip varchar(15)
billing_country Billing country of the customer varchar(50)
billing_email Email address of the customer varchar(70)
merchant_param1 Merchant defined field 1 varchar(500)
merchant_param2 Merchant defined field 2 varchar(500)
merchant_param3 Merchant defined field 3 varchar(500)
merchant_param4 Merchant defined field 4 varchar(500)
merchant_param5 Merchant defined field 5 varchar(500)
vault If vault is enabled then response would be returned as Y/N Char(3)
customer_card_id
A unique card token value generated by the smartpay system 
against the saved card Integer
merchant_param6 Masked card number Varchar(400)
10.Response POST Parameters
SmartPay Application shall POST the encrypted response either on Redirect URL or Cancel 
URL as per user behaviour on the transaction page. Merchant system shall receive following 
as POST parameters are response: 
a. order_id
b. encResponse
Following is sample for response handling: 
8
SMARTPAY APPLICATION | Merchant Integration Guide
STEP 1: Parameter Acceptance
Merchant system should fetch both the parameters sent by SmartPay Application and 
save/log it as per their implementation logic.
STEP 2: Response Decryption
The encrypted response should be decrypted using the secure working key received post 
successful onboarding. 
STEP 3: Response Validation and Business Logic
All response parameters should be verified, validated and saved in merchant system before 
providing the services to the end customer. In case of any ambiguous response or no 
response, merchant may use Order Status API.
11.Appendices
11.1 AES Encryption
SmartPay Application uses AES 256 Encryption (AES-256-GCM) for request/response 
message exchange. To enable easy integration, SmartPay provides encryption/decryption 
library files for following languages: 
a) Java
b) PHP
c) Python
In case if merchant is using any other programming language, AES Encryption can be 
developed on basis of following information: 
a) Algorithm Used: AES 256/GCM/NoPadding
b) Initialization Vector (IV): 16 bytes
c) Tag Length: 16 bytes
Following is a sample step-wise output:
i. Assuming Working Key as WK = “secretKey12345678998765432112345”
ii. Create Initialization Vector (IV) of random 16 bytes.
iii. Generate secret key by converting Working Key (in step i) into bytes.
iv. Get Cipher instance of as AES 256 GCM with No Padding (RAW). 
v. Generate encrypted value of Plain Text. 
9
SMARTPAY APPLICATION | Merchant Integration Guide
vi. Convert the encrypted value to Hex and ensure TAG value is incorporated. i.e. 
byteToHex(CIPHER+TAG). Some programming languages like Java by default append TAG 
value so, in such cases directly convert the cipher text to hex value.
vii. Append the Hex value of IV create on Step ii.
viii. Pass the encrypted value to SmartPay Application. So, the encrypted value should be 
like following:
ENC_REQUEST = Hex(IV) + Hex(Cipher+Tag)
ix. Following is sample step wise output for help in programming
PLAIN_TEXT = “Hello World”
WORKING_KEY = “secretKey12345678998765432112345”
RANDOM_IV_HEX = “d99c56fd684cb7ca1a3cd2c73037482c”
CIPHERTEXT (with Tag) HEX VALUE = 
1d0476a660c926b2d5ff80a7813fdc91d41e5bedebe9c4499899cd
ENC_REQUEST (IV HEX VALUE + CIPHERTEXT HEX VALUE ): 
d99c56fd684cb7ca1a3cd2c73037482c1d0476a660c926b2d5ff80a7813fdc91d41e5bedebe9c449989
9cd
x. Similarly, for decryption extract first 32 characters of encrypted string.
xi. Convert the extracted string from Hex to Byte. This will give IV in bytes. 
xii. This IV can be used for initializing cipher block and carrying out decryption.
11.2 Order Status
Order Status Status Description
Invalid Transaction request invalidated at SmartPay Application.
Initiated Transaction initiation request received from Merchant.
Aborted Transaction cancelled by the customer on SmartPay.
Awaited Transaction sent to PG connector and awaiting response.
Success Transaction successful and should be captured pending.
Failure Transaction processing failed.
Timeout Request timed out and merchant should trigger transaction inquiry.
