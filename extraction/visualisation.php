<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
        <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="chosen/chosen.css" />
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="chosen/chosen.jquery.js" type="text/javascript"></script>
        <title>Social graph</title>
    </head>
    <body>
        <?php if(isset($_GET['fb_user_1']) && isset($_GET['fb_user_2'])): ?>
            <?php var_dump($_GET['fb_user_1'], $_GET['fb_user_2']);die; ?>
        <?php endif; ?>

        <aside>
            <div class="title">
                <h1>INTERSECTION</h1>
                <h2>Facebook cross-visualization graph</h2>
            </div>
            <form action="visualisation.php" method="get">
                <fieldsetrr>
                    <label>Enter a name</label>
                    <select name="fb_user_1" class="chzn-autocomplete"></select>
                </fieldset>
                <fieldset>
                    <label>Enter a name</label>
                    <select name="fb_user_2" class="chzn-autocomplete"></select>
                </fieldset>
                <input class="btn btn-inverse btn-large" type="submit" value="Visualize" />
            </form>
            <script type="text/javascript">
                $('document').ready(function() {

                    $.getJSON("listuser.json.php")
                    .done(function(data) {
                        var items = '';

                        $.each(data, function(key, val) {
                            items += '<option value="' + key + '">' + val + '</option>';
                        });

                        $('.chzn-autocomplete').append(items);
                        $('.chzn-autocomplete').chosen();
                    })
                    .fail(function(jqxhr, textStatus, error) {
                        var err = textStatus + ', ' + error;
                        console.log( "Request Failed: " + err);
                    });
                });
            </script>
        </aside>

        <div id="output"></div>
    </body>
</html>