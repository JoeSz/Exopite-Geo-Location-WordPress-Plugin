<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Geo_Location
 * @subpackage Exopite_Geo_Location/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Exopite_Geo_Location
 * @subpackage Exopite_Geo_Location/public
 * @author     Joe Szalai <joe@szalai.org>
 */
class Exopite_Geo_Location_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Geo_Location_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Geo_Location_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/exopite-geo-location-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Exopite_Geo_Location_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exopite_Geo_Location_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/exopite-geo-location-public.js', array( 'jquery' ), $this->version, false );

	}

    /*
     * Get user IP address
     */
    public function get_ip_address() {

        // check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        // check for IPs passing through proxies
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // check if multiple ips exist in var
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
                $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($iplist as $ip) {
                    if ($this->validate_ip($ip))
                        return $ip;
                }
            } else {
                if ($this->validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                    return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && $this->validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];
        if (!empty($_SERVER['HTTP_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];

        // return unreliable ip since all else failed
        return $_SERVER['REMOTE_ADDR'];

    }

    /**
     * Ensures an ip address is both a valid IP and does not fall within
     * a private network range.
     */
    public function validate_ip($ip) {

        if (strtolower($ip) === 'unknown')
            return false;

        // generate ipv4 network address
        $ip = ip2long($ip);

        // if the ip is set and not equivalent to 255.255.255.255
        if ($ip !== false && $ip !== -1) {
            // make sure to get unsigned long representation of ip
            // due to discrepancies between 32 and 64 bit OSes and
            // signed numbers (ints default to signed in PHP)
            $ip = sprintf('%u', $ip);
            // do private network range checking
            if ($ip >= 0 && $ip <= 50331647) return false;
            if ($ip >= 167772160 && $ip <= 184549375) return false;
            if ($ip >= 2130706432 && $ip <= 2147483647) return false;
            if ($ip >= 2851995648 && $ip <= 2852061183) return false;
            if ($ip >= 2886729728 && $ip <= 2887778303) return false;
            if ($ip >= 3221225984 && $ip <= 3221226239) return false;
            if ($ip >= 3232235520 && $ip <= 3232301055) return false;
            if ($ip >= 4294967040) return false;
        }

        return true;

    }

    public function use_geoplugin( $ip, $purpose ) {

        $url = "http://www.geoplugin.net/json.gp?ip=" . $ip;

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        $ipdat = @json_decode( $result );

        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city"           => @$ipdat->geoplugin_city,
                        "state"          => @$ipdat->geoplugin_regionName,
                        "country_name"        => @$ipdat->geoplugin_countryName,
                        "country_code"   => @$ipdat->geoplugin_countryCode,
                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode,
                        "latitude"       => @$ipdat->geoplugin_latitude,
                        "longitude"      => @$ipdat->geoplugin_longitude,
                        "zip_code"       => '',
                        "org"            => '',
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }

        return $output;

    }

    public function use_freegeoip( $ip ) {

        $url = "http://freegeoip.net/json/" . $ip;

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        $ipdat = @json_decode( $result );

        $output = array();

        if (@strlen(trim($ipdat->country_code)) == 2) {

            $output = array(
                "city"           => @$ipdat->city,
                "state"          => @$ipdat->region_name,
                "country_name"   => @$ipdat->country_name,
                "country_code"   => @$ipdat->country_code,
                "continent"      => '',
                "continent_code" => '',
                "latitude"       => @$ipdat->latitude,
                "longitude"      => @$ipdat->longitude,
                "zip_code"       => @$ipdat->zip_code,
                "org"            => '',
            );

        }

        return $output;

    }

    public function use_geobytes( $ip ) {

        $url = "http://gd.geobytes.com/GetCityDetails?fqcn=" . $ip;

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        $ipdat = @json_decode( $result );

        $output = array();

        if (@strlen(trim($ipdat->geobytesinternet)) == 2) {

            $output = array(
                "city"           => @$ipdat->geobytescity,
                "state"          => @$ipdat->geobytesregion,
                "country_name"   => @$ipdat->geobytestitle,
                "country_code"   => @$ipdat->geobytesinternet,
                "continent"      => @$ipdat->geobytesmapreference,
                "continent_code" => '',
                "latitude"       => @$ipdat->geobyteslatitude,
                "longitude"      => @$ipdat->geobyteslongitude,
                "zip_code"       => '',
                "org"            => '',
            );

        }

        return $output;

    }

    public function use_ipapi( $ip ) {

        $url = "https://ipapi.co/" . $ip . "/json/";

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        $ipdat = @json_decode( $result );

        $output = array();

        if (@strlen(trim($ipdat->country)) == 2) {

            $output = array(
                "city"           => @$ipdat->city,
                "state"          => '',
                "country_name"   => @$ipdat->country_name,
                "country_code"   => @$ipdat->country,
                "continent"      => @$ipdat->timezone,
                "continent_code" => @$ipdat->continent_code,
                "latitude"       => @$ipdat->latitude,
                "longitude"      => @$ipdat->longitude,
                "zip_code"       => '',
                "org"            => @$ipdat->org,
            );

        }

        return $output;

    }

    public function use_ipdata( $ip ) {

        $url = "https://api.ipdata.co/" . $ip . "/";
        // $ipdat = @json_decode( file_get_contents( $url ) );

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        $ipdat = @json_decode( $result );

        $output = array();

        if (@strlen(trim($ipdat->country_code)) == 2) {

            $output = array(
                "city"           => @$ipdat->city,
                "state"          => @$ipdat->region,
                "country_name"   => @$ipdat->country_name,
                "country_code"   => @$ipdat->country_code,
                "continent"      => @$ipdat->continent_name,
                "continent_code" => @$ipdat->continent_code,
                "latitude"       => @$ipdat->latitude,
                "longitude"      => @$ipdat->longitude,
                "zip_code"       => @$ipdat->postal,
                "org"            => @$ipdat->organisation,
            );

        }

        return $output;

    }

    public function use_iplocate( $ip ) {

        $url = "https://www.iplocate.io/api/lookup/" . $ip . "/";

        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        $ipdat = @json_decode( $result );

        $output = array();

        if (@strlen(trim($ipdat->country_code)) == 2) {

            $output = array(
                "city"           => @$ipdat->city,
                "state"          => '',
                "country_name"   => @$ipdat->country,
                "country_code"   => @$ipdat->country_code,
                "continent"      => @$ipdat->continent,
                "continent_code" => '',
                "latitude"       => @$ipdat->latitude,
                "longitude"      => @$ipdat->longitude,
                "zip_code"       => @$ipdat->postal_code,
                "org"            => @$ipdat->org,
            );

        }

        return $output;

    }

    public function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;

        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }

        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {

            // https://ahmadawais.com/best-api-geolocating-an-ip-address/
            // $output = $this->use_geoplugin( $ip, $purpose );
            // $output = $this->use_freegeoip( $ip );
            // $output = $this->use_geobytes( $ip );
            // $output = $this->use_ipapi( $ip );
            // $output = $this->use_iplocate( $ip );
            $output = $this->use_ipdata( $ip );

        }
        return $output;
    }

    public function exopite_geo_locate( $atts ) {

        $output = '';
        $result = '';

        if ( isset( $_POST['get-location'] ) && ! empty( $_POST['ip-address'] ) ) {

            $ip = esc_attr( $_POST['ip-address'] );

            if ( preg_match('/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})\:?([0-9]{1,5})?/', $ip, $match ) ) {
               $ip = $match['1'];
            }

            $data = $this->ip_info( $ip );

            $result .= '<div class="result-wrapper"><div class="row justify-content-center"><div class="col-12 col-xs-12 col-sm-4 col-md-3 col-lg-2">IP:</div><div class="col-12 col-xs-12 col-sm-6 col-md-4 col-lg-3">' . $ip . '</div></div>';

            if ( ! empty( $data['country_code'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2"></div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3"><img src="' . EXOPITE_GEO_LOCATION_URL . '/public/css/blank.gif" class="flag flag-' . strtolower( $data['country_code'] ) . '" alt="' . $data['country_name'] . '" /></div></div>';
            }

            if ( ! empty( $data['city'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2">City:</div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">' . $data['city'] . '</div></div>';
            }
            if ( ! empty( $data['state'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2">State:</div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">' . $data['state'] . '</div></div>';
            }
            if ( ! empty( $data['country_name'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2">Country Name:</div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">' . $data['country_name'] . '</div></div>';
            }
            if ( ! empty( $data['country_code'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2">Country Code:</div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">' . $data['country_code'] . '</div></div>';
            }
            if ( ! empty( $data['zip_code'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2">ZIP Code:</div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">' . $data['zip_code'] . '</div></div>';
            }
            if ( ! empty( $data['continent'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2">Continent:</div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">' . $data['continent'] . '</div></div>';
            }
            if ( ! empty( $data['latitude'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2">Latitude:</div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">' . $data['latitude'] . '</div></div>';
            }
            if ( ! empty( $data['longitude'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2">Longitude:</div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">' . $data['longitude'] . '</div></div>';
            }
            if ( ! empty( $data['org'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2">Organization:</div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">' . $data['org'] . '</div></div>';
            }
            if ( ! empty( $data['latitude'] ) && ! empty( $data['longitude'] ) ) {
                $result .= '<div class="row justify-content-center"><div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2"></div><div class="col-6 col-xs-6 col-sm-4 col-md-4 col-lg-3">
                <a href="https://www.google.de/maps/place/' . $data['latitude'] . ',' . $data['longitude'] . '">Google Maps</a><br>
                <a href="https://www.bing.com/maps?cp=' . $data['latitude'] . '~' . $data['longitude'] . '">Bing Maps</a>
                <a href="https://www.openstreetmap.org/#map=14/' . $data['latitude'] . '/' . $data['longitude'] . '">OpenStreetMap.Org</a>
                </div></div>';
            }

            $result .= '</div>';

        }

        ob_start( );

        $ip_to_show = ( isset( $_POST['ip-address'] ) ) ? $ip : $this->get_ip_address();

        ?>
        <form method="post" class="ip-lookup-form">
            <div class="row"><div class="col-12 col-xs-12 text-center get-location-title">Lookup an IP address:</div></div>
            <div class="row"><div class="col-12 col-xs-12 text-center"><input type="text" name="ip-address" value="<?php echo $ip_to_show; ?>" class="get-location-input" ></div></div>
            <div class="row"><div class="col-12 col-xs-12 text-center"><input type="submit" name="get-location" class="get-location-submit" value="<?php esc_html_e( 'Get Location', 'exopite-geo-location' ); ?>"></div></div>

        </form>
        <?php

        $output .= ob_get_clean() . $result;

        return $output;
    }

}
