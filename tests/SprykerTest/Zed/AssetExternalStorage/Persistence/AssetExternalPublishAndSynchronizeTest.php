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
            $assetUuid,
            'content',
            $this->cmsSlotTransfer->getKey(),
            'assetName'
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            1
        );

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);

        $this->assertStorageData([
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
            $assetUuid,
            'content',
            $this->cmsSlotTransfer->getKey(),
            'assetName'
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            1
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            2
        );

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);

        $this->assertStorageData([
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
            $firstAssetUuid,
            'content for the first asset',
            $this->cmsSlotTransfer->getKey(),
            'firstAssetName'
        );
        $this->tester->haveAssetExternalStoreRelation(
            $firstAssetExternalTransfer->getIdAssetExternal(),
            1
        );
        $this->tester->haveAssetExternalStoreRelation(
            $firstAssetExternalTransfer->getIdAssetExternal(),
            2
        );

        $secondAssetUuid = Uuid::uuid();
        $secondAssetExternalTransfer = $this->tester->haveAssetExternal(
            $secondAssetUuid,
            'content for the second asset',
            $this->cmsSlotTransfer->getKey(),
            'secondAssetName'
        );
        $this->tester->haveAssetExternalStoreRelation(
            $secondAssetExternalTransfer->getIdAssetExternal(),
            2
        );

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);

        $this->assertStorageData([
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
            $assetUuid,
            'content',
            $this->cmsSlotTransfer->getKey(),
            'assetName'
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            1
        );

        $assetExternalTransfer->setAssetContent('changed content');

        // Act
        $this->tester->updateAssetExternal($assetExternalTransfer);

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);

        $this->assertStorageData([
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
            $assetUuid,
            'content',
            $this->cmsSlotTransfer->getKey(),
            'assetName'
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            1
        );

        $assetExternalTransfer->setCmsSlotKey('external-asset-footer');

        // Act
        $this->tester->updateAssetExternal($assetExternalTransfer);

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);
        $this->assertStorageData([
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
            'TENANT_UUID',
            'content',
            $this->cmsSlotTransfer->getKey(),
            'assetName'
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            1
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
            'TENANT_UUID',
            'content',
            $this->cmsSlotTransfer->getKey(),
            'assetName'
        );
        $this->tester->haveAssetExternalStoreRelation(
            $assetExternalTransfer->getIdAssetExternal(),
            1
        );

        // Act
        $this->tester->deleteAssetExternal($assetExternalTransfer);

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_DELETE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsRemovedFromStorage(EventConstants::EVENT_QUEUE);
    }

    /**
     * @param string $cmsSlotKey
     * @param string $storeName
     *
     * @return string
     */
    protected function getExpectedStorageKey(string $cmsSlotKey, string $storeName): string
    {
        return sprintf('asset_external_cms_slot:%s:%s', $storeName, $cmsSlotKey);
    }

    /**
     * @param array $expectedStorageData
     *
     * @return void
     */
    protected function assertStorageData(array $expectedStorageData): void
    {
        $this->assertEquals(count($expectedStorageData), $this->tester->getStorageClient()->getCountItems());

        foreach ($expectedStorageData as $expectedStorageKey => $expectedStorageDataItem) {
            $this->tester->assertStorageHasKey($expectedStorageKey);

            $storageData = $this->tester->getStorageClient()->get($expectedStorageKey);
            $this->assertArrayHasKey('cmsSlotKey', $storageData);
            $this->assertEquals($expectedStorageDataItem['cmsSlotKey'], $storageData['cmsSlotKey']);

            $this->assertEquals($expectedStorageDataItem['assets'], $storageData['assets']);
        }
    }
}
