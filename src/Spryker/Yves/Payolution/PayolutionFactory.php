<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payolution;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Payolution\Form\DataProvider\InstallmentDataProvider;
use Spryker\Yves\Payolution\Form\DataProvider\InvoiceDataProvider;
use Spryker\Yves\Payolution\Form\InstallmentSubForm;
use Spryker\Yves\Payolution\Form\InvoiceSubForm;
use Spryker\Yves\Payolution\Handler\PayolutionHandler;

class PayolutionFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\Payolution\Form\InvoiceSubForm
     */
    public function createInvoiceForm()
    {
        return new InvoiceSubForm();
    }

    /**
     * @return \Spryker\Yves\Payolution\Form\InstallmentSubForm
     */
    public function createInstallmentForm()
    {
        return new InstallmentSubForm();
    }

    /**
     * @return \Spryker\Yves\Payolution\Form\DataProvider\InstallmentDataProvider
     */
    public function createInstallmentFormDataProvider()
    {
        return new InstallmentDataProvider($this->getPayolutionClient(), $this->createCurrencyManager());
    }

    /**
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     */
    public function createCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

    /**
     * @return \Spryker\Yves\Payolution\Form\DataProvider\InvoiceDataProvider
     */
    public function createInvoiceFormDataProvider()
    {
        return new InvoiceDataProvider();
    }

    /**
     * @return \Spryker\Yves\Payolution\Handler\PayolutionHandler
     */
    public function createPayolutionHandler()
    {
        return new PayolutionHandler($this->getPayolutionClient(), $this->createCurrencyManager());
    }

    /**
     * @return \Spryker\Client\Payolution\PayolutionClientInterface
     */
    public function getPayolutionClient()
    {
        return $this->getProvidedDependency(PayolutionDependencyProvider::CLIENT_PAYOLUTION);
    }

}
