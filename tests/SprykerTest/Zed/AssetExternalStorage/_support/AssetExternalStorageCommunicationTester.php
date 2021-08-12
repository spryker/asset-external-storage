<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternalStorage;

use Codeception\Actor;
use Spryker\Zed\AssetExternalStorage\AssetExternalStorageDependencyProvider;
use Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageFacadeInterface;
use Spryker\Zed\Kernel\Container;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\AssetExternalStorage\Business\AssetExternalStorageFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class AssetExternalStorageCommunicationTester extends Actor
{
    use _generated\AssetExternalStorageCommunicationTesterActions;

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiBusinessFactory $factoryMock
     *
     * @return \Spryker\Zed\ConfigurableBundleCartsRestApi\Business\ConfigurableBundleCartsRestApiFacadeInterface
     */
    public function getFacadeMock($factoryMock): AssetExternalStorageFacadeInterface
    {
        $container = new Container();
        $factoryMock->setContainer($container);
        $assetExternalStorageDependencyProvider = new AssetExternalStorageDependencyProvider();
        $assetExternalStorageDependencyProvider->provideBusinessLayerDependencies($container);
        $assetExternalStorageBusinessfacade = $this->getFacade();
        $assetExternalStorageBusinessfacade->setFactory($factoryMock);

        return $assetExternalStorageBusinessfacade;
    }
}
