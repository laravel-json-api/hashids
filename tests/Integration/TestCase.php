<?php

declare(strict_types=1);

namespace LaravelJsonApi\HashIds\Tests\Integration;

use LaravelJsonApi\HashIds\HashId;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Vinkla\Hashids\HashidsServiceProvider;

abstract class TestCase extends BaseTestCase
{

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('hashids', [
            'default' => 'main',
            'connections' => [
                'main' => [
                    'salt' => 'Z3wxm8m6fxPMRtjX',
                    'length' => 10,
                ],

                'alternative' => [
                    'salt' => 'RRivwMsIlm1XB8ir',
                    'length' => 5,
                ],
            ],
        ]);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        HashId::withDefaultConnection(null);
    }

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app)
    {
        return [
            HashidsServiceProvider::class,
        ];
    }
}
