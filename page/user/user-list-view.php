<?php

  //~ Template for user-list.php
  // variables:
  //  $title - page title
  //  $users - Users to be displayed
?>

<h1><?=$title?></h1>

<?php if (empty($users)): ?>
    <p>No users found.</p>
<?php else: ?>
    <ul class="list">
        <?php foreach ($users as $user): ?>
            <li>                
                <h3><a href="<?= Utils::createLink('detail', 
                        array('id' => $user->getId())) ?>"><?php echo Utils::escape($user->getFirstName()) . ' ' . Utils::escape($user->getLastName());?></a></h3>                 
                <p><span class="label">Username:</span> <?php 
                echo Utils::escape($user->getUsername()); 
                ?></p>
                <p><span class="label">Password:</span> <?php 
                echo Utils::escape($user->getPassword()); 
                ?></p> 
                <p><a href="index.php?module=user&page=add-edit&id=<?php echo $user->getId()?>">Edit</a> | <a href="index.php?module=user&page=delete&id=<?php echo $user->getId()?>">Delete</a></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>