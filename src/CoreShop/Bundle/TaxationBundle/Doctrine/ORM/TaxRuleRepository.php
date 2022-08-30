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

namespace CoreShop\Bundle\TaxationBundle\Doctrine\ORM;

use CoreShop\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use CoreShop\Component\Taxation\Model\TaxRuleGroupInterface;
use CoreShop\Component\Taxation\Repository\TaxRuleRepositoryInterface;

class TaxRuleRepository extends EntityRepository implements TaxRuleRepositoryInterface
{
    public function findByGroup(TaxRuleGroupInterface $group): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.taxRuleGroup = :taxRuleGroup')
            ->setParameter('taxRuleGroup', $group)
            ->getQuery()
            ->getResult();
    }
}
