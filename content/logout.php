<?php 
$this->template()->end();
$this->user()->access()->logout();
header('location: /');
die();
