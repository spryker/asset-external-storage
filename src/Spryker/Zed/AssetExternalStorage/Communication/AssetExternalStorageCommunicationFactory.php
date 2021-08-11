<?php

/**
* Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
* Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
*/

namespace Spryker\Zed\AssetExternalStorage\Communication;

use Spryker\Zed\AssetExternalStorage\AssetExternalStorageDependencyProvider;
use Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\AssetExternalStorage\AssetExternalStorageConfig getConfig()
 */
class AssetExternalStorageCommunicationFactory extends AbstractCommunicationFactory
{/**
 * @return \Spryker\Zed\AssetExternalStorage\Dependency\Facade\AssetExternalStorageToEventBehaviorFacadeInterface
 */
    public function getEventBehaviorFacade(): AssetExternalStorageToEventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(AssetExternalStorageDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
}
