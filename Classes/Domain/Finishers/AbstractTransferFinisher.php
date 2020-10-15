<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\Finishers;

use Brotkrueml\JobRouterBase\Domain\Transfer\IdentifierGenerator;
use Brotkrueml\JobRouterBase\Domain\VariableResolvers\VariableResolver;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
abstract class AbstractTransferFinisher extends AbstractFinisher
{
    /** @var VariableResolver */
    protected $variableResolver;

    /** @var IdentifierGenerator */
    private $identifierGenerator;

    protected $transferIdentifier = '';

    public function injectVariableResolver(VariableResolver $variableResolver)
    {
        $this->variableResolver = $variableResolver;
    }

    public function injectIdentifierGenerator(IdentifierGenerator $identifierGenerator)
    {
        $this->identifierGenerator = $identifierGenerator;
    }

    protected function executeInternal()
    {
        $this->buildTransferIdentifier();
        $this->initialiseVariableResolver();

        if (isset($this->options['handle'])) {
            $options = [$this->options];
        } else {
            $options = $this->options;
        }

        foreach ($options as $option) {
            $this->options = $option;
            $this->process();
        }
    }

    protected function buildTransferIdentifier(): void
    {
        $this->transferIdentifier = $this->identifierGenerator->build('form_' . $this->getFormIdentifier());
    }

    protected function initialiseVariableResolver(): void
    {
        $this->variableResolver->setTransferIdentifier($this->transferIdentifier);
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
