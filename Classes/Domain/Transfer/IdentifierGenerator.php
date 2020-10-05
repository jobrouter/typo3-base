<?php
declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterBase\Domain\Transfer;

use TYPO3\CMS\Core\SingletonInterface;

/**
 * @internal
 */
class IdentifierGenerator implements SingletonInterface
{
    private $transferIdentifiers = [];

    public function build(string $key): string
    {
        if (!isset($this->transferIdentifiers[$key])) {
            $this->transferIdentifiers[$key] = \implode(
                '_',
                [
                    $key,
                    \substr(\md5(\uniqid('', true)), 0, 13),
                ]
            );
        }

        return $this->transferIdentifiers[$key];
    }
}
