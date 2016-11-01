<?php

  //~ Template for list.php
  // variables:
  //  $title - page title
  //  $status - status of TODOs to be displayed
  //  $bookings - TODOs to be displayed
?>

<h1><?=$title?></h1>

<?php if (empty($bookings)): ?>
    <p>No bookings found.</p>
<?php else: ?>
    <ul class="list">
        <?php foreach ($bookings as $booking): ?>
            <li>                
                <h3><a href="<?= Utils::createLink('detail', 
                        array('id' => $booking->getId())) ?>"><?php 
                        echo Utils::escape($booking->getFlightName()); ?></a></h3>                
                <p><span class="label">Created On:</span> <?php 
                echo Utils::escape(Utils::formatDateTime($booking->getDateCreated())); 
                ?></p> 
                <p><a href="index.php?module=booking&page=delete&id=<?php echo $booking->getId()?>">Delete</a></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>