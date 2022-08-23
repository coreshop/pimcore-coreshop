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

namespace CoreShop\Component\Currency\Repository;

use CoreShop\Component\Currency\Model\CurrencyInterface;
use CoreShop\Component\Resource\Repository\RepositoryInterface;
use Doctrine\ORM\QueryBuilder;

interface CurrencyRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(): QueryBuilder;

    /**
     * @return CurrencyInterface[]
     */
    public function findActive(): array;

    /**
     * @return CurrencyInterface
     */
    public function getByCode(string $currencyCode): ?CurrencyInterface;
}
