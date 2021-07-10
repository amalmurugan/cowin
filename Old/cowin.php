<?php

$ch = curl_init();
#$curdate = '29-06-2021';
$tomorrow = new DateTime('tomorrow');
$curdate = $tomorrow->format('d-m-Y');
echo 'Checking slot for ' . $curdate . PHP_EOL;

$curlConfig = [
    CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/findByDistrict?district_id=296&date=' . $curdate,
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
foreach ($slots->sessions as $session) {
    // var_dump($session);die;
    // if ($session->available_capacity >  0 or $session->available_capacity_dose1 > 0 or $session->available_capacity_dose2 > 0) {
    if ($session->available_capacity_dose1 > 0) {
        if ('COVISHIELD' === $session->vaccine || 'COVAXIN' === $session->vaccine) {
//            if ('COVAXIN' === $session->vaccine) {
            if (18 === $session->min_age_limit) {
                // echo PHP_EOL;
                // echo $session->fee_type.'-> PINCode '.$session->pincode.' -'.$session->name.PHP_EOL;
                // echo '______________________________________________________________________________'.PHP_EOL;
                // echo PHP_EOL;
                // echo $session->available_capacity.'-DOSE1===>'.$session->available_capacity_dose1.'-DOSE2===>'.$session->available_capacity_dose2.PHP_EOL;
                // echo '______________________________________________________________________________'.PHP_EOL;

                echo PHP_EOL;
                echo $session->fee_type . '-> PINCode ' . $session->pincode . ' -' . $session->name . ' - ' . $session->available_capacity . '-DOSE1===>' . $session->available_capacity_dose1 . '-DOSE2===>' . $session->available_capacity_dose2 . PHP_EOL;
                echo '______________________________________________________________________________' . PHP_EOL;
                $playsound = 1;
            }
        }
    }
}
if (1 == $playsound) {
    exec(sprintf('%s > %s 2>&1 & echo $! >> %s', 'aplay buzzer.wav', 'cowin-output.log', 'cowin-pid.log'));
    // shell_exec("nohup aplay buzzer.wav&");
    // shell_exec("spd-say 'Vaccine Vaccine Vaccine'");
}

/*
["center_id"]=>
int(171354)
["name"]=>
string(27) "Malayinkeezh Taluk Hospital"
["address"]=>
string(33) "Malayinkil Thqh  Malayinkeezh P O"
["state_name"]=>
string(6) "Kerala"
["district_name"]=>
string(18) "Thiruvananthapuram"
["block_name"]=>
string(8) "Vilappil"
["pincode"]=>
int(695571)
["from"]=>
string(8) "09:00:00"
["to"]=>
string(8) "16:00:00"
["lat"]=>
int(8)
["long"]=>
int(77)
["fee_type"]=>
string(4) "Free"
["session_id"]=>
string(36) "6be492cd-4715-441f-bd1a-836f0e2f6f05"
["date"]=>
string(10) "26-06-2021"
["available_capacity"]=>
int(0)
["available_capacity_dose1"]=>
int(0)
["available_capacity_dose2"]=>
int(0)
["fee"]=>
string(1) "0"
["min_age_limit"]=>
int(45)
["vaccine"]=>
string(10) "COVISHIELD"
["slots"]=>
array(4) {
  [0]=>
  string(15) "09:00AM-11:00AM"
  [1]=>
  string(15) "11:00AM-01:00PM"
  [2]=>
  string(15) "01:00PM-03:00PM"
  [3]=>
  string(15) "03:00PM-04:00PM"
}

*/
