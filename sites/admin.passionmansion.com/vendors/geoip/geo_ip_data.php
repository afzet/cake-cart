<?php
/**
 * A simple wrapper to the original `geoiprecord' class provided by Maxmind's
 * pure PHP GeoIP API.
 *
 * This class uses camelCase property names and also includes an additional
 * property not seen in the original record set: timezone.
 *
 * @author Matthew Harris <shugotenshi@gmail.com>
 */
class GeoIpData {
    var $ip;
    var $remoteAddress;
    var $countryName;
    var $countryCode;
    var $longCountryCode;
    var $region;
    var $regionName;
    var $city;
    var $postalCode;
    var $areaCode;
    var $dmaCode;
    var $latitude;
    var $longitude;
    var $isp;
    var $internetServiceProvider;
    var $org;
    var $organization;
    var $timezone;
        
    function GeoIpData($record, $ip)
    {
        if (!$ip) {
            trigger_error('GeoIpData wants an IP address!', E_USER_WARNING);
        }
        elseif (!is_a($record, 'geoiprecord') && !is_a($record, 'geoipdnsrecord')) {
            trigger_error('GeoIpData can only be created from a geoiprecord or geoipdnsrecord object!', E_USER_WARNING);
        }
        else {
            $mapping = array(
                'ip'            => 'ip',
                'country_name'  => 'countryName',
                'country_code'  => 'countryCode',
                'country_code3' => 'longCountryCode',
                'region'        => 'region',
                'regionname'    => 'regionName',
                'city'          => 'city',
                'postal_code'   => 'postalCode',
                'area_code'     => 'areaCode',
                'dma_code'      => 'dmaCode',
                'dmacode'       => 'dmaCode',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
                'isp'           => 'isp',
                'org'           => 'org',
                'timezone'      => 'timezone'
            );
            
            // Add IP address to the record.
            $record->ip = $ip;
            
            // Add timezone to the record.
            $record->timezone = get_time_zone($record->country_code, $record->region);
            
            foreach ($mapping as $rKey => $dataKey) {
                if (isset($record->{$rKey})){
                    $value = $record->{$rKey};
                    if ($value) {
                        $this->{$dataKey} = $value;
                    }
                }
            }
            
            // Some alias properties.
            $this->remoteAddress            = $this->ip;
            $this->internetServiceProvider  = $this->isp;
            $this->organization             = $this->org;
        }
    }
    
    function asXML($root = 'GeoIpData')
    {
        $properties = array(
            'ip', 'remoteAddress', 'countryName', 'countryCode',
            'longCountryCode', 'region', 'regionName', 'city', 'postalCode',
            'areaCode', 'dmaCode', 'latitude', 'longitude', 'isp',
            'internetServiceProvider', 'org', 'organization', 'timezone'
        );
        $xml = "<$root>\n";
        foreach ($properties as $property) {
            if ($this->{$property}) {
                $xml .= "\t<$property>".htmlspecialchars($this->{$property})."</$property>\n";
            }
            else {
                $xml .= "\t<$property />\n";
            }
        }
        $xml .= "\t<googleMapsUrl>".htmlspecialchars($this->googleMapsUrl())."</googleMapsUrl>\n";
        $xml .= "\t<yahooMapsUrl>".htmlspecialchars($this->yahooMapsUrl())."</yahooMapsUrl>\n";
        $xml .= "</$root>\n";
        return $xml;
    }
    
    function googleMapsUrl($title = null)
    {
        if (!is_null($this->latitude) && !is_null($this->longitude)) {
            if (is_null($title)) {
                $title = '';
                
                if (!is_null($this->city)) {
                    $title .= $this->city;
                }
                
                if (!is_null($this->region) && !is_numeric($this->region)) {
                    $title .= ' '.$this->region;
                }
                
                if (!is_null($this->countryName)) {
                    $title .= ', '.$this->countryName;
                }
                
                $title = trim($title, ' ,');
            }
            
            $url = 'http://maps.google.com/maps?q=%f,+%f+(%s)';
            return sprintf($url, $this->latitude, $this->longitude, rawurlencode($title));
        }
        else {
            return false;
        }
    }
    
    function yahooMapsUrl()
    {
        if (!is_null($this->latitude) && !is_null($this->longitude)) {
            $url = 'http://maps.yahoo.com/#mvt=m&lat=%f&lon=%f';
            return sprintf($url, $this->latitude, $this->longitude);
        }
        else {
            return false;
        }
    }
}
?>