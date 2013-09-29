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
        $mixpanelData['dailyListingClicks'] = $dailyListingIdToClickMap;
        $mixpanelData['totalListingClicks'] = $this->_getListingIdToCountMap('Listing Click', $first_day, $today, $where);
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

            $metricCounts['mostViewed'] = $this->_getTopNMostViewed(3, $listing_ids, $dailyListingIdToClickMap);
            $metricCounts['leastViewed'] = $this->_getTopNMostViewed(5, $listing_ids, $dailyListingIdToClickMap, false);

            $metricCounts['mostViewed'] = $this->Listing->GetListingIdToTitleMap($metricCounts['mostViewed']);
            $metricCounts['leastViewed'] = $this->Listing->GetListingIdToTitleMap($metricCounts['leastViewed']);

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

        $topN = array();
        $listingIdsWithClicks = array();
        /* Go through each listing_id in $listing_ids, and find the top N using values from the sorted map */
        foreach($listingIdToClicksMap as $listing_id => $clicks){
            array_push($listingIdsWithClicks, $listing_id);
            if (in_array($listing_id, $listing_ids) && count($topN) < $n)
                array_push($topN, $listing_id);
        }

        /* If there aren't enough listings with clicks to fill out the array, fill in with random 0-click listings */
        if (!$mostViewed && count($topN) < $n){
            foreach ($listing_ids as $listing_id){
                if (!in_array($listing_id, $listingIdsWithClicks))
                    array_push($topN, $listing_id);

                if (count($topN) === $n)
                    break;
            }
        }

        return $topN;
    }

    /*
    Queries for mixpanel data on 'Listing Click' event, segmenting on listing_id and display type = 'full page contact user'
    Returns map of listing_id => # contacts for that listing_id
    */
    private function _getListingIdToCountMap($event, $from_date, $to_date, $where = null)
    {
        $mixpanelData = $this->_getMixpanelData('segmentation', $event, "listing_id", $from_date, $to_date, $where);
        //CakeLog::write('rawdata', $where . '; ' . print_r($mixpanelData, true));
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