<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\Finishers;

use Brotkrueml\JobRouterBase\Domain\Correlation\IdGenerator;
use Brotkrueml\JobRouterBase\Domain\VariableResolvers\VariableResolver;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
abstract class AbstractTransferFinisher extends AbstractFinisher
{
    /**
     * @var VariableResolver
     */
    protected $variableResolver;

    /**
     * @var IdGenerator
     */
    private $correlationIdGenerator;

    /**
     * @var string
     */
    protected $correlationId = '';

    public function injectVariableResolver(VariableResolver $variableResolver)
    {
        $this->variableResolver = $variableResolver;
    }

    public function injectIdGenerator(IdGenerator $correlationIdGenerator)
    {
        $this->correlationIdGenerator = $correlationIdGenerator;
    }

    protected function executeInternal()
    {
        $this->buildCorrelationId();
        $this->initialiseVariableResolver();

        $options = isset($this->options['handle']) ? [$this->options] : $this->options;

        foreach ($options as $option) {
            $this->options = $option;
            $this->process();
        }
    }

    protected function buildCorrelationId(): void
    {
        $this->correlationId = $this->correlationIdGenerator->build('form_' . $this->getFormIdentifier());
    }

    protected function initialiseVariableResolver(): void
    {
        $this->variableResolver->setCorrelationId($this->correlationId);
        $this->variableResolver->setFormValues($this->finisherContext->getFormValues());
        $this->variableResolver->setRequest($this->getServerRequest());
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

    protected function resolveFormFields(array $formValues, $value): string
    {
        return \str_replace(
            \array_keys($formValues),
            \array_values($formValues),
            $value
        );
    }

    protected function getServerRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
