<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Generated\Shared\Transfer\AssetExternalTransfer;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage;
use Spryker\Zed\AssetExternalStorage\Persistence\Exception\AssetExternalStorageEntityNotFound;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStoragePersistenceFactory getFactory()
 */
class AssetExternalStorageEntityManager extends AbstractEntityManager implements AssetExternalStorageEntityManagerInterface
{
    use TransactionTrait;

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
     * @param string $storeName
     * @param array $assetExternalCmsSlotStorageToDelete
     *
     * @return void
     */
    public function createAssetExternalStorage(
        AssetExternalTransfer $assetExternalTransfer,
        string $storeName,
        array $assetExternalCmsSlotStorageToDelete
    ): void {
        $data[static::CMS_SLOT_KEY_DATA_KEY] = $assetExternalTransfer->getCmsSlotKey();

        $data[static::ASSETS_DATA_KEY] = [
            [
                static::ASSET_ID_DATA_KEY => $assetExternalTransfer->getIdAssetExternal(),
                static::ASSET_UUID_DATA_KEY => $assetExternalTransfer->getAssetUuid(),
                static::ASSET_CONTENT_DATA_KEY => $assetExternalTransfer->getAssetContent(),
            ],
        ];

        $this->getTransactionHandler()->handleTransaction(function () use (
            $assetExternalTransfer,
            $storeName,
            $data,
            $assetExternalCmsSlotStorageToDelete
        ): void {
            $this->executePublishAssetExternalTransaction(
                $assetExternalTransfer,
                $storeName,
                $data,
            );

            $this->removeAssetFromDatasByIdAssetExternal(
                $assetExternalCmsSlotStorageToDelete,
                $assetExternalTransfer->getIdAssetExternal(),
            );
        });
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer> $assetExternalCmsSlotsStorageEntityTransfers
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function removeAssetFromDatasByIdAssetExternal(array $assetExternalCmsSlotsStorageEntityTransfers, int $idAssetExternal): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($assetExternalCmsSlotsStorageEntityTransfers, $idAssetExternal): void {
            $this->executeRemoveAssetExternalTransaction($assetExternalCmsSlotsStorageEntityTransfers, $idAssetExternal);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     * @param array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer> $assetExternalCmsSlotStorageToUpdate
     * @param array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer> $assetExternalCmsSlotStorageToDelete
     *
     * @return void
     */
    public function updateAssetExternalStoragesData(
        AssetExternalTransfer $assetExternalTransfer,
        array $assetExternalCmsSlotStorageToUpdate,
        array $assetExternalCmsSlotStorageToDelete
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($assetExternalTransfer, $assetExternalCmsSlotStorageToUpdate, $assetExternalCmsSlotStorageToDelete): void {
            $this->updateAssetExternalStoragesDataTransaction($assetExternalCmsSlotStorageToUpdate, $assetExternalTransfer);
            $this->executeRemoveAssetExternalTransaction($assetExternalCmsSlotStorageToDelete, $assetExternalTransfer->getIdAssetExternal());
        });
    }

    /**
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return bool
     */
    protected function updateData(
        SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity,
        AssetExternalTransfer $assetExternalTransfer
    ): bool {
        $data = $assetExternalCmsSlotStorageEntity->getData();

        $isUpdated = false;
        foreach ($data[static::ASSETS_DATA_KEY] as $key => $asset) {
            if ($asset[static::ASSET_ID_DATA_KEY] !== $assetExternalTransfer->getIdAssetExternal()) {
                continue;
            }
            $data[static::ASSETS_DATA_KEY][$key][static::ASSET_CONTENT_DATA_KEY] = $assetExternalTransfer->getAssetContent();
            $assetExternalCmsSlotStorageEntity->setData($data);
            $assetExternalCmsSlotStorageEntity->save();
            $isUpdated = true;
        }

        return $isUpdated;
    }

    /**
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return void
     */
    protected function createData(
        SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity,
        AssetExternalTransfer $assetExternalTransfer
    ): void {
        $data = $assetExternalCmsSlotStorageEntity->getData();

        $data[static::ASSETS_DATA_KEY][] = [
            static::ASSET_ID_DATA_KEY => $assetExternalTransfer->getIdAssetExternal(),
            static::ASSET_UUID_DATA_KEY => $assetExternalTransfer->getAssetUuid(),
            static::ASSET_CONTENT_DATA_KEY => $assetExternalTransfer->getAssetContent(),
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
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     * @param string $storeName
     * @param array $data
     *
     * @return void
     */
    protected function executePublishAssetExternalTransaction(
        AssetExternalTransfer $assetExternalTransfer,
        string $storeName,
        array $data
    ): void {
        $assetExternalCmsSlotStorage = $this->getSpyAssetExternalCmsSlotStorage();

        $assetExternalCmsSlotStorage
            ->setStore($storeName)
            ->setCmsSlotKey($assetExternalTransfer->getCmsSlotKey())
            ->setData($data);

        $assetExternalCmsSlotStorage->save();
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer> $assetExternalCmsSlotsStorageEntityTransfers
     * @param int $idAssetExternal
     *
     * @return void
     */
    protected function executeRemoveAssetExternalTransaction(array $assetExternalCmsSlotsStorageEntityTransfers, int $idAssetExternal): void
    {
        foreach ($assetExternalCmsSlotsStorageEntityTransfers as $assetExternalCmsSlotsStorageEntityTransfer) {
            /** @var array $data */
            $data = $assetExternalCmsSlotsStorageEntityTransfer->getData();

            foreach ($data[static::ASSETS_DATA_KEY] as $key => $asset) {
                if ($asset[static::ASSET_ID_DATA_KEY] !== $idAssetExternal) {
                    continue;
                }
                unset($data[static::ASSETS_DATA_KEY][$key]);
                $assetExternalStorageEntity = $this->getAssetExternalStorageEntityById(
                    $assetExternalCmsSlotsStorageEntityTransfer->getIdAssetExternalCmsSlotStorage(),
                );

                $assetExternalStorageEntity->setData($data);

                $assetExternalStorageEntity->save();
            }
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\SpyAssetExternalCmsSlotStorageEntityTransfer> $assetExternalCmsSlotStorageEntityTransfers
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return void
     */
    protected function updateAssetExternalStoragesDataTransaction(
        array $assetExternalCmsSlotStorageEntityTransfers,
        AssetExternalTransfer $assetExternalTransfer
    ): void {
        foreach ($assetExternalCmsSlotStorageEntityTransfers as $assetExternalCmsSlotStorageEntityTransfer) {
            $assetExternalCmsSlotStorageEntity = $this->getAssetExternalStorageEntityById(
                $assetExternalCmsSlotStorageEntityTransfer->getIdAssetExternalCmsSlotStorage(),
            );

            $isUpdated = $this->updateData($assetExternalCmsSlotStorageEntity, $assetExternalTransfer);

            if (!$isUpdated) {
                $this->createData($assetExternalCmsSlotStorageEntity, $assetExternalTransfer);
            }
        }
    }

    /**
     * @param int $idAssetExternalCmsSlotStorage
     *
     * @throws \Spryker\Zed\AssetExternalStorage\Persistence\Exception\AssetExternalStorageEntityNotFound
     *
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage
     */
    protected function getAssetExternalStorageEntityById(int $idAssetExternalCmsSlotStorage): SpyAssetExternalCmsSlotStorage
    {
        $assetExternalCmsSlotStorageEntity = $this->getFactory()
            ->createAssetExternalStorageQuery()
            ->findOneByIdAssetExternalCmsSlotStorage($idAssetExternalCmsSlotStorage);

        if (!$assetExternalCmsSlotStorageEntity) {
            throw new AssetExternalStorageEntityNotFound($idAssetExternalCmsSlotStorage);
        }

        return $assetExternalCmsSlotStorageEntity;
    }
}
