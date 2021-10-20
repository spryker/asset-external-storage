<?php

namespace SprykerTest\Zed\AssetExternalStorage\Comminication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\AssetExternal\Dependency\AssetExternalEvents;
use Spryker\Zed\AssetExternalStorage\Communication\Plugin\Event\Listener\AssetExternalStoragePublishListener;
use SprykerTest\Zed\AssetExternalStorage\AssetExternalStorageCommunicationTester;

class AssetExternalStoragePublishListenerTest extends Unit
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
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->assetExternalTransfer = $this->tester->haveAssetExternalTransfer([
            'idAssetExternal' => AssetExternalStorageCommunicationTester::ID_ASSET_EXTERNAL_DEFAULT,
        ]);
        $this->tester->mockAssetExternalFacade($this->assetExternalTransfer);
    }

    /**
     * @return void
     */
    public function testHandleWitchCorrectDataSuccessfully(): void
    {
        // Arrange
        $assetExternalStoragePublishListener = new AssetExternalStoragePublishListener();
        $assetExternalStoragePublishListener->setFacade($this->tester->getFacade());

        $eventTransfer = (new EventEntityTransfer())->setId($this->assetExternalTransfer->getIdAssetExternal());

        // Act
        $assetExternalStoragePublishListener->handle(
            $eventTransfer,
            AssetExternalEvents::ENTITY_SPY_ASSET_EXTERNAL_CREATE);

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
            'asset_external_cms_slot:en:external-asset-header' => [
                'cmsSlotKey' => 'external-asset-header',
                'assets' => [[
                    'assetId' => $this->assetExternalTransfer->getIdAssetExternal(),
                    'assetUuid' => $this->assetExternalTransfer->getAssetUuid(),
                    'assetContent' => $this->assetExternalTransfer->getAssetContent(),
                ]],
            ],
        ]);
    }
}
