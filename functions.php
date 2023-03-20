<?php
 /**
 * Functions
 */

/*
Directory
- path to the current directory
*/
define( 'DIR', dirname( __FILE__ ) );

/*
General Functions
- basic theme functions
*/
include_once(DIR . '/lib/functions/functions_general.php');
include_once(DIR . '/lib/functions/functions_woocommerce.php');
include_once(DIR . '/lib/functions/functions_cpt.php');
include_once(DIR . '/lib/functions/functions_taxonomy.php');
include_once(DIR . '/lib/functions/functions_ACF.php');