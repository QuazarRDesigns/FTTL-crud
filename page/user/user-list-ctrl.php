<?php

$head_template = new HeadTemplate;
$head_template->setTitle('User List');
$head_template->setDescription('List of users bookings');

//$status = Utils::getUrlParam('status');
//TodoValidator::validateStatus($status);

$dao = new UserDao();
//$search = new TodoSearchCriteria();
//$search->setStatus($status);

// data for template
$title = 'Users';
$sql = 'SELECT * FROM users WHERE status != "deleted"';
$users = $dao->find($sql);