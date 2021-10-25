<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\VariableResolvers;

use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal
 */
class VariableResolver
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var string
     */
    private $correlationId;

    /**
     * @var array<string, string>
     */
    private $formValues;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setCorrelationId(string $correlationId): void
    {
        $this->correlationId = $correlationId;
    }

    /**
     * @param mixed[] $formValues
     */
    public function setFormValues(array $formValues): void
    {
        $this->formValues = $formValues;
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * @return int|string
     */
    public function resolve(int $fieldType, string $value)
    {
        if (! \str_contains($value, '{__')) {
            return $value;
        }

        $event = new ResolveFinisherVariableEvent(
            $fieldType,
            $value,
            $this->correlationId,
            $this->formValues,
            $this->request
        );
        /** @var ResolveFinisherVariableEvent $event */
        $event = $this->eventDispatcher->dispatch($event);

        return $event->getValue();
    }
}
