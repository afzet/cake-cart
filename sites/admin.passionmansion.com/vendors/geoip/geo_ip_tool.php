<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'geo_ip_data.php';
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'api/geoip.inc';
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'api/geoipcity.inc';
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'api/geoipregionvars.php';
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'api/timezone.php';

/**
 * A simple wrapper tool for making GeoIP lookups.
 *
 * @author Matthew Harris <shugotenshi@gmail.com>
 */
class GeoIpTool {
    /**
     * GeoIP instance (geoip.inc)
     *
     * @var GeoIP
     * @access private
     */
    var $__geoip;
    
    /**
     * Construct new GeoIpTool instance.
     *
     * @param string $file GeoIP database file
     * @param string $flags Flags to pass into geoip_open()
     * @access public
     */
    function GeoIpTool($file, $flags)
    {
        if (!file_exists($file)) {
            if (is_null($file)) {
                $file = '(null)';
            }
            trigger_error("$file: No such file or directory; You can download the GeoLite City database for free at Maxmind.com",
                E_USER_WARNING);
        }
        elseif (!is_readable($file)) {
            trigger_error("$file: The GeoIP database file is not readable, please correct permissions.",
                E_USER_WARNING);
        }
        else {
            if ($flags & GEOIP_SHARED_MEMORY) {
                @geoip_load_shared_mem($file);
            }
            
            $this->__geoip = @geoip_open($file, $flags);
            if (!$this->__geoip) {
                trigger_error("$file: Failed to load the GeoIP database!", E_USER_WARNING);
            }
        }
    }
    
    /**
     * Lookup a record in the database.
     *
     * @param string $ip IP address to lookup
     * @access public
     * @return GeoIpData|false
     */
    function lookup($ip)
    {
        $record = GeoIP_record_by_addr($this->__geoip, $ip);
        if ($record) {
            return new GeoIpData($record, $ip);
        }
        else {
            return false;
        }
    }
}
?>