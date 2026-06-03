<?php
/*
Possible values for IPModus

HideIP
ShowFullIP
ShowLast1ByteOfIP
ShowLast2ByteOfIP
ShowLast3ByteOfIP

*/

$Service     = array();
$CallingHome = array();
$PageOptions = array();

$PageOptions['ContactEmail']                         = 'n9umj@proton.me';		    // Support E-Mail address

$PageOptions['DashboardVersion']                     = '1.0.1234676';       			// Dashboard Version

$PageOptions['PageRefreshActive']                    = true;          			// Activate automatic refresh
$PageOptions['PageRefreshDelay']                     = '10000';       			// Page refresh time in miliseconds


$PageOptions['RepeatersPage'] = array();
$PageOptions['RepeatersPage']['LimitTo']             = 99;            			// Number of Repeaters to show
$PageOptions['RepeatersPage']['IPModus']             = 'ShowLast2ByteOfIP'; 	// See possible options above
$PageOptions['RepeatersPage']['MasqueradeCharacter'] = '*';	        			// Character used for  masquerade


$PageOptions['PeerPage'] = array();
$PageOptions['PeerPage']['LimitTo']                  = 99;            			// Number of peers to show
$PageOptions['PeerPage']['IPModus']                  = 'ShowLast2ByteOfIP';  	// See possible options above
$PageOptions['PeerPage']['MasqueradeCharacter']      = '*';           			// Character used for  masquerade

$PageOptions['LastHeardPage']['LimitTo']             = 39;                      // Number of stations to show

$PageOptions['ModuleNames'] = array();                                			// Module nomination
$PageOptions['ModuleNames']['A']                     = 'SIN All Modes';
$PageOptions['ModuleNames']['B']                     = 'Chat Room Baker';
$PageOptions['ModuleNames']['C']                     = 'Chat Room Charlie';


$PageOptions['MetaDescription']                      = 'URF is a D-Star Reflector System for Ham Radio Operators.';  // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaKeywords']                         = 'Ham Radio, D-Star, XReflector, XLX, XRF, DCS, REF, M17,';    // Meta Tag Values, usefull forSearch Engine
$PageOptions['MetaAuthor']                           = 'N9UMJ';                                                      // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaRevisit']                          = 'After 30 Days';                                              // Meta Tag Values, usefull for Search Engine
$PageOptions['MetaRobots']                           = 'index,follow';                                               // Meta Tag Values, usefull for Search Engine

$PageOptions['UserPage']['ShowFilter']               = true;                                                         // Show Filter on Users page

$Service['PIDFile']                                  = '/var/run/xlxd.pid';
$Service['XMLFile']                                  = '/var/log/xlxd.xml';

$CallingHome['Active']                               = true;					               // xlx phone home, true or false
$CallingHome['MyDashBoardURL']                       = 'http://xlx.w9winxlx.us';			       // dashboard url
$CallingHome['ServerURL']                            = 'http://xlxapi.rlx.lu/api.php';         // database server, do not change !!!!
$CallingHome['PushDelay']                            = 10;  	                               // push delay in seconds
$CallingHome['Country']                              = "US";                         // Country
$CallingHome['Comment']                              = "Welcome to the Southern Indiana Network"; 				           // Comment. Max 100 character
$CallingHome['HashFile']                             = "/callhome/callinghome.php";             // Make sure the apache user has read and write permissions in this folder.
$CallingHome['LastCallHomefile']                     = "/callhome/lastcallhome.php";            // lastcallhome.php can remain in the tmp folder
$CallingHome['OverrideIPAddress']                    = "";                                     // Insert your IP address here. Leave blank for autodetection. No need to enter a fake address.
$CallingHome['InterlinkFile']                        = "/home/pi/urfd/config/urfd.interlink";        // Path to interlink file

/*
  include an extra config file for people who dont like to mess with shipped config.ing.php
  this makes updating dashboard from git a little bit easier
*/

if (file_exists("../config.inc.php")) {
  include ("../config.inc.php");
}

?>
