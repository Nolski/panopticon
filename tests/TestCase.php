<?php

namespace Tests;

use App\Models\User;
use App\Sync\DataRequest;
use App\Sync\FormRequest;
use App\Sync\MobileWorkersRequest;
use App\Sync\UsersRequest;
use App\Sync\UsersSync;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\Fixtures\Classes\DataFactory;
use Tests\Fixtures\Classes\StructureFactory;
use App\Sync\StructureRequest;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function tearDown()
    {
        \Mockery::close();
    }

    protected function loginApi($user = null)
    {
        $this->actingAs($user ?? factory(User::class)->create(), 'api');
    }

    protected function login($user = null)
    {
        $this->actingAs($user ?? factory(User::class)->create());
    }

    protected function mockStructureRequest()
    {
        $requestMock = \Mockery::mock(StructureRequest::class);

        $structure = json_decode(file_get_contents(base_path('tests/Fixtures/structure.json')), true)['modules'];

        $requestMock->shouldReceive('getModules')->andReturn($structure);

        app()->instance(StructureRequest::class, $requestMock);
    }

    protected function mockFullStructureRequest()
    {
        $requestMock = \Mockery::mock(StructureRequest::class);

        $structure = json_decode(file_get_contents(base_path('tests/Fixtures/full_structure.json')), true)['modules'];

        $requestMock->shouldReceive('getModules')->andReturn($structure);

        app()->instance(StructureRequest::class, $requestMock);
    }


    protected function mockDataRequest()
    {

        $requestMock = \Mockery::mock(DataRequest::class);

        $data = json_decode(file_get_contents(base_path('tests/Fixtures/job_seeker_json_data.json')), true);

        $requestMock->shouldReceive('data')->andReturn($data);

        app()->instance(DataRequest::class, $requestMock);
    }

    protected function mockFormRequest()
    {

        $requestMock = \Mockery::mock(FormRequest::class);

        $data = json_decode(file_get_contents(base_path('tests/Fixtures/job_seeker_monthly_followups.json')), true);

        $requestMock->shouldReceive('data')->andReturn($data);

        app()->instance(FormRequest::class, $requestMock);
    }

    protected function mockUsersRequest($data = null)
    {
        $requestMock = \Mockery::mock(UsersRequest::class);

        if (!$data) {
            $data = json_decode(file_get_contents(base_path('tests/Fixtures/commcare_users.json')), true);
        }

        $requestMock->shouldReceive('data')->andReturn($data);

        app()->instance(UsersRequest::class, $requestMock);

        $this->mockMobileWorkersRequest(true);
    }

    protected function mockMobileWorkersRequest($empty = false){
        $requestMock = \Mockery::mock(MobileWorkersRequest::class);

        $data = json_decode(file_get_contents(base_path('tests/Fixtures/commcare_mobile_workers.json')), true);

        if($empty){
            $data['objects'] = [];
        }

        $requestMock->shouldReceive('data')->andReturn($data);

        app()->instance(MobileWorkersRequest::class, $requestMock);
    }

    protected function syncStructure(string $caseType = null)
    {
        $this->mockStructureRequest();

        $factory = app(StructureFactory::class);

        return $factory->make($caseType);
    }

    protected function syncCaseData(string $caseType)
    {
        $this->mockDataRequest();

        $factory = app(DataFactory::class);

        return $factory->make($caseType);
    }

    protected function syncUsers($data = null)
    {
        $this->mockUsersRequest($data);

        app(UsersSync::class)->make();
    }

    protected function memoryUsage()
    {
        $usage = memory_get_usage(true);
        if ($usage < 1024) {
            $usage = $usage . " bytes";
        } elseif ($usage < 1048576) {
            $usage = round($usage / 1024, 2) . " kilobytes";
        } else {
            $usage = round($usage / 1048576, 2) . " megabytes";
        }

        var_dump($usage);
    }


    protected function loginAsEso()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user ?? factory(User::class)->create(), 'api');
    }


    public function createUserRoleWithPermission($user, $permissions, $guard = 'web')
    {

        $permissions = $this->createPermissions($permissions, $guard);

        $role = factory(Role::class)->create();

        $role->givePermissionTo(
            $permissions->pluck('name')->toArray()
        );

        $user->assignRole($role->id);
    }

    protected function createPermissions($permissions, $guard = 'web')
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        $permissions = collect($permissions)->map(function ($permission) use ($guard) {
            return [
                'name' => $permission,
                'guard_name' => $guard,
            ];
        });

        Permission::query()->insert($permissions->toArray());

        return $permissions;
    }

}
