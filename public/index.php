<?php

require '../vendor/autoload.php';

use app\Email;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__FILE__,2));
$dotenv->load();

$email = new Email();


$sent = $email->setFrom("minhaEmpresa@gmail.com", "Empresa")->setTo("usuario@gmail.com","Usuario")->setMessage("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make ")->addTemplate('contact',['to'=>"Usuario"])->send();
echo $sent ? "email enviado" : "Falha ao enviar";