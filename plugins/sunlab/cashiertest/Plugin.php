<?php namespace SunLab\CashierTest;

use Backend;
use Illuminate\Support\Facades\Event;
use System\Classes\PluginBase;

/**
 * CashierTest Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['OFFLINE.Cashier'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'CashierTest',
            'description' => 'No description provided yet...',
            'author' => 'SunLab',
            'icon' => 'icon-leaf'
        ];
    }

    public function boot()
    {
        parent::boot();

        Event::listen('offline.cashier::stripeElementForm.submit', function ($post) {
            $token = $post['token']['id'] ?? null;
            if (!$token) {
                throw new \RuntimeException('Stripe token is missing!');
            }

            $user = \Auth::getUser();
            $user->newSubscription('main', env('STRIPE_PLAN_TEST_ID'))->create($token);

            return [
                'redirect' => \Url::to('thank-you')
            ];
        });
    }
}
