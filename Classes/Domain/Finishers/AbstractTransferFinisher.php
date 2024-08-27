<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Domain\Finishers;

use JobRouter\AddOn\Typo3Base\Domain\Correlation\IdGenerator;
use JobRouter\AddOn\Typo3Base\Domain\VariableResolvers\VariableResolver;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
abstract class AbstractTransferFinisher extends AbstractFinisher
{
    protected VariableResolver $variableResolver;
    private IdGenerator $correlationIdGenerator;

    /**
     * @var string
     */
    protected $correlationId = '';

    public function injectVariableResolver(VariableResolver $variableResolver): void
    {
        $this->variableResolver = $variableResolver;
    }

    public function injectIdGenerator(IdGenerator $correlationIdGenerator): void
    {
        $this->correlationIdGenerator = $correlationIdGenerator;
    }

    protected function executeInternal(): ?string
    {
        $this->buildCorrelationId();
        $this->initialiseVariableResolver();

        $options = isset($this->options['handle']) ? [$this->options] : $this->options;

        foreach ($options as $option) {
            $this->options = $option;
            $this->process();
        }

        return null;
    }

    protected function buildCorrelationId(): void
    {
        $this->correlationId = $this->correlationIdGenerator->build('form_' . $this->getFormIdentifier());
    }

    protected function initialiseVariableResolver(): void
    {
        $this->variableResolver->setCorrelationId($this->correlationId);
        $this->variableResolver->setFormValues($this->finisherContext->getFormValues());
        $this->variableResolver->setRequest($this->finisherContext->getRequest());
    }

    abstract protected function process(): void;

    protected function getFormIdentifier(): string
    {
        return $this
            ->finisherContext
            ->getFormRuntime()
            ->getFormDefinition()
            ->getIdentifier();
    }

    /**
     * @param array<string, string> $formValues
     */
    protected function resolveFormFields(array $formValues, string $value): string
    {
        $formValues = \array_map(
            static fn(string|\DateTimeInterface|null $value): ?string => $value instanceof \DateTimeInterface ? $value->format('c') : $value,
            $formValues,
        );

        return \str_replace(
            \array_keys($formValues),
            \array_values($formValues),
            $value,
        );
    }
}
