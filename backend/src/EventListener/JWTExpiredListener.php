<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class JWTExpiredListener
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        /** @var JWTAuthenticationFailureResponse */
        $response = $event->getResponse();

        $response->setMessage($this->translator->trans('message.jwt_expired'));
    }
}
