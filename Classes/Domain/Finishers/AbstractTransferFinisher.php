<?php
declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\Finishers;

use Brotkrueml\JobRouterBase\Domain\VariableResolver\VariableResolver;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
abstract class AbstractTransferFinisher extends AbstractFinisher
{
    /** @var VariableResolver */
    protected $variableResolver;

    protected $transferIdentifier = '';

    public function injectVariableResolver(VariableResolver $variableResolver)
    {
        $this->variableResolver = $variableResolver;
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
        $this->transferIdentifier = \implode(
            '_',
            [
                'form',
                $this->getFormIdentifier(),
                \substr(\md5(\uniqid('', true)), 0, 13),
            ]
        );
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
