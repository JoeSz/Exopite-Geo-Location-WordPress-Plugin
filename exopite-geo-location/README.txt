=== Plugin Name ===
Donate link: https://www.joeszalai.org
Tags: goe location, location
Requires at least: 4.8
Tested up to: 5.3
Stable tag: 4.8
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

= 20191112 =
* Update Plugin Update Checker to 4.8

= 20191020 =
* Clean up (Remove admin interface)
* Update license

= 20191017 =
* Initial release

== SUPPORT/UPDATES ==

If you use my program(s), I would **greatly appreciate it if you kindly give me some suggestions/feedback**. If you solve some issue or fix some bugs or add a new feature, please share with me or mke a pull request. (But I don't have to agree with you or necessarily follow your advice.)<br/>
**Before open an issue** please read the readme (if any :) ), use google and your brain to try to solve the issue by yourself. After all, Github is for developers.<br/>
My **updates will be irregular**, because if the current stage of the program fulfills all of my needs or I do not encounter any bugs, then I have nothing to do.<br/>
**I provide no support.** I wrote these programs for myself. For fun. For free. In my free time. It does not have to work for everyone. However, that does not mean that I do not want to help.<br/>
I've always tested my codes very hard, but it's impossible to test all possible scenarios. Most of the problem could be solved by a simple google search in a matter of minutes. I do the same thing if I download and use a plugin and I run into some errors/bugs.

== Disclaimer ==

All softwares and informations are provided "as is", without warranty of any kind, express or implied, including but not limited to the warranties of merchant-ability, fitness for a particular purpose and non-infringement.

Please read: https://www.joeszalai.org/disclaimer/
