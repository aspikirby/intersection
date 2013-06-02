<?php header('Content-Type:application/json'); ?>

<?php require 'lib/IntersectionDAO.php'; ?>

{
    <?php $users = IntersectionDAO::getFacebookUsers(); ?>
    <?php foreach($users as $k => $user): ?>
        <?php printf("\"%s\": \"%s %s\"%s\n",
            $user['facebook_user_id'],
            $user['last_name'],
            $user['first_name'],
            $k == count($users) - 1 ? '' : ','
            );
        ?>
    <?php endforeach; ?>
}