<?php

/**
 * Created by PhpStorm.
 * User: soufianeMIT
 * Date: 05/04/18
 * Time: 15:39
 */
namespace AppBundle\Mailer;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Mailer
{


    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer       $mailer
     * @param EngineInterface     $templateEngine
     * @param TranslatorInterface $translator
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templateEngine, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
        $this->translator = $translator;
    }

    protected function sendEmailMessage(
        $body, $toEmail, $subject,
        $fromEmail = 'contact@mystorytelling.com', $fromName = 'MyStorytelling')
    {
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail, $fromName)
            ->setTo($toEmail)
            ->setBody($body)
            ->setContentType('text/html');

        return $this->mailer->send($message);
    }

    public function sendEmail($body, $toEmail, $subject)
    {
        $this->sendEmailMessage($body, $toEmail, $subject);
    }

    /**
     * Notifies a user that his account has been created.
     *
     * @param User $user
     */
    public function sendAccountCreatedMessage(User $user)
    {
        $subject = $this->translator->trans('mail.user_created.header');
        $bodyMessage = $this->templateEngine->render('mail/user/user_created.html.twig', [
            'subject' => $subject,
            'email' => $user->getEmail(),
            'password' => $user->getPlainPassword(),
        ]);
        $this->sendEmailMessage($bodyMessage, $user->getEmail(), $subject);
    }

    /**
     * Notifies a user that his account has been created.
     *
     * @param User $user
     */
    public function sendPasswordUpdatedMessage(User $user)
    {
        $subject = $this->translator->trans('mail.password_updated_header');
        $bodyMessage = $this->templateEngine->render(':mail/user:password_updated.html.twig', [
            'subject' => $subject,
            'email' => $user->getEmail(),
            'password' => $user->getPlainPassword(),
        ]);
        $this->sendEmailMessage($bodyMessage, $user->getEmail(), $subject);
    }
}
