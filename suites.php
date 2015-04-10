<?php
require './res/php/bouncer.php';

require './res/html/head.html';

if(isset($_GET["suite"])) {
        getSuite($_GET['suite']);
    } else {
        require './res/html/suites.html';
    }

require './res/html/footer.html';

function getSuite($suite) {
    if($suite === "sta") {
        require './res/html/stasuite.html';
    } else if($suite === "lux") {
        require './res/html/luxsuite.html';
    } else {
        require './res/html/wrongsuite.html';
    }
}

?>
