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
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoreStoragePublishListener;
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
 * @group AssetExternalStoreStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class AssetExternalStoreStoragePublishListenerTest extends Unit
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
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @var \Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoreStoragePublishListener
     */
    protected $assetExternalStoreStoragePublishListener;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->assetExternalTransfer = $this->tester->haveAssetExternalTransfer([
            'idAssetExternal' => AssetExternalStorageCommunicationTester::ID_ASSET_EXTERNAL_DEFAULT,
        ]);
        $this->tester->mockAssetExternalFacade($this->assetExternalTransfer);

        $this->storeTransfer = $this->tester->haveStore([
            'name' => AssetExternalStorageCommunicationTester::STORE_NAME_DEFAULT,
        ]);

        $this->assetExternalStoreStoragePublishListener = (new AssetExternalStoreStoragePublishListener())
            ->setFacade($this->tester->getFacade());
    }

    /**
     * @return void
     */
    public function testHandleWithCorrectDataSuccessfully(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())->setForeignKeys([
            AssetExternalStorageConfig::COL_FK_STORE => $this->storeTransfer->getIdStore(),
            AssetExternalStorageConfig::COL_FK_ASSET_EXTERNAL => $this->assetExternalTransfer->getIdAssetExternal(),
        ]);

        // Act
        $this->assetExternalStoreStoragePublishListener->handle(
            $eventTransfer,
            AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_CREATE
        );

        // Assert
        $this->tester->assertAssetExternalStorage([
            'asset_external_cms_slot:de:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
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
        $this->assetExternalStoreStoragePublishListener->handle(
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
            AssetExternalStorageConfig::COL_FK_STORE => $this->storeTransfer->getIdStore(),
        ]);

        $this->expectException(NoForeignKeyException::class);

        // Act
        $this->assetExternalStoreStoragePublishListener->handle(
            $eventTransfer,
            AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_CREATE
        );
    }
}
