<?php

namespace App\Providers;

use App\Services\Account\IAccount;
use App\Services\Account\SAccount;
use App\Services\Dashboard\IDashboard;
use App\Services\Dashboard\SDashboard;
use App\Services\FollowUp\IFollowUp;
use App\Services\FollowUp\SFollowUpAccount;
use App\Services\FollowUp\SFollowUpLeads;
use App\Services\Google\IGoogle;
use App\Services\Google\SGoogle;
use App\Services\IGlobal;
use App\Services\Leads\ILeads;
use App\Services\Leads\SLeads;
use App\Services\Menu\IMenu;
use App\Services\Menu\SMenu;
use App\Services\Reward\IReward;
use App\Services\Reward\SReward;
use App\Services\SGlobal;
use App\Services\User\SUser;
use Illuminate\Support\ServiceProvider;

class CartaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IGlobal::class, SGlobal::class);
        $this->app->bind(IUser::class, SUser::class);
        $this->app->bind(IDashboard::class, SDashboard::class);
        $this->app->bind(ILeads::class, SLeads::class);
        $this->app->bind(IAccount::class, SAccount::class);
        $this->app->bind(IReward::class, SReward::class);
        $this->app->bind(IMenu::class, SMenu::class);
        $this->app->bind(IGoogle::class, SGoogle::class);

        // followup
        $this->app->bind(IFollowUp::class, SFollowUpLeads::class);
        $this->app->bind(IFollowUp::class, SFollowUpAccount::class);
    }
}
