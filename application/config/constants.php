<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



define('MANDRILL_KEY', 'MANDRILL_KEY_HERE');
define('PEPIPOST_KEY', 'PEPIPOST_KEY_HERE');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/**** USER DEFINED CONSTANTS **********/

define('ROLE_SUPER_ADMIN',                     23);
define('ROLE_ADMIN',                           1);

define('PLAN_FLEXIBLE',								2);
define('PLAN_DEDICATED',							1);

define('SEGMENT',								2);

/************************** EMAIL CONSTANTS *****************************/

define('EMAIL_FROM',                            'hello@wreely.com');		// e.g. email@example.com
define('EMAIL_BCC',                            	'tirupati.balan@gmail.com');		// e.g. email@example.com
define('FROM_NAME',                             'Wreely');	// Your system name
define('EMAIL_PASS',                            '');	// Your email password
define('PROTOCOL',                             	'smtp');				// mail, sendmail, smtp
define('SMTP_HOST',                             'smtp.pepipost.com');		// your smtp host e.g. smtp.gmail.com
define('SMTP_PORT',                             '25');					// your smtp port e.g. 25, 587
define('SMTP_USER',                             'SMTP_USER_HERE');		// your smtp user
define('SMTP_PASS',                             'SMTP_PASS_HERE');	// your smtp password
define('MAIL_PATH',                             '/usr/sbin/sendmail');


/* End of file constants.php */
/* Location: ./application/config/constants.php */
