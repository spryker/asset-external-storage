<?php
namespace SprykerTest\Zed\AssetExternalStorage;

use Codeception\Stub;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToAssetExternalBridge;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToStoreFacadeBridge;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManager;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageRepository;

/**
 * Inherited Methods
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
class AssetExternalStorageCommunicationTester extends \Codeception\Actor
{
    use _generated\AssetExternalStorageCommunicationTesterActions;

    /**
     * @var int
     */
    public const ID_ASSET_EXTERNAL_DEFAULT = 1;

    /**
     * @var int
     */
    public const STORE_NAME_DEFAULT = 'DE';

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return void
     */
    public function mockAssetExternalFacade(AssetExternalTransfer $assetExternalTransfer): void
    {
        $assetExternalFacadeMock = Stub::make(AssetExternalStorageToAssetExternalBridge::class,
            [
                'findAssetById' => $assetExternalTransfer,
            ]);
        $this->mockFactoryMethod('getFacadeAssetExternal', $assetExternalFacadeMock);
        $this->mockFactoryMethod('getEntityManager', new AssetExternalStorageEntityManager());
        $this->mockFactoryMethod('getRepository', new AssetExternalStorageRepository());
    }

    /**
     * @param int $beforeCount
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
