<?php

class StatisticsCollector {
    private static $instance = null;

    private function __construct() {

    }

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new StatisticsCollector();
        }

        return self::$instance;
    }

    public function collect() {
        return [
            'system'       => $this->collectSystem(),
            'cpu'          => $this->collectCpu(),
            'mem'          => $this->collectMem(),
            'size'         => $this->collectSize(),
            'network'      => $this->collectNetwork(),
        ];
    }

    private function collectSystem() {
        $uname = explode(" ", exec("uname -a"), 4);
        $values = [];
        $values['current_time'] = exec("date +'%d %b %Y %T %Z'");
        $values['processor'] = str_replace("-compatible processor", "", explode(": ", exec("cat /proc/cpuinfo | grep Processor"))[1]);
        $values['system'] = $uname[0];
        $values['kernel'] = $uname[2];
        $values['host'] = exec('hostname -f');
        return $values;
    }

    private function collectCpu() {
        $values = [];
        $loadavg = explode(" ", exec("cat /proc/loadavg"));
        $values['load'] = $loadavg[2];
        return $values;
    }

    private function collectMem() {
        $values = [];
        $meminfo = file("/proc/meminfo");
        if(!is_array($meminfo)) $meminfo = [];
        for ($i = 0; $i < count($meminfo); $i++) {
            list($item, $data) = explode(":", $meminfo[$i], 2);
            $item = trim(chop($item));
            $data = intval(preg_replace("/[^0-9]/", "", trim(chop($data)))); //Remove non numeric characters
            switch($item) {
                case "MemTotal": $values['total'] = $data; break;
                case "MemFree":  $values['free'] = $data; break;
                case "SwapTotal":  $values['total_swap'] = $data; break;
                case "SwapFree":  $values['free_swap'] = $data; break;
                case "Buffers":  $values['buffer'] = $data; break;
                case "Cached":  $values['cache'] = $data; break;
                default: break;
            }
        }
        return $values;
    }

    private function collectSize() {
        return [
            'total' => disk_total_space("/"),
            'used'  => disk_free_space("/"),
        ];
    }

    private function collectNetwork() {
        return [];
    }
}