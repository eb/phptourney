<?php

$inc_path = dirname(__FILE__);

require("$inc_path/helpers.inc.php");
require("$inc_path/checks.inc.php");
require("$inc_path/config-default.inc.php");
require("$inc_path/config.inc.php");
require("$inc_path/database.inc.php");

require("$inc_path/phplib/template.inc");
require("$inc_path/parsedown/Parsedown.php");

require("$inc_path/request.inc.php");
require("$inc_path/user.inc.php");
require("$inc_path/tourney_system.inc.php");
require("$inc_path/bracket.inc.php");

?>
