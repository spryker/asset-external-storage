<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStoragePersistenceFactory getFactory()
 */
class AssetExternalStorageEntityManager extends AbstractEntityManager implements AssetExternalStorageEntityManagerInterface
{
    protected const ASSETS_DATA_KEY = 'assets';
    protected const ASSET_UUID_DATA_KEY = 'assetUuid';
    protected const ASSET_CONTENT_DATA_KEY = 'assetContent';
    protected const ASSET_ID_DATA_KEY = 'assetId';
    protected const CMS_SLOT_KEY_DATA_KEY = 'cmsSlotKey';

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     * @param string $storeName
     * @param string $cmsSlotKey
     * @param int $cmsSlotId
     *
     * @return void
     */
    public function createAssetExternalStorage(SpyAssetExternal $assetExternalEntity, string $storeName, string $cmsSlotKey, int $cmsSlotId): void
    {
        $data[static::CMS_SLOT_KEY_DATA_KEY] = $cmsSlotKey;

        $data[static::ASSETS_DATA_KEY] = [
            [
                static::ASSET_ID_DATA_KEY => $assetExternalEntity->getIdAssetExternal(),
                static::ASSET_UUID_DATA_KEY => $assetExternalEntity->getAssetUuid(),
                static::ASSET_CONTENT_DATA_KEY => $assetExternalEntity->getAssetContent(),
            ],
        ];

        $assetExternalCmsSlotStorage = $this->getSpyAssetExternalCmsSlotStorage();

        $assetExternalCmsSlotStorage
            ->setStore($storeName)
            ->setFkCmsSlot($cmsSlotId)
            ->setCmsSlotKey($cmsSlotKey)
            ->setData($data);

        $assetExternalCmsSlotStorage->save();
    }

    /**
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     *
     * @return bool
     */
    public function updateAssetExternalStorageData(
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
    public function createAssetExternalStorageData(
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
     * @param \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity
     * @param int $idAssetExternal
     *
     * @return void
     */
    public function removeAssetFromDataByAssetExternalUuid(SpyAssetExternalCmsSlotStorage $assetExternalCmsSlotStorageEntity, int $idAssetExternal): void
    {
        $data = $assetExternalCmsSlotStorageEntity->getData();
        foreach ($data[static::ASSETS_DATA_KEY] as $key => $asset) {
            if ($asset[static::ASSET_ID_DATA_KEY] !== $idAssetExternal) {
                continue;
            }
            unset($data[static::ASSETS_DATA_KEY][$key]);
            $assetExternalCmsSlotStorageEntity->setData($data);
            $assetExternalCmsSlotStorageEntity->save();
        }
    }

    /**
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage
     */
    protected function getSpyAssetExternalCmsSlotStorage(): SpyAssetExternalCmsSlotStorage
    {
        return new SpyAssetExternalCmsSlotStorage();
    }
}
