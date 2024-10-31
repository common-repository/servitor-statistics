<?php

class StatisticsCronjob {

    public function run_statistics_cronjob() {
        $collector  = StatisticsCollector::getInstance();
        $statistics = @$collector->collect();

        $userKey = get_option('servitor_user_key');
        $apiKey  = get_option('servitor_api_key');

        return $this->doRequest( $statistics, $userKey, $apiKey );
    }

    /**
     * doRequest function.
     *
     * @param $statistics
     * @param $userKey
     * @param $apiKey
     *
     * @return mixed
     */
    protected function doRequest( $statistics, $userKey, $apiKey ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, Servitor_Statistics_Host . "/api/v1/statistics" );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($statistics + [
            'type' => 'WORDPRESS',
            ] ));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization-userkey: ' . $userKey,
            'Authorization-apikey: ' . $apiKey
        ));
        $response = curl_exec( $ch );
        if(curl_errno($ch)) {
            $response = curl_error($ch);
        }
        curl_close( $ch );

        return $response;
    }

}