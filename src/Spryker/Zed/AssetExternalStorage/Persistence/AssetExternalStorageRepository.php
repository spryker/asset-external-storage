<?php

/**
* Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
* Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
*/

namespace Spryker\Zed\AssetExternalStorage\Persistence;

use Generated\Shared\Transfer\AssetExternalStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AssetExternalStorage\Persistence\AssetExternalStoragePersistenceFactory getFactory()
 */
class AssetExternalStorageRepository extends AbstractRepository implements AssetExternalStorageRepositoryInterface
{
    /**
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]
     */
    public function findAllAssetExternalStorage(): array
    {
        return $this->getFactory()
            ->getAssetExternalStorageQuery()
            ->find()
            ->getArrayCopy();
    }

    /**
     * @return \Orm\Zed\AssetExternalStorage\Persistence\SpyAssetExternalCmsSlotStorage[]
     */
    public function findAllAssetExternalStorageByAssetExternalId(array $ids): array
    {
        $query = $this->getFactory()->getAssetExternalStorageQuery();

        if ($ids !== []) {
            $query->filterByIdAssetExternalCmsSlotStorage_In($ids);
        }

        return $query->find()->getArrayCopy();
    }
}
