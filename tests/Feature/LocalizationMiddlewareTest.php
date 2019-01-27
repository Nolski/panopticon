<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocalizationMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_url_ignore_locale_param_in_url_when_its_default()
    {

        $this->login();

        $this->createUserRoleWithPermission(auth()->user(), 'users_management');

        config(['app.locale' => 'en']);

        $this->withoutExceptionHandling();

        $this->initDashboard();

        $this->get('/')->assertSuccessful();

        $this->assertEquals('en', App::getLocale());
    }


    protected function initDashboard(){

        $this->syncStructure();

        $this->login();

        $this->createUserRoleWithPermission(auth()->user(),['cases.job-seeker', 'cases.firm','cases.job-opening']);
    }
}
