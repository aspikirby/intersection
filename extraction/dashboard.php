<?php
require 'config/config.inc.php';
require 'lib/Intersection.php';

$app = new Intersection($fb_app_id , $fb_app_secret);
?>
<html>
    <head>
        <title>Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
    </head>

    <body>
        <table class="table table-striped" id="users">
            <thead>
                <tr>
                    <th>Facebook ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($app->getUsers() as $user): ?>
                <tr>
                    <td><?php echo $user['facebook_user_id']; ?></td>
                    <td><?php echo $user['first_name']; ?></td>
                    <td><?php echo $user['last_name']; ?></td>
                    <td>
                        <a class="btn btn-inverse" href="<?php echo Intersection::getProfilApiUrl($user['facebook_user_id'], $user['facebook_access_token']); ?>" target="_blank">
                            Profil
                        </a>
                        <a class="btn btn-inverse" href="<?php echo Intersection::getFriendsApiUrl($user['facebook_user_id'], $user['facebook_access_token']); ?>" target="_blank">
                            Friends
                        </a>
                        <a class="btn btn-inverse" href="<?php echo Intersection::getGroupsApiUrl($user['facebook_user_id'], $user['facebook_access_token']); ?>" target="_blank">
                            Groups
                        </a>
                        <a class="btn btn-inverse" href="<?php echo Intersection::getEventsApiUrl($user['facebook_user_id'], $user['facebook_access_token']); ?>" target="_blank">
                            Events
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <script type="text/javascript">
            $('document').ready(function() {
                $('#users a.btn').on('click', function(event) {
                    event.preventDefault();
                    $.get($(this).attr('href'), function(data) {
                        $modalBox = '<div id="jsonoutput" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="JSONOutput" aria-hidden="true">\
                            <div class="modal-header">\
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\
                            <h3 id="JSONOutput">Modal header</h3>\
                            </div>\
                            <div class="modal-body">\
                            <p></p>\
                            </div>\
                            <div class="modal-footer">\
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>\
                            </div>\
                            </div>'
                        ;
                        if($('#jsonoutput').length == 0) {
                            $('body').append($modalBox);
                        }

                        $('#jsonoutput').find('.modal-body > p').empty().append(data);
                        $('#jsonoutput').modal("show");
                    });
                });
            });
        </script>
    </body>
</html>