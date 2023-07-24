<?php

namespace app;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    private PHPMailer $mail;

    private string $toEmail;
    private string $toName = "";
    private string $fromEmail;
    private string $fromName = "";
    private string $subject = "";
    private string $message = "";
    private array $templateData = [];
    private string $templateName = "";
    private string $template = "";




    public function __construct()
    {
        try {
            //Server settings
            $this->mail = new PHPMailer();
            $this->mail->isSMTP();
            $this->mail->Host       = $_ENV['HOST'];
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $_ENV['USERNAME'];
            $this->mail->Password   = $_ENV['PASSWORD'];
            $this->mail->Port       = $_ENV['PORT'];
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setFrom($fromEmail, $fromName = "")
    {
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;

        return $this;
    }

    public function setTo($toEmail, $toName = "")
    {
        $this->toEmail = $toEmail;
        $this->toName = $toName;
        return $this;
    }

    public function addTemplate(string $template, array $templateData = [])
    {
        $path = dirname(__FILE__, 2) . "/public/templates/{$template}.html";
        if (!file_exists($path)) {
            throw new Exception("O arquivo {$template} não existe");
        }
        $this->templateName = $template;
        $templateContent = file_get_contents($path);
        $this->template = $templateContent;
        $this->templateData = $templateData;
        return $this;
    }

    public function sendWithTemplate()
    {
        $templateData = [];
        $this->templateData['message'] = $this->message;
        foreach ($this->templateData as $key => $value) {
            $templateData["@{$key}"] = $value;
        }
        return str_replace(array_keys($templateData), array_values($templateData), $this->template);
    }
    public function send()
    {
        $this->mail->setFrom($this->fromEmail, $this->fromName);
        $this->mail->addAddress($this->toEmail, $this->toName);

        $this->mail->isHTML(true);
        $this->mail->CharSet = "UTF8";
        $this->mail->Subject = $this->subject;
        $this->mail->Body    = empty($this->templateName) ? $this->message : $this->sendWithTemplate();
        $this->mail->AltBody = $this->message;

        return $this->mail->send();
    }
}
