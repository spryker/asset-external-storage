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
    public const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    public const STORE_NAME_EN = 'EN';

    /**
     * @var array<string>
     */
    public const STORE_NAMES_DEFAULT = [
        self::STORE_NAME_DE,
        self::STORE_NAME_EN,
    ];

    /**
     * @var string
     */
    public const CMS_SLOT_KEY_DEFAULT = 'external-asset-header-test';

    /**
     * @var string
     */
    public const ASSET_EXTERNAL_STORAGE_KEY = 'asset_external_cms_slot';

    /**
     * @var string
     */
    protected const ASSETS_DATA_KEY = 'assets';

    /**
     * @var string
     */
    protected const ASSET_UUID_DATA_KEY = 'assetUuid';

    /**
     * @var string
     */
    protected const ASSET_CONTENT_DATA_KEY = 'assetContent';

    /**
     * @var string
     */
    protected const ASSET_ID_DATA_KEY = 'assetId';

    /**
     * @var string
     */
    protected const CMS_SLOT_KEY_DATA_KEY = 'cmsSlotKey';

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
        $count = (new SpyAssetExternalCmsSlotStorageQuery())
            ->filterByCmsSlotKey(static::CMS_SLOT_KEY_DEFAULT)
            ->count();
        $this->assertSame(count($expectedStorageData), $count, 'qty of storage records is different from expected');

        foreach ($expectedStorageData as $expectedStorageKey => $expectedStorageDataItem) {
            $assetExternalCmsSlotStorage = SpyAssetExternalCmsSlotStorageQuery::create()
                ->findOneByKey($expectedStorageKey);
            $this->assertNotNull($assetExternalCmsSlotStorage, 'no data stored');

            $data = $assetExternalCmsSlotStorage->getData();
            $this->assertSame($expectedStorageDataItem, $data, 'storage data is different from expected');
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return void
     */
    public function haveAssetExternalCmsSlotStorageForAssetExternalTransfer(AssetExternalTransfer $assetExternalTransfer): void
    {
        foreach ($assetExternalTransfer->getStores() as $storeName) {
            $assetExternalCmsSlotStorage = (new SpyAssetExternalCmsSlotStorageQuery())
                ->filterByCmsSlotKey($assetExternalTransfer->getCmsSlotKey())
                ->filterByStore($storeName)
                ->findOneOrCreate();

            $data[static::CMS_SLOT_KEY_DATA_KEY] = $assetExternalTransfer->getCmsSlotKey();

            $data[static::ASSETS_DATA_KEY] = [
                [
                    static::ASSET_ID_DATA_KEY => $assetExternalTransfer->getIdAssetExternal(),
                    static::ASSET_UUID_DATA_KEY => $assetExternalTransfer->getAssetUuid(),
                    static::ASSET_CONTENT_DATA_KEY => $assetExternalTransfer->getAssetContent(),
                ],
            ];

            $assetExternalCmsSlotStorage->setStore($storeName)
                ->setCmsSlotKey($assetExternalTransfer->getCmsSlotKey())
                ->setData($data);

            $assetExternalCmsSlotStorage->save();
        }
    }

    /**
     * @param string $storeName
     *
     * @return string
     */
    public function getStorageKey(string $storeName): string
    {
        return sprintf(
            '%s:%s:%s',
            static::ASSET_EXTERNAL_STORAGE_KEY,
            strtolower($storeName),
            static::CMS_SLOT_KEY_DEFAULT
        );
    }
}
