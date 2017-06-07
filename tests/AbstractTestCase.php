<?php

namespace SanderVanHooft\PayableRedirect;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use SanderVanHooft\PayableRedirect\PayableRedirectServiceProvider;

class AbstractTestCase extends AbstractPackageTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('payable.mollie.key', env('MOLLIE_KEY'));
    }

    /**
     * Get the service provider class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return string
     */
    protected function getServiceProviderClass($app)
    {
        return PayableRedirectServiceProvider::class;
    }

    protected function setUp()
    {
        parent::setUp();
        $this->withPackageMigrations();
    }

    protected function withPackageMigrations()
    {
        include_once __DIR__.'/CreateTestModelsTable.php';
        (new \CreateTestModelsTable())->up();
        include_once __DIR__.'/../database/migrations/2017_05_11_163005_create_payments_table.php';
        (new \CreatePaymentsTable())->up();
    }
}
