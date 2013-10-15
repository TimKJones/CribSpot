<?php

class ReportingShell extends AppShell 
{
	public $uses = array('Message', 'Conversation', 'Listing', 'User');

    /*
    Sends a daily usage report to property managers about their properties
    

    */  
    public function send_pm_reports($time_period = 'WEEKLY') 
    {
        $counter = 0;
        $yesterday = date('Y-m-d', time() - 60 * 60 * 24);
        $from_date = null;
        $to_date = null;
        if ($time_period === 'DAILY') {
            $from_date = $yesterday;
            $to_date = $yesterday;
        } else if ($time_period === 'WEEKLY') {
            $from_date = date('Y-m-d', time() - 60 * 60 * 24 * 8);
            $to_date = $yesterday;
        }
        
        $first_day = '2013-09-03';
        $mixpanelMetrics = array(
            'dailyListingClicks',
            'totalListingClicks',
            'dailyWebsiteReferrals',
            'totalWebsiteReferrals',
            'dailyContacts',
            'totalContacts',
            'dailyMessages',
            'totalMessages'
        );

        $mixpanelData = array();

        $where = '"small popup"' . " == " . 'properties["display type"]';
        $dailyListingIdToClickMap = $this->_getListingIdToCountMap('Listing Click', $from_date, $to_date, $where);
        if ($dailyListingIdToClickMap === null)
            return;

        $totalListingIdToClickMap = $this->_getListingIdToCountMap('Listing Click', $first_day, $yesterday, $where);
        if ($totalListingIdToClickMap === null)
            return;

        $mixpanelData['dailyListingClicks'] = $dailyListingIdToClickMap;
        $mixpanelData['totalListingClicks'] = $totalListingIdToClickMap;

        /* Website referrals */
        $where = '"go to realtor\'s website"' . " == " . 'properties["display type"]';
        $mixpanelData['dailyWebsiteReferrals'] = $this->_getListingIdToCountMap('Listing Click', $from_date, $to_date, $where);
        $mixpanelData['totalWebsiteReferrals'] = $this->_getListingIdToCountMap('Listing Click', $first_day, $yesterday, $where);
        if ($mixpanelData['dailyWebsiteReferrals'] === null || $mixpanelData['totalWebsiteReferrals'] === null)
            return;

        /* Contacts */
        $where = '"full page contact user"' . " == " . 'properties["display type"]';
        $dailyListingIdToContactsMap = $this->_getListingIdToCountMap('Listing Click', $from_date, $to_date, $where);
        $totalListingIdToContactsMap = $this->_getListingIdToCountMap('Listing Click', $first_day, $yesterday, $where);
        $mixpanelData['dailyContacts'] = $dailyListingIdToContactsMap;
        $mixpanelData['totalContacts'] = $totalListingIdToContactsMap;
        if ($mixpanelData['dailyContacts'] === null || $mixpanelData['totalContacts'] === null)
            return;

        $messages = $this->Message->GetListingIdToReceivedMessagesMap();
        $dailyListingIdToReceivedMessagesMap = $messages['daily'];
        $totalListingIdToReceivedMessagesMap = $messages['total'];
        $mixpanelData['dailyMessages'] = $dailyListingIdToReceivedMessagesMap;
        $mixpanelData['totalMessages'] = $totalListingIdToReceivedMessagesMap;

        /* Get map of user_id to array of listing_ids they own */
        $userIdToListingIdsMap = $this->Listing->GetUserIdToOwnedListingIdsMap();

        foreach($userIdToListingIdsMap as $user_id => $listing_ids) {
            $user = $this->User->get($user_id);
            if (!array_key_exists('User', $user) ||
                !array_key_exists('email', $user['User']) ||
                !array_key_exists('user_type', $user['User']) ||
                $user['User']['user_type'] != 1 ||
                (array_key_exists('receives', $user['User']) && $user['User']['receives_metrics_report'] != 1))
                    continue;

            /* map of totals for each individual user */
            $metricCounts = array();
            foreach ($mixpanelMetrics as $metric)
                $metricCounts[$metric] = 0;

            $metricCounts['dailyPhoneCalls'] = 0;
            $metricCounts['totalPhoneCalls'] = 0;
            $metricCounts['dailyMessages'] = 0;
            $metricCounts['totalMessages'] = 0;
            $metricCounts['dailyMessages'] = 0;
            $metricCounts['totalMessages'] = 0;
            
            foreach ($listing_ids as $listing_id){
                foreach ($mixpanelMetrics as $metric){
                    if (array_key_exists($listing_id, $mixpanelData[$metric]) && 
                        $mixpanelData[$metric][$listing_id] !== null){
                        $metricCounts[$metric] += intval($mixpanelData[$metric][$listing_id]);
                    }
                }

                /* Calculate phone calls as being # contact buttons pressed - # messages sent */

                if (array_key_exists($listing_id, $dailyListingIdToContactsMap)){
                    $messages = 0;
                    if (array_key_exists($listing_id, $dailyListingIdToReceivedMessagesMap))
                        $messages = $dailyListingIdToReceivedMessagesMap[$listing_id];

                    $metricCounts['dailyPhoneCalls'] +=  ($dailyListingIdToContactsMap[$listing_id] - $messages);
                }
                    
                if (array_key_exists($listing_id, $totalListingIdToContactsMap)){
                    $messages = 0;
                    if (array_key_exists($listing_id, $totalListingIdToReceivedMessagesMap))
                        $messages = $totalListingIdToReceivedMessagesMap[$listing_id];     

                    $metricCounts['totalPhoneCalls'] += ($totalListingIdToContactsMap[$listing_id] - $messages);
                }              
            }

CakeLog::write("userslistingids", $user_id . ': ' . print_r($listing_ids, true));
CakeLog::write("dailyIdToClickMap", print_r($dailyListingIdToClickMap, true));
CakeLog::write('lengths', 'dailyIdToClickMap: '. count($dailyListingIdToClickMap));
            /* Get map of listing_id to number of clicks on that listing_id */
            $dailyMostViewedListingIdToClicksMap = $this->_getTopNMostViewed(5, $listing_ids, $dailyListingIdToClickMap);
            $dailyLeastViewedListingIdToClicksMap = $this->_getTopNMostViewed(5, $listing_ids, $dailyListingIdToClickMap, false);
            $totalMostViewedListingIdToClicksMap = $this->_getTopNMostViewed(3, $listing_ids, $totalListingIdToClickMap);
            $totalLeastViewedListingIdToClicksMap = $this->_getTopNMostViewed(5, $listing_ids, $totalListingIdToClickMap, false);
CakeLog::write('dailyleastviewed', $user['User']['id'].': '. print_r($dailyLeastViewedListingIdToClicksMap, true));
            $titleToClicksMaps = array();
            $titleToClicksMaps['leastViewed'] = $this->_getListingTitleToClicksMetrics($dailyLeastViewedListingIdToClicksMap,
                $totalListingIdToClickMap);
CakeLog::write('dailyleastviewedNow', $user['User']['id'].': '. print_r($titleToClicksMaps['leastViewed'], true));
            $titleToClicksMaps['mostViewed'] = $this->_getListingTitleToClicksMetrics($dailyMostViewedListingIdToClicksMap,
                $totalListingIdToClickMap);


            /* Get map of listing_id to number of contact events for that listing_id */
            $dailyMostContactedListingIdToClicksMap = $this->_getTopNMostViewed(5, $listing_ids, $dailyListingIdToContactsMap);

            $titleToMostContactedMap = array();
            $titleToMostContactedMap = $this->_getListingTitleToClicksMetrics($dailyMostContactedListingIdToClicksMap,
                $dailyListingIdToContactsMap);

            


            //$titleToClicksMaps['totalLeastViewed'] = $this->_getListingTitleToClicksMetrics($totalLeastViewedListingIdToClicksMap);
            //$titleToClicksMaps['totalMostViewed'] = $this->_getListingTitleToClicksMetrics($totalMostViewedListingIdToClicksMap);

            /* Prepare data for the 'Daily Metrics Report' table */

            $overviewMetrics = array(
                'dailyListingClicks',
                'dailyWebsiteReferrals',
                'dailyPhoneCalls',
                'dailyMessages',
                'totalListingClicks',
                'totalWebsiteReferrals',
                'totalPhoneCalls',
                'totalMessages'
            );

            /*
            FIX: 10-12-2013 TKJ
            Phone calls can be negative for a few weird edge cases as a result of not having tracked contact from full-page
            */
            $phoneMetrics = array('dailyPhoneCalls', 'totalPhoneCalls');
            foreach ($phoneMetrics as $metric){
                if (array_key_exists($metric, $metricCounts) && $metricCounts[$metric] < 0)
                    $metricCounts[$metric] = 0;
            }

            $overviewMetricsCount = array();
            foreach ($overviewMetrics as $metric){
                $overviewMetricsCount[$metric] = $metricCounts[$metric];
            }

            $templateData['leastViewed'] = $titleToClicksMaps['leastViewed'];
            $templateData['mostViewed'] = $titleToClicksMaps['mostViewed'];
            $templateData['user'] = $user['User'];
            $templateData['overviewMetrics'] = $overviewMetricsCount;
            $templateData['mostContacted'] = $titleToMostContactedMap;

            /* Sort final arrays before inserting them into tables */
            asort($templateData['leastViewed']);
            arsort($templateData['mostViewed']);
            arsort($templateData['mostContacted']);

            CakeLog::write('template', print_r($templateData, true));

            $month = date('F');
            $day = date('j');
            $year = date('Y');
            $yesterday = $month.' '.$day.', '.$year;
            $time_period_string = 'Weekly';
            if ($time_period === 'DAILY')
                $time_period_string = 'Daily';

            /* Format the date for "Since ..." at the bottom of the email */
            $since_date = $user['User']['created'];
            $since_month = date('F', strtotime($since_date));
            $since_day = date('j', strtotime($since_date));
            $since_year = date('Y', strtotime($since_date));
            $since_date = $since_month.' '.$since_day.', '.$since_year;
            $templateData['sinceDate'] = $since_date;


            $templateData['timePeriod'] = $time_period_string;

            if ((Configure::read('EMAIL_DEBUG_MODE') === 'DEBUG' && $counter < 2) ||
                Configure::read('EMAIL_DEBUG_MODE') === 'PRODUCTION'){
                if (array_key_exists('User', $user) && array_key_exists('email', $user['User'])) {
                    $email = $user['User']['email'];
                    if (!empty($email)){
                        $this->_emailUser($email, 'Cribspot '.$time_period_string.' Metrics Report: '.$yesterday, "daily_pm_report", $templateData);
                    }
                }
            }

            CakeLog::write('mixpanelMetrics', $user_id);
            CakeLog::write('mixpanelMetrics', print_r($metricCounts, true));
        }  
    }

    /* 
    Returns the top n most viewed listing_ids in $listing_ids
    if $mostViewed == false, returns the bottom n most viewed
    */
    private function _getTopNMostViewed($n, $listing_ids, $listingIdToClicksMap, $mostViewed = true)
    {
        if ($mostViewed)
            arsort($listingIdToClicksMap);
        else
            asort($listingIdToClicksMap);

        CakeLog::write('sorted', print_r($listingIdToClicksMap, true));

        /* Get a list of all listings that have clicks */
        $listingIdsWithClicks = array();
        foreach($listingIdToClicksMap as $listing_id => $clicks){
            array_push($listingIdsWithClicks, $listing_id);
        }

        $topN = array();
        /* If we're getting the least viewed listing_ids, start by adding listings with no clicks */
        if (!$mostViewed){
            foreach ($listing_ids as $listing_id){
                if (count($topN) < $n && !in_array($listing_id, $listingIdsWithClicks))
                    $topN[$listing_id] = 0;
            }
        }

        /* Go through each listing_id in $listing_ids, and find the top N using values from the sorted map */
        foreach($listingIdToClicksMap as $listing_id => $clicks){
            if (in_array($listing_id, $listing_ids) && count($topN) < $n){
                $topN[$listing_id] = $clicks;
            }
        }   

        if ($mostViewed)
            arsort($topN);
        else
            asort($topN);

        CakeLog::write('topN', print_r($topN, true));

        return $topN;
    }

    /*
    Takes as input a map of listing_id to clicks for that listing_id for an unspecified time period.
    Returns a map of Address (or alternate_name) with unit title and description => clicks
    */
    private function _getListingTitleToClicksMetrics($idToClicksMaps, $totalListingIdToClickMap)
    {
CakeLog::write('totalListingIdToClickMap', print_r($totalListingIdToClickMap, true));
        $titleToClicksMap = array();
        /* Take out just the listing_ids to convert to their unit names */
        $listingIds = array();
        foreach ($idToClicksMaps as $listing_id => $clicks)
            array_push($listingIds, $listing_id);

CakeLog::write('listingids', print_r($listingIds, true));

        $listingIdToTitleMap = $this->Listing->GetListingIdToTitleMap($listingIds);
CakeLog::write('listingIdToTitleMap', print_r($listingIdToTitleMap, true));
        /* Now put the unit names back into the original map to get ready to be formatted in the email */
        foreach ($listingIdToTitleMap as $listing_id => $title){
            if (!array_key_exists($listing_id, $idToClicksMaps))
                    continue;

            $clickCount = 0;
            if (array_key_exists($listing_id, $idToClicksMaps))
                $clickCount = $idToClicksMaps[$listing_id];

            $titleToClicksMap[$title] = array();
            $titleToClicksMap[$title]['daily'] = $clickCount;
            $titleToClicksMap[$title]['total'] = 0;
            if (array_key_exists($listing_id, $totalListingIdToClickMap))
                $titleToClicksMap[$title]['total'] = $totalListingIdToClickMap[$listing_id];
        }

CakeLog::write('metriccounts', print_r($titleToClicksMap, true));
        return $titleToClicksMap;
    }


    /*
    Queries for mixpanel data on 'Listing Click' event, segmenting on listing_id and display type = 'full page contact user'
    Returns map of listing_id => # contacts for that listing_id
    */
    private function _getListingIdToCountMap($event, $from_date, $to_date, $where = null)
    {
        $mixpanelData = $this->_getMixpanelData('segmentation', $event, "listing_id", $from_date, $to_date, $where);
        $map = array();
        if (!property_exists($mixpanelData, 'data') || !property_exists($mixpanelData->data, 'values'))
            return null;

        if ($mixpanelData->data !== null && $mixpanelData->data->values !== null){
            $values = $mixpanelData->data->values;
            CakeLog::write('values', print_r($values, true));
            foreach ($values as $listing_id=>$dateToViewCountMap){
                $map[$listing_id] = 0;
                foreach ($dateToViewCountMap as $date=>$viewCount){
                    /* $dateMap is a map of date => view count for that date */
                    $map[$listing_id] += $viewCount;
                }
            }
        }

        return $map;
    }

    private function _getMixpanelData($endpoint, $event, $properties, $from_date, $to_date, $where = null)
    {
        App::uses('Mixpanel', 'Mixpanel');    
        $api_key = Configure::read('MIXPANEL_KEY');
        $api_secret = Configure::read('MIXPANEL_SECRET');
         
        $mp = new Mixpanel($api_key, $api_secret);
        $endpoint = array($endpoint);
        $parameters = array(
            'event' => $event,
            'on' => 'properties["' . $properties .'"]',
            'from_date' => $from_date,
            'to_date' => $to_date,
            'limit' => 10000
        );
        if ($where)
            $parameters['where'] = $where;

        return $mp->request($endpoint, $parameters);
    }
}

?>