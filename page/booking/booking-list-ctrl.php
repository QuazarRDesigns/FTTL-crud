<?php

$head_template = new HeadTemplate;
$head_template->setTitle('Booking List');
$head_template->setDescription('List of bookings');

//$status = Utils::getUrlParam('status');
//TodoValidator::validateStatus($status);

$dao = new BookingDao();
//$search = new TodoSearchCriteria();
//$search->setStatus($status);

// data for template
$title = 'Bookings';
$sql = 'SELECT * FROM bookings WHERE status != "deleted"';
$bookings = $dao->find($sql);