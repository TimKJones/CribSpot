<?php

class ReportingShell extends AppShell 
{
	public $uses = array('Message', 'Conversation', 'Listing', 'User');

    /*
    Sends a daily usage report to property managers about their properties
    IMPORTANT: schedule for 11:59 PM every day, not 12:00 AM.
    */  
    public function send_daily_pm_reports() 
    {

/* MAKE IT GO YESTERDAY INSTEAD OF TODAY */ 

        /* 
        Get map of user_id => list of listing_ids they own
        Have maps for:
            - listing_id => # listing clicks
            - listing_id => # 'go to website' clicks
            - listing_id => # times contact button pressed
        Loop through (user_id => listing_ids) map
            for each user_id, calculate each metric we're tracking
            set variables
            send email

        Data I'm trying to get
        - Total # of marker clicks for a specific PM's properties
        - Total # of referrals to their website today
        - # times contact button pressed for their properties
        - # messages they've received today and total
        - their most popular properties?
        - their least popular properties?
        - add that we can get them data for any property
        */
        $today = date('Y-m-d');
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
        $dailyListingIdToClickMap = $this->_getListingIdToCountMap('Listing Click', $today, $today, $where);
        $totalListingIdToClickMap = $this->_getListingIdToCountMap('Listing Click', $first_day, $today, $where);
        $mixpanelData['dailyListingClicks'] = $dailyListingIdToClickMap;
        $mixpanelData['totalListingClicks'] = $totalListingIdToClickMap;
        $where = '"go to realtor\'s website"' . " == " . 'properties["display type"]';
        $mixpanelData['dailyWebsiteReferrals'] = $this->_getListingIdToCountMap('Listing Click', $today, $today, $where);
        $mixpanelData['totalWebsiteReferrals'] = $this->_getListingIdToCountMap('Listing Click', $first_day, $today, $where);

        $where = '"full page contact user"' . " == " . 'properties["display type"]';
        $dailyContactsMap = $this->_getListingIdToCountMap('Listing Click', $today, $today, $where);
        $totalContactsMap = $this->_getListingIdToCountMap('Listing Click', $first_day, $today, $where);
        $mixpanelData['dailyContacts'] = $dailyContactsMap;
        $mixpanelData['totalContacts'] = $totalContactsMap;

        $messages = $this->Message->GetUserIdToReceivedMessagesMap();
        $dailyMessagesMap = $messages['daily'];
        $totalMessagesMap = $messages['total'];  
        $mixpanelData['dailyMessages'] = $dailyMessagesMap; 
        $mixpanelData['totalMessages'] = $totalMessagesMap; 

        /* Get map of user_id to array of listing_ids they own */
        $userIdToListingIdsMap = $this->Listing->GetUserIdToOwnedListingIdsMap();

        foreach($userIdToListingIdsMap as $user_id => $listing_ids){
            $user = $this->User->get($user_id);
            $metricCounts = array();
            foreach ($mixpanelMetrics as $metric)
                $metricCounts[$metric] = 0;

            $metricCounts['dailyPhoneCalls'] = 0;
            $metricCounts['totalPhoneCalls'] = 0;
            
            foreach ($listing_ids as $listing_id){
                foreach ($mixpanelMetrics as $metric){
                    if (array_key_exists($listing_id, $mixpanelData[$metric]) && 
                        $mixpanelData[$metric][$listing_id] !== null){
                        $metricCounts[$metric] += intval($mixpanelData[$metric][$listing_id]);
                    }
                }

                /* Calculate phone calls as being # contact buttons pressed - # messages sent */

                if (array_key_exists($listing_id, $dailyContactsMap)){
                    $messages = 0;
                    if (array_key_exists($listing_id, $dailyMessagesMap))
                        $messages = $dailyMessagesMap[$listing_id];

                    $metricCounts['dailyPhoneCalls'] +=  ($dailyContactsMap[$listing_id] - $messages);
                }
                    
                if (array_key_exists($listing_id, $totalContactsMap)){
                    $messages = 0;
                    if (array_key_exists($listing_id, $totalMessagesMap))
                        $messages = $totalMessagesMap[$listing_id];     

                    $metricCounts['totalPhoneCalls'] += ($totalContactsMap[$listing_id] - $messages);
                }
            }
CakeLog::write("userslistingids", $user_id . ': ' . print_r($listing_ids, true));
CakeLog::write("dailyIdToClickMap", print_r($dailyListingIdToClickMap, true));
CakeLog::write('lengths', 'dailyIdToClickMap: '. count($dailyListingIdToClickMap));
            /* Get map of listing_id to number of clicks on that listing_id */
            $mostViewedListingIdToClicksMap = $this->_getTopNMostViewed(3, $listing_ids, $dailyListingIdToClickMap);
            $leastViewedListingIdToClicksMap = $this->_getTopNMostViewed(5, $listing_ids, $dailyListingIdToClickMap, false);
CakeLog::write('listingIdToClicksMap1', print_r($mostViewedListingIdToClicksMap, true));
            /* Take out just the listing_ids to convert to their unit names */
            $mostViewedListingIds = array();
            $leastViewedListingIds = array();
            foreach ($mostViewedListingIdToClicksMap as $listing_id=>$clicks)
                array_push($mostViewedListingIds, $listing_id);

            foreach ($leastViewedListingIdToClicksMap as $listing_id => $clicks)
                array_push($leastViewedListingIds, $listing_id);
CakeLog::write('mostViewed', print_r($mostViewedListingIds, true));
CakeLog::write('leastViewed', print_r($leastViewedListingIds, true));

            $mostViewedListingIdToTitleMap = $this->Listing->GetListingIdToTitleMap($mostViewedListingIds);
            $leastViewedListingIdToTitleMap = $this->Listing->GetListingIdToTitleMap($leastViewedListingIds);
CakeLog::write('listingIdToTitleMap', print_r($mostViewedListingIdToTitleMap, true));
            /* Now put the unit names back into the original map to get ready to be formatted in the email */
            foreach ($mostViewedListingIdToTitleMap as $listing_id => $title){
                if (!array_key_exists($listing_id, $mostViewedListingIdToClicksMap))
                        continue;

                $clickCount = 0;
                if (array_key_exists($listing_id, $mostViewedListingIdToClicksMap))
                    $clickCount = $mostViewedListingIdToClicksMap[$listing_id];

                $metricCounts['mostViewed'][$title] = $clickCount;
            }
CakeLog::write('metriccountsMostViewedn', print_r($metricCounts['mostViewed'], true));

            foreach ($leastViewedListingIdToTitleMap as $listing_id => $title){
                if (!array_key_exists($listing_id, $leastViewedListingIdToTitleMap))
                    continue;   

                CakeLog::write('listingIdToClicksMap', $listing_id . ' ' . $title);
                $clickCount = 0;
                if (array_key_exists($listing_id, $mostViewedListingIdToClicksMap))
                    $clickCount = $dailyListingIdToClickMap[$listing_id];

                $metricCounts['leastViewed'][$title] = $clickCount;
            }
CakeLog::write('gettingclose', print_r($metricCounts, true));
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

            $overviewMetricsCount = array();
            foreach ($overviewMetrics as $metric){
                $overviewMetricsCount[$metric] = $metricCounts[$metric];
            }

            $mostViewed = null;
            $leastViewed = null;
            if (array_key_exists('mostViewed', $metricCounts))
                $mostViewed = $metricCounts['mostViewed'];

            if (array_key_exists('leastViewed', $metricCounts))
                $leastViewed = $metricCounts['leastViewed'];

            $templateData = array(
                'user' => $user['User'], 
                'overviewMetrics' => $overviewMetricsCount,
                'mostViewed' => $mostViewed,
                'leastViewed' => $leastViewed
            );

            $month = date('F');
            $day = date('j');
            $year = date('Y');
            $today = $month.' '.$day.', '.$year;
            //if ($user['User']['id'] == 12)
           //     $this->_emailUser('tim@cribspot.com', 'Cribspot Daily Metrics Report: '.$today, "daily_pm_report", $templateData);


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

        /* Start by adding any listings that have 0 clicks */
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

        /* If there aren't enough listings with clicks to fill out the array, fill in with random 0-click listings */
       /* if (!$mostViewed && count($topN) <= $n){
            foreach ($listing_ids as $listing_id){
                if (!in_array($listing_id, $listingIdsWithClicks))
                    $topN[$listing_id] = $clicks;

                if (count($topN) === $n)
                    break;
            }
        }*/
CakeLog::write('topn', print_r($topN, true));
        return $topN;
    }

    /*
    Queries for mixpanel data on 'Listing Click' event, segmenting on listing_id and display type = 'full page contact user'
    Returns map of listing_id => # contacts for that listing_id
    */
    private function _getListingIdToCountMap($event, $from_date, $to_date, $where = null)
    {
        $mixpanelData = $this->_getMixpanelData('segmentation', $event, "listing_id", $from_date, $to_date, $where);
        $map = array();
        if ($mixpanelData->data !== null && $mixpanelData->data->values !== null){
            $values = $mixpanelData->data->values;
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
            'to_date' => $to_date
        );
        if ($where)
            $parameters['where'] = $where;

        return $mp->request($endpoint, $parameters);
    }
}

?>