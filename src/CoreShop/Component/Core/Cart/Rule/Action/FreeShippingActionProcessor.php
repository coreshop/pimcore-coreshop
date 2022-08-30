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

namespace CoreShop\Component\Core\Cart\Rule\Action;

use CoreShop\Component\Order\Cart\Rule\Action\CartPriceRuleActionProcessorInterface;
use CoreShop\Component\Order\Factory\AdjustmentFactoryInterface;
use CoreShop\Component\Order\Model\AdjustmentInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\Model\PriceRuleItemInterface;

final class FreeShippingActionProcessor implements CartPriceRuleActionProcessorInterface
{
    public function __construct(private AdjustmentFactoryInterface $adjustmentFactory)
    {
    }

    public function applyRule(OrderInterface $cart, array $configuration, PriceRuleItemInterface $cartPriceRuleItem): bool
    {
        $shippingAdjustments = $cart->getAdjustments(AdjustmentInterface::SHIPPING);

        // Don't apply FreeShipping Conditions multiple times
        foreach ($shippingAdjustments as $adjustment) {
            if ($adjustment->getLabel() === 'FreeShipping') {
                return true;
            }
        }

        $shippingWithTax = $cart->getAdjustmentsTotal(AdjustmentInterface::SHIPPING, true);
        $shippingWithoutTax = $cart->getAdjustmentsTotal(AdjustmentInterface::SHIPPING, false);

        $cart->addAdjustment($this->adjustmentFactory->createWithData(AdjustmentInterface::SHIPPING, 'FreeShipping', -1 * $shippingWithTax, -1 * $shippingWithoutTax));

        return true;
    }

    public function unApplyRule(OrderInterface $cart, array $configuration, PriceRuleItemInterface $cartPriceRuleItem): bool
    {
        return true;
    }
}
