<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GPLv3 and CCL
 */

declare(strict_types=1);

namespace CoreShop\Bundle\CurrencyBundle\DependencyInjection\Compiler;

use CoreShop\Component\Currency\Context\CompositeCurrencyContext;
use CoreShop\Component\Currency\Context\CurrencyContextInterface;
use CoreShop\Component\Registry\PrioritizedCompositeServicePass;

final class CompositeCurrencyContextPass extends PrioritizedCompositeServicePass
{
    public const CURRENCY_CONTEXT_SERVICE_TAG = 'coreshop.context.currency';

    public function __construct()
    {
        parent::__construct(
            CurrencyContextInterface::class,
            CompositeCurrencyContext::class,
            self::CURRENCY_CONTEXT_SERVICE_TAG,
            'addContext'
        );
    }
}
