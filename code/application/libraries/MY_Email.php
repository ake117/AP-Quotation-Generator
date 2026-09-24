<?php defined('BASEPATH') OR exit('No direct script access allowed.');

if (version_compare(CI_VERSION, '3.1.0') >= 0) {
    require_once dirname(__FILE__).'/MY_Email_3_1_x.php';
} elseif (version_compare(CI_VERSION, '3.0') >= 0) {
    require_once dirname(__FILE__).'/MY_Email_3_0_x.php';
} else {
    require_once dirname(__FILE__).'/MY_Email_2_x.php';
}
