<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternalStorage\Persistence;

use Codeception\Test\Unit;
use Faker\Provider\Uuid;
use Generated\Shared\Transfer\CmsSlotTransfer;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Spryker\Shared\AssetExternalStorage\AssetExternalStorageConfig;
use Spryker\Shared\Event\EventConstants;
use Spryker\Zed\AssetExternal\Dependency\AssetExternalEvents;
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Subscriber\AssetExternalStorageEventSubscriber;
use Spryker\Zed\Event\Communication\Plugin\Queue\EventQueueMessageProcessorPlugin;
use Spryker\Zed\Queue\QueueDependencyProvider;
use Spryker\Zed\Synchronization\Communication\Plugin\Queue\SynchronizationStorageQueueMessageProcessorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AssetExternalStorage
 * @group Persistence
 * @group AssetExternalPublishAndSynchronizeTest
 * Add your own group annotations below this line
 */
class AssetExternalPublishAndSynchronizeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AssetExternalStorage\AssetExternalStoragePersistenceTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CmsSlotTransfer
     */
    protected $cmsSlotTransfer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeDeTransfer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeAtTransfer;

    /**
     * All tests in here require to have a valid availability entity to work on.
     * We setup only once and let the main test method re-use this data for:
     *
     * - Gets published and synchronized after create
     * - Gets published and updated after update
     * - Gets published and removed after delete
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->addEventSubscriber(new AssetExternalStorageEventSubscriber());

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_MESSAGE_PROCESSOR_PLUGINS, [
            EventConstants::EVENT_QUEUE => new EventQueueMessageProcessorPlugin(),
            AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE => new SynchronizationStorageQueueMessageProcessorPlugin(),
        ]);

        $this->cmsSlotTransfer = $this->tester->haveCmsSlot([
            CmsSlotTransfer::KEY => 'external-asset-header',
        ]);

        $this->storeDeTransfer = $this->tester->haveStore([
            'name' => 'DE',
        ]);

        $this->storeAtTransfer = $this->tester->haveStore([
            'name' => 'AT',
        ]);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tester->getStorageClient()->deleteAll();
        SpyAssetExternalCmsSlotStorageQuery::create()->deleteAll();

        parent::tearDown();
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testAssetExternalCreateStoragePublishAndSynchronize(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid();
        $assetExternalTransfer = $this->tester->haveAssetExternal(
            ['cmsSlotKey' => $this->cmsSlotTransfer->getKey()],
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            $this->storeDeTransfer->getIdStore()
        );

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);

        $this->tester->assertStorageData([
            'asset_external_cms_slot:de:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
                'assets' => [[
                    'assetId' => $assetExternalTransfer->getIdAssetExternal(),
                    'assetUuid' => $assetExternalTransfer->getAssetUuid(),
                    'assetContent' => $assetExternalTransfer->getAssetContent(),
                ]],
            ],
        ]);
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testAssetExternalStoreCreateStoragePublishAndSynchronize(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid();
        $assetExternalTransfer = $this->tester->haveAssetExternal(
            ['cmsSlotKey' => $this->cmsSlotTransfer->getKey()],
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            $this->storeDeTransfer->getIdStore()
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            $this->storeAtTransfer->getIdStore()
        );

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);

        $this->tester->assertStorageData([
            'asset_external_cms_slot:de:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
                'assets' => [[
                    'assetId' => $assetExternalTransfer->getIdAssetExternal(),
                    'assetUuid' => $assetExternalTransfer->getAssetUuid(),
                    'assetContent' => $assetExternalTransfer->getAssetContent(),
                ]],
            ],
            'asset_external_cms_slot:at:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
                'assets' => [[
                    'assetId' => $assetExternalTransfer->getIdAssetExternal(),
                    'assetUuid' => $assetExternalTransfer->getAssetUuid(),
                    'assetContent' => $assetExternalTransfer->getAssetContent(),
                ]],
            ],
        ]);
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testSeveralAssetExternalCreatedInOneCmsSlotStoragePublishAndSynchronize(): void
    {
        // Arrange
        $firstAssetUuid = Uuid::uuid();
        $firstAssetExternalTransfer = $this->tester->haveAssetExternal(
            ['cmsSlotKey' => $this->cmsSlotTransfer->getKey()],
        );
        $this->tester->haveAssetExternalStoreRelation(
            $firstAssetExternalTransfer->getIdAssetExternal(),
            $this->storeDeTransfer->getIdStore()
        );
        $this->tester->haveAssetExternalStoreRelation(
            $firstAssetExternalTransfer->getIdAssetExternal(),
            $this->storeAtTransfer->getIdStore()
        );

        $secondAssetUuid = Uuid::uuid();
        $secondAssetExternalTransfer = $this->tester->haveAssetExternal(
            ['cmsSlotKey' => $this->cmsSlotTransfer->getKey()],
        );
        $this->tester->haveAssetExternalStoreRelation(
            $secondAssetExternalTransfer->getIdAssetExternal(),
            $this->storeAtTransfer->getIdStore()
        );

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);

        $this->tester->assertStorageData([
            'asset_external_cms_slot:de:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
                'assets' => [[
                    'assetId' => $firstAssetExternalTransfer->getIdAssetExternal(),
                    'assetUuid' => $firstAssetExternalTransfer->getAssetUuid(),
                    'assetContent' => $firstAssetExternalTransfer->getAssetContent(),
                ]],
            ],
            'asset_external_cms_slot:at:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
                'assets' => [
                    [
                        'assetId' => $firstAssetExternalTransfer->getIdAssetExternal(),
                        'assetUuid' => $firstAssetExternalTransfer->getAssetUuid(),
                        'assetContent' => $firstAssetExternalTransfer->getAssetContent(),
                    ],
                    [
                        'assetId' => $secondAssetExternalTransfer->getIdAssetExternal(),
                        'assetUuid' => $secondAssetExternalTransfer->getAssetUuid(),
                        'assetContent' => $secondAssetExternalTransfer->getAssetContent(),
                    ],
                ],
            ],
        ]);
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testAssetExternalUpdateContentStoragePublishAndSynchronize(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid();
        $assetExternalTransfer = $this->tester->haveAssetExternal(
            ['cmsSlotKey' => $this->cmsSlotTransfer->getKey()],
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            $this->storeDeTransfer->getIdStore()
        );

        $assetExternalTransfer->setAssetContent('changed content');

        // Act
        $this->tester->updateAssetExternal($assetExternalTransfer);

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);

        $this->tester->assertStorageData([
            'asset_external_cms_slot:de:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
                'assets' => [[
                    'assetId' => $assetExternalTransfer->getIdAssetExternal(),
                    'assetUuid' => $assetExternalTransfer->getAssetUuid(),
                    'assetContent' => $assetExternalTransfer->getAssetContent(),
                ]],
            ],
        ]);
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testAssetExternalUpdateCmsSlotStoragePublishAndSynchronize(): void
    {
        // Arrange
        $assetUuid = Uuid::uuid();
        $assetExternalTransfer = $this->tester->haveAssetExternal(
            ['cmsSlotKey' => $this->cmsSlotTransfer->getKey()],
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            $this->storeDeTransfer->getIdStore()
        );

        $assetExternalTransfer->setCmsSlotKey('external-asset-footer');

        // Act
        $this->tester->updateAssetExternal($assetExternalTransfer);

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);
        $this->tester->assertStorageData([
            'asset_external_cms_slot:de:external-asset-footer' => [
                'cmsSlotKey' => 'external-asset-footer',
                'assets' => [[
                    'assetId' => $assetExternalTransfer->getIdAssetExternal(),
                    'assetUuid' => $assetExternalTransfer->getAssetUuid(),
                    'assetContent' => $assetExternalTransfer->getAssetContent(),
                ]],
            ],
        ]);

        $this->tester->assertStorageNotHasKey('asset_external_cms_slot:de:external-asset-header');
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testAssetExternalDeleteStoragePublishAndSynchronize(): void
    {
        // Arrange
        $assetExternalTransfer = $this->tester->haveAssetExternal(
            ['cmsSlotKey' => $this->cmsSlotTransfer->getKey()],
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            $this->storeDeTransfer->getIdStore()
        );

        // Act
        $this->tester->deleteAssetExternal($assetExternalTransfer);

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_DELETE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsRemovedFromStorage(EventConstants::EVENT_QUEUE);
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testAssetExternalStoreDeleteStoragePublishAndSynchronize(): void
    {
        // Arrange
        $assetExternalTransfer = $this->tester->haveAssetExternal(
            ['cmsSlotKey' => $this->cmsSlotTransfer->getKey()],
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            $this->storeDeTransfer->getIdStore()
        );

        // Act
        $this->tester->deleteAssetExternal($assetExternalTransfer);

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_DELETE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsRemovedFromStorage(EventConstants::EVENT_QUEUE);
    }
}
