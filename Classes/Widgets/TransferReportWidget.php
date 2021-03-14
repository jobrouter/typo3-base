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
use TYPO3\CMS\Dashboard\Widgets\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
final class TransferReportWidget implements WidgetInterface, AdditionalCssInterface
{
    /**
     * @var WidgetConfigurationInterface
     */
    private $configuration;

    /**
     * @var ListDataProviderInterface
     */
    private $dataProvider;

    /**
     * @var StandaloneView
     */
    private $view;

    public function __construct(WidgetConfigurationInterface $configuration, ListDataProviderInterface $dataProvider, StandaloneView $view)
    {
        $this->configuration = $configuration;
        $this->dataProvider = $dataProvider;
        $this->view = $view;
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplate('Widget/TransferReportWidget');

        $this->view->assignMultiple([
            'configuration' => $this->configuration,
            'items' => $this->dataProvider->getItems(),
        ]);

        return $this->view->render();
    }

    public function getCssFiles(): array
    {
        return [
            \sprintf('EXT:%s/Resources/Public/Css/widgets.css', Extension::KEY),
        ];
    }
}
