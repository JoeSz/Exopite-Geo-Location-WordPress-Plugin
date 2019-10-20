=== Plugin Name ===
Donate link: https://www.joeszalai.org
Tags: goe location, location
Requires at least: 4.7
Tested up to: 5.2.4
Stable tag: 4.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Display IP Lookup form and enetered IP geo location with [exopite-geo-locate] shortcode.

== Description ==

This is more like a 10 min. proof of contept plugin.

[exopite-geo-locate service="service"]

Services:
- geoplugin
- freegeoip
- geobytes
- ipapi
- iplocate
- default = ipdata

Can be used with the following IP APIs, you can select them in the code.

- http://www.geoplugin.net/json.gp?ip=[IP]
- http://freegeoip.net/json/[IP]
- http://gd.geobytes.com/GetCityDetails?fqcn=[IP]
- https://ipapi.co/[IP]/json/
- https://api.ipdata.co/[IP]/
- https://www.iplocate.io/api/lookup/[IP]/

Returns if available:
- City
- State
- Country Name
- Country Code
- Continent Name
- Continent Code
- Latitude
- Longitude
- Zip Code
- Organisation

== Installation ==

1. Upload `exopite-frontend-notifications.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use one of the hooks to display messages

== Screenshots ==

1. Screenshot

== Changelog ==

= 20191020 =
* Clean up (Remove admin interface)
* Update license

= 20191017 =
* Initial release

== Disclaimer ==

All softwares and informations are provided "as is", without warranty of any kind, express or implied, including but not limited to the warranties of merchant-ability, fitness for a particular purpose and non-infringement.

Please read: https://www.joeszalai.org/disclaimer/