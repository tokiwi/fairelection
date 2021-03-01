<?php

/*
 * (c) Tokiwi SA <info@tokiwi.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Traits;

use Psr\Log\LoggerInterface;

trait AppLoggerTrait
{
    private ?LoggerInterface $logger = null;

    /**
     * @required
     */
    public function setLogger(LoggerInterface $appLogger): void
    {
        $this->logger = $appLogger;
    }

    public function logInfo(string $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->info($message, $context);
        }
    }

    private function logError(string $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->error($message, $context);
        }
    }
}
