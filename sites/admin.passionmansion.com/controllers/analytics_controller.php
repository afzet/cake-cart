<?  
class AnalyticsController extends AppController{
    var $uses = array();
    var $Analytics = array();
 
    function beforeFilter() {
        App::import('ConnectionManager');
        $this->Analytics =& ConnectionManager::getDataSource('analytics');
    }
 
    function list_profiles() {
        // List all profiles associated with your account
        $profiles = $this->Analytics->listSources();
        debug($profiles);
        exit;
    }
    
    function show_reports() {
// The quickest way to test if the API is working for you.
        $report = $this->Analytics->report('Dashboard');
        debug($report);
        
// Use a specific profile id (see list_profiles action above), do:
        $report = $this->Analytics->report(array(
            'profile' => '290723',
            'report' => 'Dashboard'
        ));
        debug($report);
 
// You can also reference your profile by name instead
        $report = $this->Analytics->report(array(
            'profile' => 'www.thinkingphp.org',
            'report' => 'Dashboard'
        ));
        debug($report);
 
// Retrieve the raw XML instead of an array to parse it yourself (lets say using SimpleXml in PHP5):
        $report = $this->Analytics->report(array(
            'profile' => 'www.thinkingphp.org',
            'report' => 'Dashboard'
        ), true);
        debug($report);
        
// Retrieve a PDF report (make sure you set the right header before displaying):
        $report = $this->Analytics->report(array(
                'profile' => 'www.thinkingphp.org',
                'report' => 'Dashboard',
                'format' => 'pdf'
            ), true);
        debug($report);
 
// Set some criteria on the report:
        $report = $this->Analytics->report(array(
                'profile' => 'www.thinkingphp.org',
                'report' => 'Dashboard',
                'from' => '2007-12-17',
                'to' => '2007-12-18'
            ));
        debug($report);
        
/**
 * Small Reference of options:
 * 
 * profile: The id or name of the profile (optional, default = first profile)
 * report: A report string like 'Dashboard', 'TrafficSources', 'Keywords' (optional, default = 'Dashboard')
 * from: A yyyy-mm-dd formated date (optional, default = 1 month ago)
 * to: A yyyy-mm-dd formated date (optional, default = today)
 * tab: 0-4 (optional, default = 0)
 * view: 0-9+ (optional, default = 0)
 * format: 0-3 or 'pdf', 'xml', 'csv', 'tsv' (optional, default = 'xml')
 * compute: 'average', other options unknown? (optional, default = 'average')
 * query: Here you can manually pass (and overwrite) any part of the raw HTTP GET query made to Google Analytics. (optional, default = array())
 */
    }
}
 

?>