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
require_once("Codebase.class.php");

$secure = 's'; // or leave null to use HTTP
$c = new Codebase('username','password','hostname',$secure);

$project = $c->project('short-name-for-project');
print_r($project);
?>
```

## Available functions ##
* projects() // get all projects from account
* tickets($project) // get all tickets from project
* project($project) // get specific information about a project
* notes($ticket_id,$project) // get all notes from a ticket from a given project
* statuses($project) // get all statuses available in a project
* categories($project) // get all categories available in a project
* priorities($project) // get all priorities available in a project
* addTimeEntry($project,$params) // add a time entry to a project
* addTicket($project,$params,$files) // add a ticket to a project
* addAttachments($project,$files,$ticket_id) // add an attachment to a ticket
* note($project, $note, $ticket_id, $changes) // add a note to a ticket

### Helper functions ###

* request() // issue the curl request
* post() // wrapper for request() with POST option
* get() // wrapper for request() without POST option
* object2array() // convert PHP objects to PHP arrays


## Changelog ##
v1.0 - Added projects(), tickets(), project(), notes(), statuses(), categories(), priorities(), addTimeEntry(), addTicket(), addAttachments(), note(), request(), post(), get(), object2array()