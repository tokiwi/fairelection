<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventListener;

use App\FairElection\NoResultException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslateExceptionListener
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof NoResultException) {
            return;
        }

        $exceptionClass = \get_class($exception);
        $translatedException = new $exceptionClass($this->translator->trans($exception->getMessage()));

        $event->setThrowable($translatedException);
    }
}
