<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_base" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Base\Enumeration;

enum FieldType: int
{
    case Text = 1;
    case Integer = 2;
    case Decimal = 3;
    case Date = 4;
    case DateTime = 5;
    case Attachment = 6;
}
