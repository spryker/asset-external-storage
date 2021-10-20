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
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStorageUnpublishListener;
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
 * @group AssetExternalStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class AssetExternalStorageUnpublishListenerTest extends Unit
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
     * @var \Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStorageUnpublishListener
     */
    protected $assetExternalStorageUnpublishListener;

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

        $this->assetExternalStorageUnpublishListener = (new AssetExternalStorageUnpublishListener())
            ->setFacade($this->tester->getFacade());
    }

    /**
     * @return void
     */
    public function testHandleWithCorrectDataSuccessfully(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())
            ->setId($this->assetExternalTransfer->getIdAssetExternal())
            ->setForeignKeys([
                AssetExternalStorageConfig::COL_CMS_SLOT_KEY => $this->assetExternalTransfer->getCmsSlotKey(),
            ]);

        $this->tester->haveAssetExternalCmsSlotStorageForAssetExternalTransfer($this->assetExternalTransfer);

        // Act
        $this->assetExternalStorageUnpublishListener->handle(
            $eventTransfer,
            AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_DELETE
        );

        // Assert
        $this->tester->assertAssetExternalStorage([
            'asset_external_cms_slot:de:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
                'assets' => [],
            ],
            'asset_external_cms_slot:en:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
                'assets' => [],
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testHandleWithoutCmsSlotKeyThrowsException(): void
    {
        // Arrange
        $eventTransfer = (new EventEntityTransfer())
            ->setId($this->assetExternalTransfer->getIdAssetExternal());

        $this->expectException(NoForeignKeyException::class);

        // Act
        $this->assetExternalStorageUnpublishListener->handle(
            $eventTransfer,
            AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_DELETE
        );
    }
}
