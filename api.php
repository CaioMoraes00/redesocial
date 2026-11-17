<?php

header("content-type: application/json; charset=UTF-8");
header("access-control-allow-origin: *");
header("access-control-allow-methods: GET, POST, PUT, DELETE");

require _DIR_ . "/config.php";
require _DIR_ . "/routes/rotas.php";