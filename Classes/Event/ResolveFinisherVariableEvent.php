<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Event;

use Brotkrueml\JobRouterBase\Enumeration\FieldType;
use Psr\Http\Message\ServerRequestInterface;

final class ResolveFinisherVariableEvent
{
    /**
     * @param int|string $value
     * @param array<string, string> $formValues
     */
    public function __construct(
        private readonly FieldType $fieldType,
        private $value,
        private readonly string $correlationId,
        private readonly array $formValues,
        private readonly ServerRequestInterface $request,
    ) {}

    public function getFieldType(): FieldType
    {
        return $this->fieldType;
    }

    public function getValue(): int|string
    {
        return $this->value;
    }

    public function setValue(int|string $value): void
    {
        $this->value = $value;
    }

    public function getCorrelationId(): string
    {
        return $this->correlationId;
    }

    /**
     * @return array<string, string>
     */
    public function getFormValues(): array
    {
        return $this->formValues;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
