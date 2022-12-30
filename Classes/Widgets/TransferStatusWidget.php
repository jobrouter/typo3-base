<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Widgets;

use Brotkrueml\JobRouterBase\Extension;
use Brotkrueml\JobRouterBase\Widgets\Provider\TransferStatusDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
class TransferStatusWidget implements WidgetInterface, AdditionalCssInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly TransferStatusDataProviderInterface $dataProvider,
        private readonly StandaloneView $view,
        private readonly array $options = []
    ) {
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplate('Widget/TransferStatusWidget');
        $this->view->assignMultiple([
            'status' => $this->dataProvider->getStatus(),
            'configuration' => $this->configuration,
        ]);

        return $this->view->render();
    }

    /**
     * @return string[]
     */
    public function getCssFiles(): array
    {
        return [
            \sprintf('EXT:%s/Resources/Public/Css/widgets.css', Extension::KEY),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
