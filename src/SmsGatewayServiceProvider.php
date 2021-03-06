<?php

namespace Nlksoft\SmsGateway;


use Nlksoft\SmsGateway\Nlksms;
use Illuminate\Support\ServiceProvider;

class SmsGatewayServiceProvider extends ServiceProvider
{

    protected $phone;
    protected $password;

    public function boot()
    {
        $this->publishes([__DIR__.'/config/smsgateway.php' => config_path('smsgateway.php')]);
        $this->phone = config('smsgateway.phone');
        $this->password = config('smsgateway.password');
    }

    public function register()
    {
        $this->app->singleton('nlksms', function(){

            return new Nlksms( array(
                'user' => array('name'=> $this->phone,'pass'=>$this->password)
            ));
        });
    }
}