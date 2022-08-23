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

namespace CoreShop\Component\Core\CartItem\Rule\Condition;

use CoreShop\Component\Core\Repository\CategoryRepositoryInterface;
use CoreShop\Component\Core\Rule\Condition\CategoriesConditionCheckerTrait;
use CoreShop\Component\Order\CartItem\Rule\Condition\AbstractConditionChecker;
use CoreShop\Component\Order\Model\CartPriceRuleInterface;
use CoreShop\Component\Order\Model\CartPriceRuleVoucherCodeInterface;
use CoreShop\Component\Order\Model\OrderItemInterface;
use CoreShop\Component\Product\Model\ProductInterface;
use CoreShop\Component\Resource\Model\ResourceInterface;

final class CategoriesConditionChecker extends AbstractConditionChecker
{
    use CategoriesConditionCheckerTrait {
        CategoriesConditionCheckerTrait::__construct as private __traitConstruct;
    }

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->__traitConstruct($categoryRepository);
    }

    public function isValidForOrderItem(
        OrderItemInterface $orderItem,
        CartPriceRuleInterface $cartPriceRule,
        ?CartPriceRuleVoucherCodeInterface $voucher,
        array $configuration
    ): bool {
        $categoryIdsToCheck = $this->getCategoriesToCheck(
            $configuration['categories'],
            $orderItem->getOrder()->getStore(),
            $configuration['recursive'] ?: false
        );

        $product = $orderItem->getProduct();

        if ($product instanceof ProductInterface) {
            if (!is_array($product->getCategories())) {
                return false;
            }

            foreach ($product->getCategories() as $category) {
                if ($category instanceof ResourceInterface) {
                    if (in_array($category->getId(), $categoryIdsToCheck)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
