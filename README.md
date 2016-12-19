# Phoneduty

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vend/phoneduty/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vend/phoneduty/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/vend/phoneduty/badges/build.png?b=master)](https://scrutinizer-ci.com/g/vend/phoneduty/build-status/master)

[![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)

This is a Twilio Twimlet designed to be hosted on Heroku. It will query PagerDuty to find the currently on-call engineer and forward the inbound call to them.

It needs a few environment variables defined to work:
    
| Environment Variable | Value | Description | Required? |
|----------------------|-------|-------------|-----------| 
|PAGERDUTY_ANNOUNCE_GREETING | Welcome to MyCompany! | some lead-in greeting | |
|PAGERDUTY_API_TOKEN | api-token-goes-here | PagerDuty v1 API Token | yes |
|PAGERDUTY_DOMAIN | mycomapny | PagerDuty account domain, i.e. https://[PAGERDUTY_DOMAIN].pagerduty.com/ | yes |
|PAGERDUTY_SCHEDULE_ID | some-id | ScheduleID for the on-call schedule to look up | yes | 
|PAGERDUTY_SERVICE_API_TOKEN | service-token-goes-here | Different to the regular API Token! | yes |
|PHONEDUTY_ANNOUNCE_TIME |true | if set to a TRUEish value will include the current time of the engineer being called as part of the answering message. This may help raise awareness that you are potentially getting somebody out of bed, so be gentle | |
|TWILIO_CALLERID | some-e.164-phone-number | If set, will use this value for callerID instead of the inbound caller's callerID. We use this so engineers know that the call is coming from our support number and hence will be logged. Else there's no way to tell. Must be a number purchased from or registered with Twilio. eg "+61 2 1234 5678"| |
|TWILIO_RECORD_CALL | record-from-answer | configures whether the call should be recorded, by setting "record" attribute in Twilio TwiML \<Dial> stanza. Recommend "record-from-answer" for recording, not set for no recording. see: https://www.twilio.com/docs/api/twiml/dial | |


# Usage

- Configure your on-call schedule in PagerDuty
- Ensure your rostered staff have a 'phone' contact method defined
- Note the schedule ID of the roster you wish to use.
- Create and note an API key in PagerDuty
- Deploy this app to Heroku.
- Configure the relevant environment variables above in Heroku
- Buy a phone number from Twilio
- Add the generated Heroku URL  as a "Request URL" for the Voice property of this Twilio number
- Call the external Twilio number. You should get a voice prompt telling you who is on call, what time it is in their timezone currently, and then you will get connected to the rostered engineer.


# Relevant Reading

## Twilio

Twilio TwiML reference
<https://www.twilio.com/docs/api/twiml>

Some sample Twimlets:
<https://www.twilio.com/labs/twimlets>


## PagerDuty 

PagerDuty API 
<http://developer.pagerduty.com/documentation/integration/events>

## Heroku

Setting up and deploying PHP apps on Heroku
<https://devcenter.heroku.com/articles/getting-started-with-php>





