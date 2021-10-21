<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternalStorage\Comminication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\AssetExternal\Dependency\AssetExternalEvents;
use Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig;
use Spryker\Zed\AssetExternalStorage\Communication\Exception\NoForeignKeyException;
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoreStorageUnpublishListener;
use SprykerTest\Zed\AssetExternalStorage\AssetExternalStorageCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AssetExternalStorage
 * @group Comminication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group AssetExternalStoreStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class AssetExternalStoreStorageUnpublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AssetExternalStorage\AssetExternalStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\AssetExternalTransfer
     */
    protected $assetExternalTransfer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected $storeTransfers = [];

    /**
     * @var \Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoreStoragePublishListener
     */
    protected $assetExternalStoreStorageUnpublishListener;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->assetExternalTransfer = $this->tester->haveAssetExternalTransfer([
            'idAssetExternal' => AssetExternalStorageCommunicationTester::ID_ASSET_EXTERNAL_DEFAULT,
            'stores' => AssetExternalStorageCommunicationTester::STORE_NAMES_DEFAULT,
        ]);
        $this->tester->mockAssetExternalFacade($this->assetExternalTransfer);

        foreach (AssetExternalStorageCommunicationTester::STORE_NAMES_DEFAULT as $storeName) {
            $this->storeTransfers[$storeName] = $this->tester->haveStore([
                'name' => $storeName,
            ]);
        }

        $this->assetExternalStoreStorageUnpublishListener = (new AssetExternalStoreStorageUnpublishListener())
            ->setFacade($this->tester->getFacade());
    }

    /**
     * @return void
     */
    public function testHandleWithCorrectDataSuccessfully(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())
            ->setForeignKeys([
            AssetExternalStorageConfig::COL_FK_STORE => $this
                ->storeTransfers[AssetExternalStorageCommunicationTester::STORE_NAME_DE]
                ->getIdStore(),
            AssetExternalStorageConfig::COL_FK_ASSET_EXTERNAL => $this->assetExternalTransfer->getIdAssetExternal(),
        ]);

        $this->tester->haveAssetExternalCmsSlotStorageForAssetExternalTransfer($this->assetExternalTransfer);

        // Act
        $this->assetExternalStoreStorageUnpublishListener->handle(
            $eventTransfer,
            AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_DELETE
        );

        // Assert
        $storageKeyDE = $this->tester
            ->getStorageKey(AssetExternalStorageCommunicationTester::STORE_NAME_DE);
        $storageKeyEN = $this->tester
            ->getStorageKey(AssetExternalStorageCommunicationTester::STORE_NAME_EN);

        $this->tester->assertAssetExternalStorage([
            $storageKeyDE => [
                'cmsSlotKey' => AssetExternalStorageCommunicationTester::CMS_SLOT_KEY_DEFAULT,
                'assets' => [],
            ],
            $storageKeyEN => [
                'cmsSlotKey' => AssetExternalStorageCommunicationTester::CMS_SLOT_KEY_DEFAULT,
                'assets' => [[
                    'assetId' => $this->assetExternalTransfer->getIdAssetExternal(),
                    'assetUuid' => $this->assetExternalTransfer->getAssetUuid(),
                    'assetContent' => $this->assetExternalTransfer->getAssetContent(),
                ]],
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testHandleWithoutFkStoreThrowsException(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())->setForeignKeys([
            AssetExternalStorageConfig::COL_FK_ASSET_EXTERNAL => $this->assetExternalTransfer->getIdAssetExternal(),
        ]);

        $this->expectException(NoForeignKeyException::class);

        // Act
        $this->assetExternalStoreStorageUnpublishListener->handle(
            $eventTransfer,
            AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_CREATE
        );
    }

    /**
     * @return void
     */
    public function testHandleWithoutFkAssetExternalThrowsException(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())->setForeignKeys([
            AssetExternalStorageConfig::COL_FK_STORE => $this
                ->storeTransfers[AssetExternalStorageCommunicationTester::STORE_NAME_DE]
                ->getIdStore(),
        ]);

        $this->expectException(NoForeignKeyException::class);

        // Act
        $this->assetExternalStoreStorageUnpublishListener->handle(
            $eventTransfer,
            AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_CREATE
        );
    }
}
