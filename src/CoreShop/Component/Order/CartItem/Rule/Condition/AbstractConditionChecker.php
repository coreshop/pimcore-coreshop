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

namespace CoreShop\Component\Order\CartItem\Rule\Condition;

use CoreShop\Component\Order\Model\CartPriceRuleInterface;
use CoreShop\Component\Order\Model\CartPriceRuleVoucherCodeInterface;
use CoreShop\Component\Order\Model\OrderItemInterface;
use CoreShop\Component\Resource\Model\ResourceInterface;
use CoreShop\Component\Rule\Model\RuleInterface;
use Webmozart\Assert\Assert;

abstract class AbstractConditionChecker implements OrderItemActionConditionCheckerInterface
{
    public function isValid(ResourceInterface $subject, RuleInterface $rule, array $configuration, array $params = []): bool
    {
        Assert::isInstanceOf($subject, OrderItemInterface::class);
        Assert::keyExists($params, 'cartPriceRule');
        Assert::keyExists($params, 'voucher');
        Assert::nullOrIsInstanceOf($params['voucher'], CartPriceRuleVoucherCodeInterface::class);
        Assert::isInstanceOf($params['cartPriceRule'], CartPriceRuleInterface::class);

        return $this->isValidForOrderItem($subject, $params['cartPriceRule'], $params['voucher'], $configuration);
    }
}
