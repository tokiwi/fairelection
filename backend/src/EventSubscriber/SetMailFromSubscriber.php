<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

class SetMailFromSubscriber implements EventSubscriberInterface
{
    private TranslatorInterface $translator;
    private string $fromEmail;

    public function __construct(TranslatorInterface $translator, string $fromEmail)
    {
        $this->translator = $translator;
        $this->fromEmail = $fromEmail;
    }

    public function onMessage(MessageEvent $event): void
    {
        $email = $event->getMessage();

        if (!$email instanceof Email) {
            return;
        }

        $address = new Address($this->fromEmail, $this->translator->trans('title.site_name', [], 'site'));

        $email->from($address);
        $email->replyTo($address);
    }

    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => 'onMessage',
        ];
    }
}
