<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStoragePersistenceFactory getFactory()
 */
class AssetExternalStorageEntityManager extends AbstractEntityManager implements AssetExternalStorageEntityManagerInterface
{
    use TransactionTrait;

    protected const ASSETS_DATA_KEY = 'assets';
    protected const ASSET_UUID_DATA_KEY = 'assetUuid';
    protected const ASSET_CONTENT_DATA_KEY = 'assetContent';
    protected const ASSET_ID_DATA_KEY = 'assetId';
    protected const CMS_SLOT_KEY_DATA_KEY = 'cmsSlotKey';

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param string $storeName
     *
     * @return void
     */
    public function createAssetExternalStorage(SpyAssetExternal $assetExternalEntity, string $storeName): void
    {
        $data[static::CMS_SLOT_KEY_DATA_KEY] = $assetExternalEntity->getSpyCmsSlot()->getKey();

        $data[static::ASSETS_DATA_KEY] = [
            [
                static::ASSET_ID_DATA_KEY => $assetExternalEntity->getIdAssetExternal(),
                static::ASSET_UUID_DATA_KEY => $assetExternalEntity->getAssetUuid(),
                static::ASSET_CONTENT_DATA_KEY => $assetExternalEntity->getAssetContent(),
            ],
        ];

        $this->getTransactionHandler()->handleTransaction(function () use ($assetExternalEntity, $storeName, $data) {
            $this->executePublishAssetExternalTransaction($assetExternalEntity, $storeName, $data);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[] $assetExternalCmsSlotsStorageEntityTransfers
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function removeAssetFromDatasByAssetExternalUuid(array $assetExternalCmsSlotsStorageEntityTransfers, int $idAssetExternal): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($assetExternalCmsSlotsStorageEntityTransfersByStoreAndCmsSlot, $idAssetExternal) {
            $this->executeRemoveAssetExternalTransaction($assetExternalCmsSlotsStorageEntityTransfers, $idAssetExternal);
        });
    }

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[] $assetExternalCmsSlotStorageEntityTransfers
     *
     * @return void
     */
    public function updateAssetExternalStoragesData(SpyAssetExternal $assetExternalEntity, array $assetExternalCmsSlotStorageEntityTransfers): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($assetExternalEntity, $assetExternalCmsSlotStorageEntityTransfers) {
            $this->updateAssetExternalStoragesDataTransaction($assetExternalCmsSlotStorageEntityTransfers, $assetExternalEntity);
        });
    }

    /**
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     *
     * @return bool
     */
    protected function updateData(
        SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity,
        SpyAssetExternal $assetExternalEntity
    ): bool {
        $data = $assetExternalCmsSlotStorageEntity->getData();

        $isUpdated = false;
        foreach ($data[static::ASSETS_DATA_KEY] as $key => $asset) {
            if ($asset[static::ASSET_ID_DATA_KEY] !== $assetExternalEntity->getIdAssetExternal()) {
                continue;
            }
            $data[static::ASSETS_DATA_KEY][$key][static::ASSET_CONTENT_DATA_KEY] = $assetExternalEntity->getAssetContent();
            $assetExternalCmsSlotStorageEntity->setData($data);
            $assetExternalCmsSlotStorageEntity->save();
            $isUpdated = true;
        }

        return $isUpdated;
    }

    /**
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     *
     * @return void
     */
    protected function createData(
        SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity,
        SpyAssetExternal $assetExternalEntity
    ): void {
        $data = $assetExternalCmsSlotStorageEntity->getData();

        $data[static::ASSETS_DATA_KEY][] = [
            static::ASSET_ID_DATA_KEY => $assetExternalEntity->getIdAssetExternal(),
            static::ASSET_UUID_DATA_KEY => $assetExternalEntity->getAssetUuid(),
            static::ASSET_CONTENT_DATA_KEY => $assetExternalEntity->getAssetContent(),
        ];
        $assetExternalCmsSlotStorageEntity->setData($data);
        $assetExternalCmsSlotStorageEntity->save();
    }

    /**
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage
     */
    protected function getSpyAssetExternalCmsSlotStorage(): SpyAssetExternalCmsSlotStorage
    {
        return new SpyAssetExternalCmsSlotStorage();
    }

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param string $storeName
     * @param array $data
     *
     * @return void
     */
    protected function executePublishAssetExternalTransaction(SpyAssetExternal $assetExternalEntity, string $storeName, array $data): void
    {
        $assetExternalCmsSlotStorage = $this->getSpyAssetExternalCmsSlotStorage();

        $assetExternalCmsSlotStorage
            ->setStore($storeName)
            ->setFkCmsSlot($assetExternalEntity->getSpyCmsSlot()->getIdCmsSlot())
            ->setCmsSlotKey($assetExternalEntity->getSpyCmsSlot()->getKey())
            ->setData($data);

        $assetExternalCmsSlotStorage->save();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[] $assetExternalCmsSlotsStorageEntityTransfers
     * @param int $idAssetExternal
     *
     * @return void
     */
    protected function executeRemoveAssetExternalTransaction(array $assetExternalCmsSlotsStorageEntityTransfers, int $idAssetExternal): void
    {
        foreach ($assetExternalCmsSlotsStorageEntityTransfers as $assetExternalCmsSlotsStorageEntityTransfer) {
            $data = $assetExternalCmsSlotsStorageEntityTransfer->getData();
            foreach ($data[static::ASSETS_DATA_KEY] as $key => $asset) {
                if ($asset[static::ASSET_ID_DATA_KEY] !== $idAssetExternal) {
                    continue;
                }
                unset($data[static::ASSETS_DATA_KEY][$key]);
                $assetExternalStorageEntity = $this->getFactory()
                    ->getRepository()
                    ->findOneAssetExternalStorageEntityByAssetExternalId($assetExternalCmsSlotsStorageEntityTransfer->getIdAssetExternalCmsSlotStorage());

                $assetExternalStorageEntity->setData($data);

                $assetExternalStorageEntity->save();
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer[] $assetExternalCmsSlotStorageEntityTransfers
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     *
     * @return void
     */
    protected function updateAssetExternalStoragesDataTransaction(
        array $assetExternalCmsSlotStorageEntityTransfers,
        SpyAssetExternal $assetExternalEntity
    ): void {
        foreach ($assetExternalCmsSlotStorageEntityTransfers as $assetExternalCmsSlotStorageEntityTransfer) {
            $isUpdated = $this->updateData($assetExternalCmsSlotStorageEntityTransfer, $assetExternalEntity);

            if (!$isUpdated) {
                $this->createData($assetExternalCmsSlotStorageEntityTransfer, $assetExternalEntity);
            }
        }
    }
}
