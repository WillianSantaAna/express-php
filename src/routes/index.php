<?php

if (file_exists("src/public/$request.php")) {
    include "src/public/$request.php";
} else if (empty($request)) {
    include "src/public/index.php";
} else {
    include "src/public/error.php";
}
