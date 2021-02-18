<?php


if (empty($_GET))
    $user_id = "27760789-7a7c-4a0f-bf12-fc57498a45f6";
else
    $user_id = $_GET['acref'];


function redirect_to_success($id)
{
    exit(header("Location: https://carplus.co.uk/quote/success/?acref=" . $id, true, 301));
}

function redirect_to_declined($id)
{
    exit(header("Location: http://carplus.co.uk/quote/declined?" . $id, true, 301));
}

function redirect_to_approved()
{
    exit(header("Location: http://carplus.co.uk/quote/approved", true, 301));
}

function get_data()
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.autoconvert.co.uk/application/submit',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
		"VehicleType":"Car",
		"LoanAmount":20,
		"LoanTerm":20,
		"Products":[
			{
			"Name":"Cannot be null",
			"VAT":null,
			"NetValue":null,
			"CategoryId":null,
			"PaymentType":null
			}
		],
	"FinanceDetails":{
		"Deposit":null,
		"PartExchangeValue":null,
		"FDA":null,
		"EstimatedAnnualMileage":null,
		"Settlement":null,
		"EnquiryType":null,
		"FinanceTypeId":null
		},
	"BankDetails":{
		"SortCode":null,
		"AccountNumber":null,
		"AccountName":null,
		"TimeAtBankYears":null,
		"TimeAtBankMonths":null,
		"BranchName":null,
		"BankName":null,
		"BankAddress":null
		},
	"Vehicles":[
		{
			"Registration":null
		}
	],
	"Applicants":[
		{
			"Email":"test@test.co.uk",
			"Forename":"test",
			"Surname":"test"
		}
	]
	}',
        CURLOPT_HTTPHEADER => array(
            'X-ApiKey: 19ff541e-b45e-4ac5-8cda-dc457868211b',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response, true);
}

function check_for_approved()
{
    global $user_id;

    $cURLConnection = curl_init();
    curl_setopt($cURLConnection, CURLOPT_URL, 'https://api.autoconvert.co.uk/application/' . $user_id);
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
        'X-ApiKey: 19ff541e-b45e-4ac5-8cda-dc457868211b',
        'Content-Type: application/json'
    ));
    $result = curl_exec($cURLConnection);
    curl_close($cURLConnection);

    $jsonArrayResponse = json_decode($result, true);

    $data = $jsonArrayResponse['Enquiry']['EnquirySubStatusName'];

    switch ($data) {
        case '1. Approved':
            redirect_to_success($jsonArrayResponse['Enquiry']['Reference']);
            return true;
            break;

        case '2. Rejected':
            redirect_to_declined($jsonArrayResponse['Enquiry']['Reference']);
            return true;
            break;

        default:
            return false;
    }

    /*  if ($approved == ) {

          // return true;
          // print_r($jsonArrayResponse['Enquiry']['EnquirySubStatusName']);
          // redirect_to_approved();
      }
      if ($rejected == $jsonArrayResponse['Enquiry']['EnquirySubStatusName']) {

          // return true;
          // print_r($jsonArrayResponse['Enquiry']['EnquirySubStatusName']);
          // redirect_to_approved();
      }*/
}

function main()
{
    while (true) {
        check_for_approved();
        print_r("not approved");
        sleep(2);
    }
    // $start_time=date('s');
    // $end_time=date('s');
    // while( abs($start_time-$end_time) < 40 ){
    // 	check_for_approved();
    // 	$end_time=date('s');
    // }

    // $jsonArrayResponse = get_data();
    // if($jsonArrayResponse['Accepted']){
    // 	print_r('Success');
    // 	redirect_to_success($jsonArrayResponse['Reference']);
    // }else{
    // 	print_r('Failure');
    // 	redirect_to_declined($jsonArrayResponse['Reference']);
    // }
}

// redirect_to_success("27760789-7a7c-4a0f-bf12-fc57498a45f6");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
main();


?>

</body>
</html>
