<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternalStorage;

use Codeception\Actor;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AssetExternalStoragePersistenceTester extends Actor
{
    use _generated\AssetExternalStoragePersistenceTesterActions;

    /**
     * @param array $expectedStorageData
     *
     * @return void
     */
    public function assertStorageData(array $expectedStorageData): void
    {
        $this->assertSame(count($expectedStorageData), $this->getStorageClient()->getCountItems());

        foreach ($expectedStorageData as $expectedStorageKey => $expectedStorageDataItem) {
            $this->assertStorageHasKey($expectedStorageKey);

            $storageData = $this->getStorageClient()->get($expectedStorageKey);
            $this->assertArrayHasKey('cmsSlotKey', $storageData);
            $this->assertSame($expectedStorageDataItem['cmsSlotKey'], $storageData['cmsSlotKey']);

            $this->assertSame($expectedStorageDataItem['assets'], $storageData['assets']);
        }
    }
}
