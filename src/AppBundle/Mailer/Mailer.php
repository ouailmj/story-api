<?php

/*
 * This file is part of the Instan't App project.
 *
 * (c) Instan't App <contact@instant-app.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Developed by MIT <contact@mit-agency.com>
 *
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
        $fromEmail = 'contact@instant.fr', $fromName = 'MyStorytelling')
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
     * @param string $email
     */
    public function sendAccountDeletedMessage($email)
    {
        $subject = $this->translator->trans('mail.user_deleted_header');
        $bodyMessage = $this->templateEngine->render('mail/user/user_deleted.html.twig', [
            'subject' => $subject,
        ]);
        $this->sendEmailMessage($bodyMessage, $email, $subject);
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

    /**
     * @return EngineInterface
     */
    public function getTemplateEngine()
    {
        return $this->templateEngine;
    }
}
