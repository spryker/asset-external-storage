<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternalStorage\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
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
     * @var \Generated\Shared\Transfer\AssetExternalTransfer
     */
    protected $assetExternalTransfer;

    /**
     * All tests in here require to have a valid availability entity to work on.
     * We setup only once and let the main test method re-use this data for:
     *
     * - Gets published and synchronized after create
     * - Gets published and updated after update
     * - Rets published and removed after delete
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

        $this->assetExternalTransfer = $this->tester->haveAssetExternal([
            'tenantUuid' => 'TENANT_UUID',
            'slotKey' => 'slt-1',
            'stores' => ['DE'],
        ]);
    }

    /**
     * @disableTransaction
     *
     * @return void
     */
    public function testAvailabilityStoragePublishAndSynchronize(): void
    {
        $this->assertCreatedEntityIsSynchronizedToStorage();
        $this->assertUpdatedEntityIsUpdatedInStorage();
        $this->assertDeletedEntityIsRemovedFromStorage();
    }

    /**
     * @return void
     */
    protected function assertCreatedEntityIsSynchronizedToStorage(): void
    {
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_CREATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsSynchronizedToStorage(AssetExternalStorageConfig::ASSET_EXTERNAL_SYNC_STORAGE_QUEUE);

        $this->tester->assertStorageHasKey($this->getExpectedStorageKey());
    }

    /**
     * @return void
     */
    public function assertUpdatedEntityIsUpdatedInStorage(): void
    {
        // Act
        $assetExternalEntity = SpyAssetExternalQuery::create()->findOneByIdAssetExternal($this->assetExternalTransfer->getIdAssetExternal());

        $assetExternalEntity->setAssetContent('changed content');
        $assetExternalEntity->save();

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_UPDATE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsUpdatedInStorage(EventConstants::EVENT_QUEUE);
    }

    /**
     * @return void
     */
    public function assertDeletedEntityIsRemovedFromStorage(): void
    {
        // Act - Delete created entities
        $assetExternalEntity = SpyAssetExternalQuery::create()->findOneByIdAssetExternal($this->assetExternalTransfer->getIdAssetExternal());
        $assetExternalEntity->getSpyAssetExternalStores()->delete();
        $assetExternalEntity->delete();

        // Assert
        $this->tester->assertEntityIsPublished(AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_STORE_DELETE, EventConstants::EVENT_QUEUE);
        $this->tester->assertEntityIsRemovedFromStorage(EventConstants::EVENT_QUEUE);
    }

    /**
     * @return string
     */
    protected function getExpectedStorageKey(): string
    {
        return sprintf('asset_external_cms_slot:de:%s', 'slt-1');
    }
}
