# kaltura-webcaster-simulive-php

This app highlights how one would integrate Kaltura webcasting into a web application.

## Prerequisites

- PHP and Apache installed on development machine
- in AppSettings.php, replace the occurences of "<INSERT_PROPER_VALUE>" with the proper values.

## Key Features

- Creating a Kaltura Live Entry, which houses the simulive webcast
- Downloading the Kaltura Webcast Studio application, which is needed to present the webcast
- Launch the Kaltura Webcast Studio application
- View the webcast in a Kaltura Player

## Simulive Components

These are the components that make simulive different than a live broadcast with respect to a Kaltura implementation.

1. Access Control Profile (ACP): create a new one in the KMC [here](https://kmc.kaltura.com/index.php/kmcng/settings/accessControl) without any special configuration. It will be configured in the code.
    * Set the production simulive delivery profile on the ACP: 21633
    * To enable simulive & live (if you want to transition from simulive to live) —> set both 21633 and live delivery profile 15282 (live HLS) on the ACP
2. VOD Entry
    * Populate it’s entry ID to sourceEntryId on scheduleEvent (as described below)
3. Live Stream Entry
    * Disable preview mode: explicitLive = FALSE
    * Disable recording: recordStatus = KalturaRecordStatus::DISABLED
    * Populate accessControlId with ACP Id
4. Schedule Event
    * scheduleEvent->startDate
    * scheduleEvent->endDate
    * scheduleEvent->recurrenceType = KalturaScheduleEventRecurrenceType::NONE
    * scheduleEvent->templateEntryId —> liveStream entry Id
    * scheduleEvent->sourceEntryId —> VOD entry Id
    * scheduleEvent->preStartTime: time allocated prior to the actual start of the simulive to show some sort of message (i.e. "Live broadcast starting soon"). Note that this needs to be part of the VOD entry. So for example, if the simulive is 30 minutes and the pre-simulive content is 5 minutes, then the VOD entry should be 35 minutes long and the preStartTime would be 300 (seconds).
