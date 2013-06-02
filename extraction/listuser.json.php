<?php

require 'lib/IntersectionDAO.php';

$users = IntersectionDAO::getFacebookUsers();

?>

{
<?php foreach($users as $user): ?>
  <?php sprintf("%s: '%s' '%s'",
  $user['facebook_user_id'],
  $user['last_name'],
  $user['first_name']
  );
  ?>
<?php end foreach; ?>
}
