<?php

$dist_value = $_POST["district_name"];
$vaccine = $_POST["vaccine"];
$fee_type = $_POST["fee_type"];
$available_capacity_dose = $_POST["available_capacity_dose"];
$pincode = $_POST["pincode"];
$min_age_limit = $_POST["min_age_limit"];


$ch = curl_init();
$tomorrow = new DateTime('tomorrow');
$curdate = $tomorrow->format('d-m-Y');
//$curdate = '03-07-2021';


$curlConfig = [
    CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/findByDistrict?district_id=' . $dist_value . '&date=' . $curdate,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'accept: application/json',
        'Accept-Language: hi_IN',
    ],
];
curl_setopt_array($ch, $curlConfig);
$result = curl_exec($ch);
curl_close($ch);
$slots = json_decode($result);
$playsound = 0;
//echo '<h2>Checking slot for ' . $curdate . '</h2>';
echo '<table border="1" style="width:100%">';
echo '<th>Sl No.</th>';
echo '<th>Minimum Age</th>';
echo '<th>Fee Type</th>';
echo '<th>Vaccine</th>';
echo '<th>Dose 1</th>';
echo '<th>Dose 2</th>';
echo '<th>Session</th>';
echo '<th>Center Name</th>';
echo '<th>Pincode</th>';
$index = 1;
foreach ($slots->sessions as $session) {
    if (!$vaccine || $vaccine == $session->vaccine) {
        if (!$fee_type || $fee_type == $session->fee_type) {
            if ((!$available_capacity_dose && ($session->available_capacity_dose1 > 1 || $session->available_capacity_dose2 > 1)) || (($available_capacity_dose == 1 && $session->available_capacity_dose1 > 1) || ($available_capacity_dose == 2 && $session->available_capacity_dose2 > 1))) {
//            if ((!$available_capacity_dose && ($session->available_capacity_dose1 > 1 || $session->available_capacity_dose2 > 1)) || ($available_capacity_dose && $session->available_capacity_dose . $available_capacity_dose = $available_capacity_dose && $session->available_capacity_dose . $available_capacity_dose> 1)) {
                if (!$pincode || $pincode == $session->pincode) {
                    if (!$min_age_limit || $min_age_limit == $session->min_age_limit) {
                        echo '<tr>';
                        echo '<td>' . $index++ . '</td>';
                        echo '<td>' . $session->min_age_limit . '</td>';
                        echo '<td>' . $session->fee_type . '</td>';
                        echo '<td>' . $session->vaccine . '</td>';
                        echo '<td>' . $session->available_capacity_dose1 . '</td>';
                        echo '<td>' . $session->available_capacity_dose2 . '</td>';
//                        echo '<td>D1 :' . $session->available_capacity_dose1 . ' | D2 :' . $session->available_capacity_dose2 . '</td>';
                        echo '<td>' . $session->from . '-' . $session->to . '</td>';
                        echo '<td>' . $session->name . '</td>';
                        echo '<td>' . $session->pincode . '</td>';
                        $playsound = 1;
                        echo '</tr>';
                    }
                }
            }
        }
    }
    $district_name = $session->district_name;
}
echo '</table>';
if (1 == $playsound) {
    exec(sprintf('%s > %s 2>&1 & echo $! >> %s', 'aplay buzzer.wav', 'cowin-output.log', 'cowin-pid.log'));
}
echo '<h2>Checking slot availability for ' . $district_name . ' on ' . $curdate . '</h2>. Refreshing in <span id="timer"></span>s  ';

//
//      ["center_id"]=>
//      int(171354)
//      ["name"]=>
//      string(27) "Malayinkeezh Taluk Hospital"
//      ["address"]=>
//      string(33) "Malayinkil Thqh  Malayinkeezh P O"
//      ["state_name"]=>
//      string(6) "Kerala"
//      ["c"]=>
//      string(18) "Thiruvananthapuram"
//      ["block_name"]=>
//      string(8) "Vilappil"
//      ["pincode"]=>
//      int(695571)
//      ["from"]=>
//      string(8) "09:00:00"
//      ["to"]=>
//      string(8) "16:00:00"
//      ["lat"]=>
//      int(8)
//      ["long"]=>
//      int(77)
//      ["fee_type"]=>
//      string(4) "Free"
//      ["session_id"]=>
//      string(36) "6be492cd-4715-441f-bd1a-836f0e2f6f05"
//      ["date"]=>
//      string(10) "26-06-2021"
//      ["available_capacity"]=>
//      int(0)
//      ["available_capacity_dose1"]=>
//      int(0)
//      ["available_capacity_dose2"]=>
//      int(0)
//      ["fee"]=>
//      string(1) "0"
//      ["min_age_limit"]=>
//      int(45)
//      ["vaccine"]=>
//      string(10) "COVISHIELD"
//      ["slots"]=>
//      array(4) {
//      [0]=>
//      string(15) "09:00AM-11:00AM"
//      [1]=>
//      string(15) "11:00AM-01:00PM"
//      [2]=>
//      string(15) "01:00PM-03:00PM"
//      [3]=>
//      string(15) "03:00PM-04:00PM"

