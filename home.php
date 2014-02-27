<html>
    <head>
        <script>
            function onKeyUp() {
                var env = document.getElementById('env');
                var whatImage = document.getElementById('whatImage');
                var minRadius = document.getElementById('minRadius');
                var maxRadius = document.getElementById('maxRadius');
                var sensitivity = document.getElementById('sensitivity');

                var findCircles = document.getElementById('findCircles');

                findCircles.href =  window.location.href;
                findCircles.href += (env.value) ?
                    ('env/' + env.value + '/') : '';
                findCircles.href += 'find-circles/';
                findCircles.href += (whatImage.value) ?
                    ('in/' + whatImage.value + '/') : '';
                findCircles.href += (minRadius.value) ?
                    ('min-radius/' + minRadius.value + '/') : '';
                findCircles.href += (maxRadius.value) ?
                    ('max-radius/' + maxRadius.value + '/') : '';
                findCircles.href += (sensitivity.value) ?
                    ('sensitivity/' + sensitivity.value + '/') : '';

                findCircles.innerHTML = findCircles.href;
            }
        </script>
        <style>
            body {
                font-size: 1.1em;
            }
            input {
                font-size: 0.9em;
                width:5em;
                text-align:center;
            }
            label {
                width: 12em;
                display:inline-block;
            }
        </style>
    </head>
    <body>
        <h1>Incore</h1>

        <label for="env">Enviroment (dev | prod):</label>
        <input
            id="env"
            value="dev"
            onkeyup="onKeyUp()"><br>
        <label for="whatImage">What image (default | last):</label>
        <input
            id="whatImage"
            value="last"
            onkeyup="onKeyUp()"><br>
        <label for="minRadius">Minimum radius:</label>
        <input
            id="minRadius"
            value="<?php print DEFAULT_MIN_RADIUS; ?>"
            onkeyup="onKeyUp()"><br>
        <label for="maxRadius">Maximum radius:</label>
        <input
            id="maxRadius"
            value="<?php print DEFAULT_MAX_RADIUS; ?>"
            onkeyup="onKeyUp()"><br>
        <label for="sensitivity">Sensitivity [0.85, 1]:</label>
        <input
            id="sensitivity"
            value="<?php print DEFAULT_SENSITIVITY; ?>"
            onkeyup="onKeyUp()"><br>

        <br>
        <div><strong>Find circles:</strong></div>
        <a
            id="findCircles"
            href="
            <?php
            print 'http://' . HOST . $_SERVER['REQUEST_URI'] .
                'env/dev/find-circles/in/last/min-radius/20' .
                '/max-radius/50/sensitivity/0.9/';
            ?>">
            <?php
            print 'http://' . HOST . $_SERVER['REQUEST_URI'] .
                'env/dev/find-circles/in/last/min-radius/20' .
                '/max-radius/50/sensitivity/0.9/';
            ?>
        </a>
        <br>
        <br>
        <strong>Source images:</strong><br>
        Last image:
        <a href="<?php print SHOW_LAST_IMAGE; ?>">
            <?php print SHOW_LAST_IMAGE; ?>
        </a><br>
        Default image:
        <a href="<?php print SHOW_DEFAULT_IMAGE; ?>">
            <?php print SHOW_DEFAULT_IMAGE; ?>
        </a><br>
        <br>
        <strong>Result images:</strong><br>
        Last image:
        <a href="<?php print SHOW_LAST_IMAGE_RESULT; ?>">
            <?php print SHOW_LAST_IMAGE_RESULT; ?>
        </a><br>
        Default image:
        <a href="<?php print SHOW_DEFAULT_IMAGE_RESULT; ?>">
            <?php print SHOW_DEFAULT_IMAGE_RESULT; ?>
        </a><br>
    </body>
</html>