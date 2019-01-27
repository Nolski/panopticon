<?php namespace App\Http\Controllers;


use App\Actions\User\ChangeUserStatusAction;
use App\Actions\User\CreateUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\Actions\User\UsersList;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = app(UsersList::class)->get($this->perPage());
        return (new UserResourceCollection($users));
    }

    public function edit(User $user)
    {
        return $user;
    }

    public function show(User $user)
    {
        return $user;
    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $user = app(CreateUserAction::class)->create($request->all());
        return response()->json([
            'message' => trans('irc.user_created'),
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $user = app(UpdateUserAction::class)->update($user, $request->all());
        return response()->json([
            'message' => trans('irc.user_updated'),
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        app(DeleteUserAction::class)->delete($user);
        return response()->json([
            'message' => trans('irc.user_deleted'),
        ], 200);

    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function activate(User $user)
    {
        app(ChangeUserStatusAction::class)->activate($user);
        return response()->json([
            'message' => trans('irc.user_activated')
        ], 200);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivate(User $user)
    {
        app(ChangeUserStatusAction::class)->deactivate($user);
        return response()->json([
            'message' => trans('irc.user_deactivated')
        ], 200);
    }

    protected function perPage()
    {
        $perPage = request("perPage");
        $perPagesLimit = [15, 30, 50];

        if (!in_array($perPage, $perPagesLimit)) {
            $perPage = 15;
        }

        return $perPage;
    }
}
