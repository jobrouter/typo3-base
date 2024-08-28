<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Widgets;

use JobRouter\AddOn\Typo3Base\Extension;
use JobRouter\AddOn\Typo3Base\Widgets\Provider\TransferStatusDataProviderInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Dashboard\Widgets\AdditionalCssInterface;
use TYPO3\CMS\Dashboard\Widgets\RequestAwareWidgetInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;

/**
 * @internal Only for usage in the TYPO3 JobRouter extensions!
 */
class TransferStatusWidget implements WidgetInterface, AdditionalCssInterface, RequestAwareWidgetInterface
{
    private ServerRequestInterface $request;

    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly TransferStatusDataProviderInterface $dataProvider,
        private readonly BackendViewFactory $backendViewFactory,
        private readonly array $options = [],
    ) {}

    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function renderWidgetContent(): string
    {
        $view = $this->backendViewFactory->create($this->request);
        $view->assignMultiple([
            'status' => $this->dataProvider->getStatus(),
            'configuration' => $this->configuration,
        ]);

        return $view->render('Widget/TransferStatusWidget');
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
