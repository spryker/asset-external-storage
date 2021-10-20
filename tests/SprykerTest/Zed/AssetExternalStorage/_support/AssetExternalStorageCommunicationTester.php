<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternalStorage;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToAssetExternalBridge;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManager;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepository;

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
class AssetExternalStorageCommunicationTester extends Actor
{
    use _generated\AssetExternalStorageCommunicationTesterActions;

    /**
     * @var int
     */
    public const ID_ASSET_EXTERNAL_DEFAULT = 1;

    /**
     * @var string
     */
    public const STORE_NAME_DEFAULT = 'DE';

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return void
     */
    public function mockAssetExternalFacade(AssetExternalTransfer $assetExternalTransfer): void
    {
        $assetExternalFacadeMock = Stub::make(
            AssetExternalStorageToAssetExternalBridge::class,
            [
                'findAssetById' => $assetExternalTransfer,
            ]
        );
        $this->mockFactoryMethod('getFacadeAssetExternal', $assetExternalFacadeMock);
        $this->mockFactoryMethod('getEntityManager', new AssetExternalStorageEntityManager());
        $this->mockFactoryMethod('getRepository', new AssetExternalStorageRepository());
    }

    /**
     * @param array $expectedStorageData
     *
     * @return void
     */
    public function assertAssetExternalStorage(array $expectedStorageData): void
    {
        $this->assertCountStorageRecords(count($expectedStorageData));

        foreach ($expectedStorageData as $expectedStorageKey => $expectedStorageDataItem) {
            $assetExternalCmsSlotStorage = SpyAssetExternalCmsSlotStorageQuery::create()
                ->findOneByKey($expectedStorageKey);
            $this->assertNotNull($assetExternalCmsSlotStorage, 'no data stored');

            $data = $assetExternalCmsSlotStorage->getData();
            $this->assertSame($expectedStorageDataItem, $data, 'storage data is different from expected');
        }
    }

    /**
     * @param int $expectedQty
     *
     * @return void
     */
    public function assertCountStorageRecords(int $expectedQty): void
    {
        $count = SpyAssetExternalCmsSlotStorageQuery::create()->count();
        $this->assertSame($expectedQty, $count, 'qty of storage records is different from expected');
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return void
     */
    public function haveAssetExternalCmsSlotStorageForAssetExternalTransfer(AssetExternalTransfer $assetExternalTransfer): void
    {
        $assetExternalStorageEntityManager = new AssetExternalStorageEntityManager();

        foreach ($assetExternalTransfer->getStores() as $storeName) {
            $assetExternalStorageEntityManager->createAssetExternalStorage(
                $assetExternalTransfer,
                $storeName,
                []
            );
        }
    }
}
