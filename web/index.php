<?php

/**
 *
 * Twilio twimlet for forwarding inbound calls
 * to the on-call engineer as defined in PagerDuty
 *
 * Designed to be hosted on Heroku
 *
 * (c) 2014 Vend Ltd.
 *
 */

require __DIR__ . '/../vendor/autoload.php';

// Set these Heroku config variables
$scheduleID = getenv('PAGERDUTY_SCHEDULE_ID');
$APItoken   = getenv('PAGERDUTY_API_TOKEN');
$domain     = getenv('PAGERDUTY_DOMAIN');
$callerId   = getenv('TWILIO_CALLERID');
$recordCall     = getenv('TWILIO_RECORD_CALL');

// Should we announce the local time of the on-call person?
// (helps raise awareness you might be getting somebody out of bed)
$announceTime = getenv('PHONEDUTY_ANNOUNCE_TIME');
$announceGreeting = getenv('PAGERDUTY_ANNOUNCE_GREETING');


$pagerduty = new \Vend\Phoneduty\Pagerduty($APItoken, $domain);

$userID = $pagerduty->getOncallUserForSchedule($scheduleID);

if (null !== $userID) {
    $user = $pagerduty->getUserDetails($userID);

    $attributes = array(
        'voice' => 'alice',
        'language' => 'en-AU'
    );

    $greeting = "";
    if ($announceGreeting) {
 	$greeting = sprintf("%s", $announceGreeting);
    }

    $time = "";
    if ($announceTime && $user['local_time']) {
        $time = sprintf("The current time in their timezone is %s.", $user['local_time']->format('g:ia'));
    }

    $twilioResponse = new Services_Twilio_Twiml();
    $response = sprintf("%s The current on-call engineer is %s. %s "
        . "Please hold while we connect you.",
        $greeting,
        $user['first_name'],
        $time
        );

    // TwiML prepare <Dial> stanza
    $dialAttributes = array ();

    // TwiML 'callerId' attribute in <Dial> stanza, what number to use as callerId
    if ($callerId) {
        $dialAttributes += array('callerId' => $callerId);
    }

    // TwiML 'record' attribute in <Dial> stanza, if/how call should be recorded
    // recommend 'record-from-answer' for record, not set for not
    if ($recordCall) {
	$dialAttributes += array('record' => $recordCall);
    }
	
    $twilioResponse->say($response, $attributes);
    $twilioResponse->dial( $user['phone_number'], $dialAttributes);

    // send response
    if (!headers_sent()) {
        header('Content-type: text/xml');
    }

    echo $twilioResponse;
}
