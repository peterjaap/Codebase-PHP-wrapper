Codebase-PHP-wrapper
====================

Provides a re-usable PHP library for interacting with Codebase HQ (www.codebasehq.com / @codebase)

* Author: Peter Jaap Blaakmeer http://blaakmeer.com
* Twitter: @PeterJaap
* License: GPLv2/MIT

## Requirements ##

* PHP 5.3 or higher
* [cURL](http://us.php.net/manual/en/book.curl.php) extension
* [JSON](http://us.php.net/manual/en/book.json.php) extension

## Usage ##
```
<?php
require_once("codebase/codebase.php");

$secure = 's'; // or leave null to use HTTP
$c = new Codebase('username','password','hostname',$secure);

$project = $c->project('short-name-for-project');
print_r($project);
?>
```