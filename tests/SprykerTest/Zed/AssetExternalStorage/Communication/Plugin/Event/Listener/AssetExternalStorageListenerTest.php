<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\AssetExternal\Persistence\Map\SpyAssetExternalStoreTableMap;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalStore;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalStoreQuery;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorageQuery;
use Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageBusinessFactory;
use Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageFacade;
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoragePublishListener;
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoreStoragePublishListener;
use Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStorageEntityManager;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AssetExternalStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group AssetExternalStorageListenerTest
 * Add your own group annotations below this line
 */
class AssetExternalStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AssetExternalStorage\AssetExternalStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageFacade $facade
     */
    protected $facade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testAssetExternalStorageListenerData(): void
    {
        // Arrange
        SpyAssetExternalStoreQuery::create()->deleteAll();
        SpyAssetExternalQuery::create()->deleteAll();

        $this->facade = $this->getAssetExternalStorageFacade();

        (new SpyAssetExternal())
            ->setAssetUuid('uuid1234')
            ->setAssetName('firstname')
            ->setAssetContent('<br>')
            ->setFkCmsSlot(1)
            ->save();

        $assetExternalId = SpyAssetExternalQuery::create()->find()->getLast()->getIdAssetExternal();

        (new SpyAssetExternalStore())
            ->setFkAssetExternal($assetExternalId)
            ->setFkStore(1)
            ->save();

        SpyAssetExternalCmsSlotStorageQuery::create()->deleteAll();

        $assetExternalStorageListener = new AssetExternalStoragePublishListener();
        $assetExternalStorageListener->setFacade($this->facade);

        $eventTransfer = (new EventEntityTransfer())->setId($assetExternalId);

        // Act
        $assetExternalStorageListener->handle($eventTransfer, 'Entity.spy_asset_external.update');

        // Assert
        $assetExternalStorageCount = SpyAssetExternalCmsSlotStorageQuery::create()->count();

        $this->assertEquals(1, $assetExternalStorageCount);
    }

    /**
     * @return void
     */
    public function testAssetExternalStoreStorageListenerData(): void
    {
        // Arrange
        SpyAssetExternalStoreQuery::create()->deleteAll();
        SpyAssetExternalQuery::create()->deleteAll();

        $this->facade = $this->getAssetExternalStorageFacade();

        (new SpyAssetExternal())
            ->setAssetUuid('uuid1234')
            ->setAssetName('firstname')
            ->setAssetContent('<br>')
            ->setFkCmsSlot(1)
            ->save();

        $assetExternalId = SpyAssetExternalQuery::create()->find()->getLast()->getIdAssetExternal();

        (new SpyAssetExternalStore())
            ->setFkAssetExternal($assetExternalId)
            ->setFkStore(1)
            ->save();

        SpyAssetExternalCmsSlotStorageQuery::create()->deleteAll();

        $assetExternalStoreStorageListener = new AssetExternalStoreStoragePublishListener();
        $assetExternalStoreStorageListener->setFacade($this->facade);

        $eventTransfers = [
            (new EventEntityTransfer())->setForeignKeys([
                SpyAssetExternalStoreTableMap::COL_FK_ASSET_EXTERNAL => $assetExternalId,
            ]),
        ];

        // Act
        $assetExternalStoreStorageListener->handleBulk($eventTransfers, 'Entity.spy_asset_external_store.create');

        // Assert
        $assetExternalStorageCount = SpyAssetExternalCmsSlotStorageQuery::create()->count();

        $this->assertEquals(1, $assetExternalStorageCount);
    }

    /**
     * @return void
     */
    public function testAssetExternalStorageListenerDataUpdates(): void
    {
        // Arrange

        SpyAssetExternalStoreQuery::create()->deleteAll();
        SpyAssetExternalQuery::create()->deleteAll();

        $this->facade = $this->getAssetExternalStorageFacade();

        (new SpyAssetExternal())
            ->setAssetUuid('uuid1234')
            ->setAssetName('firstname')
            ->setAssetContent('<script>')
            ->setFkCmsSlot(1)
            ->save();

        $assetExternalId = SpyAssetExternalQuery::create()->find()->getLast()->getIdAssetExternal();

        (new SpyAssetExternalStore())
            ->setFkAssetExternal($assetExternalId)
            ->setFkStore(1)
            ->save();

        SpyAssetExternalCmsSlotStorageQuery::create()->deleteAll();

        (new SpyAssetExternalCmsSlotStorage())
            ->setFkCmsSlot(1)
            ->setStore('DE')
            ->setCmsSlotKey('slt-1')
            ->setData(json_encode([
                'cmsSlotKey' => 'slt-1',
                'assets' => [
                    [
                        'assetUuid' => 'someuuid1234',
                        'assetContent' => '<br>',
                    ],
                ],
            ]))
            ->save();

        $assetExternalStorageListener = new AssetExternalStoragePublishListener();
        $assetExternalStorageListener->setFacade($this->facade);

        $eventTransfer = (new EventEntityTransfer())->setId($assetExternalId);

        // Act
        $assetExternalStorageListener->handle($eventTransfer, 'Entity.spy_asset_external_store.update');

        // Assert
        $assetExternalStorageCount = SpyAssetExternalCmsSlotStorageQuery::create()->count();

        $expectedAssetExternalList = [
            'cmsSlotKey' => 'slt-1',
            'assets' => [
                [
                    'assetUuid' => 'uuid1234',
                    'assetContent' => '<script>',
                ],
            ],
        ];
        $this->assertEquals($expectedAssetExternalList, SpyAssetExternalCmsSlotStorageQuery::create()->find()->getLast()->getData());
    }

    /**
     * @return \Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageFacade
     */
    protected function getAssetExternalStorageFacade(): AssetExternalStorageFacade
    {
        $assetExternalCmsSlotStorageMock = $this->createPartialMock(SpyAssetExternalCmsSlotStorage::class, ['isSynchronizationEnabled']);
        $assetExternalCmsSlotStorageMock->method('isSynchronizationEnabled')->willReturn(false);
        $assetExtternalStorageEntityManagerMock = $this->createPartialMock(AssetExternalStorageEntityManager::class, ['getSpyAssetExternalCmsSlotStorage']);
        $assetExtternalStorageEntityManagerMock->method('getSpyAssetExternalCmsSlotStorage')->willReturn($assetExternalCmsSlotStorageMock);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageBusinessFactory $factoryMock */
        $factoryMock = $this->getMockBuilder(AssetExternalStorageBusinessFactory::class)
            ->onlyMethods(['getEntityManager'])
            ->getMock();
        $factoryMock->method('getEntityManager')->willReturn($assetExtternalStorageEntityManagerMock);

        return $this->tester->getFacadeMock($factoryMock);
    }
}
