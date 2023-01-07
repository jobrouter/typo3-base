<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\VariableResolvers;

use Brotkrueml\JobRouterBase\Enumeration\FieldType;
use Brotkrueml\JobRouterBase\Event\ResolveFinisherVariableEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal
 */
class VariableResolver
{
    private string $correlationId = '';
    /**
     * @var array<string, string>
     */
    private array $formValues = [];
    private ServerRequestInterface $request;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function setCorrelationId(string $correlationId): void
    {
        $this->correlationId = $correlationId;
    }

    /**
     * @param array<string, string> $formValues
     */
    public function setFormValues(array $formValues): void
    {
        $this->formValues = $formValues;
    }

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function resolve(FieldType $fieldType, string $value): int|string
    {
        if (! \str_contains($value, '{__')) {
            return $value;
        }

        $event = new ResolveFinisherVariableEvent(
            $fieldType,
            $value,
            $this->correlationId,
            $this->formValues,
            $this->request,
        );
        /** @var ResolveFinisherVariableEvent $event */
        $event = $this->eventDispatcher->dispatch($event);

        return $event->getValue();
    }
}
