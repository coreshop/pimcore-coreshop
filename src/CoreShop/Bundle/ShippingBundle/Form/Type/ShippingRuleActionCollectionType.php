<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2019 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Bundle\ShippingBundle\Form\Type;

use CoreShop\Bundle\RuleBundle\Form\Type\RuleActionCollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ShippingRuleActionCollectionType extends RuleActionCollectionType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('entry_type', ShippingRuleActionType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'coreshop_product_shipping_action_collection';
    }
}
