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

namespace CoreShop\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use CoreShop\Bundle\OptimisticEntityLockBundle\Exception\OptimisticLockException;
use CoreShop\Bundle\OptimisticEntityLockBundle\Manager\EntityLockManagerInterface;
use Pimcore\Model\DataObject\Concrete;
use Webmozart\Assert\Assert;

final class OptimisticEntityLockContext implements Context
{
    public function __construct(private EntityLockManagerInterface $entityLockManager)
    {
    }

    /**
     * @Given /^I successfully lock the (object-instance) with the current version$/
     */
    public function iLockTheObjectInstanceWithCurrentVersion(Concrete $dataObject): void
    {
        $this->entityLockManager->lock($dataObject, $dataObject->getValueForFieldName('optimisticLockVersion'));
    }

    /**
     * @Given /^I unsuccessfully lock the (object-instance) with the current version$/
     * @Given /^I unsuccessfully lock the (object-instance-2) with the current version$/
     */
    public function iUnsuccessfullyLockTheObjectInstanceWithCurrentVersion(Concrete $dataObject): void
    {
        Assert::throws(function () use ($dataObject) {
            $this->entityLockManager->lock($dataObject, $dataObject->getValueForFieldName('optimisticLockVersion'));
        }, OptimisticLockException::class);
    }

    /**
     * @Given /^I unsuccessfully save versioned (object-instance)$/
     * @Given /^I unsuccessfully save versioned (object-instance-2)$/
     */
    public function iUnsuccessfullySaveTheObject(Concrete $dataObject): void
    {
        Assert::throws(function () use ($dataObject) {
            $dataObject->save();
        }, OptimisticLockException::class);
    }

    /**
     * @Given /^I successfully save versioned (object-instance)$/
     * @Given /^I successfully save versioned (object-instance-2)$/
     */
    public function iSuccessfullySaveTheObject(Concrete $dataObject): void
    {
        $dataObject->save();
    }
}
