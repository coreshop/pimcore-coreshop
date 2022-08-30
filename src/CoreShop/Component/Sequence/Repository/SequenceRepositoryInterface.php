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

namespace CoreShop\Component\Sequence\Repository;

use CoreShop\Component\Resource\Repository\RepositoryInterface;
use CoreShop\Component\Sequence\Model\SequenceInterface;

interface SequenceRepositoryInterface extends RepositoryInterface
{
    public function findForType(string $type): ?SequenceInterface;
}
