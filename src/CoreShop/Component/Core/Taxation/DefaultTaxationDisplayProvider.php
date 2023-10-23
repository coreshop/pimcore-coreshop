<?php

declare(strict_types=1);

/*
 * CoreShop
 *
 * This source file is available under two different licenses:
 *  - GNU General Public License version 3 (GPLv3)
 *  - CoreShop Commercial License (CCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GPLv3 and CCL
 *
 */

namespace CoreShop\Component\Core\Taxation;

use CoreShop\Component\Core\Model\StoreInterface;

class DefaultTaxationDisplayProvider implements TaxationDisplayProviderInterface
{
    public function displayWithTax(array $context): bool
    {
        $store = $context['store'] ?? null;
        if ($store instanceof StoreInterface) {
            return $store->getUseGrossPrice();
        }
        return true;
    }
}
