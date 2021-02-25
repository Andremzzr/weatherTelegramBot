<?php

require __DIR__.'/vendor/autoload.php';

use App\Comunication\Call;
use App\Comunication\Weather;
use App\Comunication\TelegramBot;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

Call::getLastMessage();