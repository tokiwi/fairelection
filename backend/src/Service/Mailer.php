<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

class Mailer
{
    private RequestStack $requestStack;
    private MailerInterface $mailer;
    private TranslatorInterface $translator;
    private string $clientUrl;
    private string $defaultLocale;

    public function __construct(RequestStack $requestStack, MailerInterface $mailer, TranslatorInterface $translator, string $clientUrl, string $defaultLocale)
    {
        $this->requestStack = $requestStack;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->clientUrl = $clientUrl;
        $this->defaultLocale = $defaultLocale;
    }

    public function sendUserResetPasswordRequest(User $user, string $token): RawMessage
    {
        $toAddress = new Address($user->getEmail(), $user->getFullName() ?? '');

        $email = (new TemplatedEmail())
            ->to($toAddress)
            ->subject($this->translator->trans('reset_password_request.title', [], 'emails'))
            ->textTemplate('email/reset_password_request.text.twig')
            ->htmlTemplate('email/reset_password_request.html.twig')
            ->context([
                'url' => sprintf('%s/reset-password/%s', $this->clientUrl, $token),
                'token' => $token,
                'requestLocale' => null !== $this->requestStack->getMasterRequest() ?
                    $this->requestStack->getMasterRequest()->getLocale() :
                    $this->defaultLocale,
        ]);

        $this->mailer->send($email);

        return $email;
    }
}
