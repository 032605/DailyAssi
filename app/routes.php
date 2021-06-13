<?php
// Routes

$app->get('/', 'App\Controller\HomeController:home')
    ->setName('homepage');

$app->post('/da', 'App\Controller\HomeController:da')
    ->setName('da');



$app->get('/da', 'App\Controller\HomeController:da')
    ->setName('da');

$app->get('/post/{id}', 'App\Controller\HomeController:viewPost')
    ->setName('view_post');

$app->post('/da/forgot_sendmail', 'App\Controller\UCSDController:forgot_sendMail')
    ->setName('forgot_sendmail');

$app->get('/da/signup', 'App\Controller\UCSDController:signUp')
    ->setName('signup');

$app->post('/da/signup2', 'App\Controller\UCSDController:emailcheck')
    ->setName('signup2');

$app->post('/da/signup3', 'App\Controller\UCSDController:signup_success')
    ->setName('signup3');


$app->get('/da/active', 'App\Controller\UCSDController:activationcheck')
    ->setName('activationcheck');

$app->post('/da/deregistration', 'App\Controller\UCSDController:deregistration')
    ->setName('deregistration');

$app->post('/da/deregistration2', 'App\Controller\UCSDController:deregistration_check')
    ->setName('deregistration_check');

$app->get('/da/deregistrationAndroid', 'App\Controller\UCSDController:deregistrationAndroid')
    ->setName('deregistrationAndroid');

$app->post('/da/deregistrationAndroid', 'App\Controller\UCSDController:deregistrationAndroid')
    ->setName('deregistrationAndroid');

$app->post('/da/signin', 'App\Controller\UCSDController:login')
    ->setName('login');

//�¹谡 ����
$app->post('/da/signout', 'App\Controller\UCSDController:signout')
    ->setName('signout');

$app->get('/da/signin', 'App\Controller\UCSDController:login')
    ->setName('login');

$app->post('/da/signin2', 'App\Controller\UCSDController:logincheck')
    ->setName('logincheck');

$app->get('/da/signinAndroid', 'App\Controller\UCSDController:signinAndroid')
    ->setName('signinAndroid');

$app->post('/da/signinAndroid', 'App\Controller\UCSDController:signinAndroid')
    ->setName('signinAndroid');

$app->get('/da/lostpw', 'App\Controller\UCSDController:lostpw')
    ->setName('lostpw');

$app->get('/da/forgotchangepw', 'App\Controller\UCSDController:forgotchange_pw')
    ->setName('forgotchange_pw');

$app->post('/da/forgotchangepw', 'App\Controller\UCSDController:forgotchange_pw')
    ->setName('forgotchange_pw');

$app->post('/da/forgotchangepw2', 'App\Controller\UCSDController:forgotchange_pw2')
    ->setName('forgotchange_pw2');

$app->get('/da/forgotchangepwAndroid', 'App\Controller\UCSDController:forgotchangepwAndroid')
    ->setName('forgotchangepwAndroid');

$app->post('/da/forgotchangepwAndroid', 'App\Controller\UCSDController:forgotchangepwAndroid')
    ->setName('forgotchangepwAndroid');

$app->get('/da/forgotchangepwAndroid1', 'App\Controller\UCSDController:forgotchangepwAndroid1')
    ->setName('forgotchangepwAndroid1');

$app->post('/da/forgotchangepwAndroid1', 'App\Controller\UCSDController:forgotchangepwAndroid1')
    ->setName('forgotchangepwAndroid1');

$app->get('/da/forgotchangepwAndroid2', 'App\Controller\UCSDController:forgotchangepwAndroid2')
    ->setName('forgotchangepwAndroid2');

$app->post('/da/forgotchangepwAndroid2', 'App\Controller\UCSDController:forgotchangepwAndroid2')
    ->setName('forgotchangepwAndroid2');


$app->post('/da/changepw', 'App\Controller\UCSDController:changepw')
    ->setName('changepw');

$app->get('/da/changepwAndroid', 'App\Controller\UCSDController:changepwAndroid')
    ->setName('changepwAndroid');

$app->post('/da/changepwAndroid', 'App\Controller\UCSDController:changepwAndroid')
    ->setName('changepwAndroid');


$app->post('/da/checkedchangepw', 'App\Controller\UCSDController:changepw2')
    ->setName('changepw2');

$app->get('/da/process', 'App\Controller\UCSDController:handleSignUp')
    ->setName('process');

$app->post('/da/profile', 'App\Controller\UCSDController:profile')
    ->setName('profile');

$app->get('/da/hr', 'App\Controller\UCSDController:heartrate')
    ->setName('heartrate');

$app->get('/da/hrjson', 'App\Controller\UCSDController:hrjson')
    ->setName('hrjson');

$app->get('/da/temp', 'App\Controller\UCSDController:temp')
    ->setName('temp');

$app->get('/da/tempjson', 'App\Controller\UCSDController:tempjson')
    ->setName('tempjson');

$app->get('/da/air', 'App\Controller\UCSDController:air')
    ->setName('air');

$app->get('/da/air_json', 'App\Controller\UCSDController:airjson')
    ->setName('air_json');

$app->post('/da/signupAndroid', 'App\Controller\UCSDController:signupAndroid')
    ->setName('signupAndroid');

$app->get('/da/signupAndroid', 'App\Controller\UCSDController:signupAndroid')
    ->setName('signupAndroid');

$app->get('/da/receiveSensorData', 'App\Controller\UCSDController:receiveSensorData')
    ->setName('receiveSensorData');

$app->post('/da/receiveSensorData', 'App\Controller\UCSDController:receiveSensorData')
    ->setName('receiveSensorData');

$app->get('/da/sendAQIHistory', 'App\Controller\UCSDController:sendAQIHistory')
    ->setName('sendAQIHistory');

$app->post('/da/sendAQIHistory', 'App\Controller\UCSDController:sendAQIHistory')
    ->setName('sendAQIHistory');

$app->get('/da/receiveHRData', 'App\Controller\UCSDController:receiveHRData')
    ->setName('receiveHRData');

$app->post('/da/receiveHRData', 'App\Controller\UCSDController:receiveHRData')
    ->setName('receiveHRData');

$app->get('/da/sendHRHistory', 'App\Controller\UCSDController:sendHRHistory')
    ->setName('sendHRHistory');

$app->post('/da/sendHRHistory', 'App\Controller\UCSDController:sendHRHistory')
    ->setName('sendHRHistory');


$app->get('/da/receivesensor_list', 'App\Controller\UCSDController:receivesensor_list')
    ->setName('receivesensor_list');

$app->post('/da/receivesensor_list', 'App\Controller\UCSDController:receivesensor_list')
    ->setName('receivesensor_list');

$app->get('/da/sensor_DeregistAndroid', 'App\Controller\UCSDController:sensor_DeregistAndroid')
    ->setName('sensor_DeregistAndroid');

$app->post('/da/sensor_DeregistAndroid', 'App\Controller\UCSDController:sensor_DeregistAndroid')
    ->setName('sensor_DeregistAndroid');


$app->get('/da/sendalldevlist', 'App\Controller\UCSDController:sendalldevlist')
    ->setName('sendalldevlist');

$app->post('/da/sendalldevlist', 'App\Controller\UCSDController:sendalldevlist')
    ->setName('sendalldevlist');


$app->get('/da/senduserinfo', 'App\Controller\UCSDController:senduserinfo')
    ->setName('senduserinfo');

$app->post('/da/senduserinfo', 'App\Controller\UCSDController:senduserinfo')
    ->setName('senduserinfo');


$app->get('/da/send_otherSensorData', 'App\Controller\UCSDController:send_otherSensorData')
    ->setName('send_otherSensorData');

$app->post('/da/send_otherSensorData', 'App\Controller\UCSDController:send_otherSensorData')
    ->setName('send_otherSensorData');
