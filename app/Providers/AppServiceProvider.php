<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Validators\CustomValidator;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $models = array(
            'User',
            'Module',
            'Innovation',
            'VstsAccount',
            'VstsProject'
        );

        foreach( $models as $model )
        {
            $this -> app -> bind( "App\\Repositories\\{$model}\\{$model}RepoInterface", "App\\Repositories\\{$model}\\{$model}Repo" );
        }
    }
}
