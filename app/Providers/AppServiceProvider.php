<?php

namespace App\Providers;

use App\Utilities\DistanceTransformer;
use Illuminate\Support\ServiceProvider;
use Skilla\MaximalCliques\lib\BronKerboschAlgorithms;
use Skilla\MaximalCliques\lib\DataTransformerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind( DataTransformerInterface::class, DistanceTransformer::class );
        $this->app->bind( BronKerboschAlgorithms::class, function( $app )
        {
            $instance = new BronKerboschAlgorithms();
            $instance->setDataTransformer( $app->make( DataTransformerInterface::class ) );

            return $instance;
        });
    }
}
