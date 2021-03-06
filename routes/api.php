<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings', 'change-locale']
], function($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');

        $api->post('users', 'UsersController@store')
            ->name('api.users.store');

        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');

        //第三方登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');

        $api->post('authorizations', 'AuthorizationsController@store')->name('api.authorizations.store');

        // 小程序登录
        $api->post('weapp/authorizations', 'AuthorizationsController@weappStore')
            ->name('api.weapp.authorizations.store');

        $api->post('weapp/users', 'UsersController@weappStore')
            ->name('api.weapp.users.store');

        $api->put('authorizations/current', 'AuthorizationsController@update')->name('api.authorizations.update');

        $api->delete('authorizations/current', 'AuthorizationsController@destroy')->name('api.authorizations.destroy');

        $api->get('users/{user}', 'UsersController@show')
            ->name('api.users.show');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        //游客可以访问的接口
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');

        $api->get('topics', 'TopicsController@index')
            ->name('api.topics.index');

        $api->get('topics/{topic}', 'TopicsController@show')
            ->name('api.topics.show');

        $api->get('users/{user}/topics', 'TopicsController@userIndex')
            ->name('api.users.topics.index');

        $api->get('topics/{topic}/replies', 'RepliesController@index')
            ->name('api.topics.replies.index');

        $api->get('users/{user}/replies', 'RepliesController@userIndex')
            ->name('api.users.replies.index');

        $api->get('links', 'LinksController@index')
            ->name('api.links.index');

        $api->get('actived/users', 'UsersController@activedIndex')
            ->name('api.actived.users.index');

        //需要 token 验证的接口
        $api->group(['middleware' => 'api.auth'], function($api) {
            $api->get('user', 'UsersController@me')
                ->name('api.user.show');

            $api->patch('user', 'UsersController@update')
                ->name('api.user.patch');

            $api->put('user', 'UsersController@update')
                ->name('api.user.update');

            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');

            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');

            $api->patch('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');

            $api->delete('topics/{topic}', 'TopicsController@destroy')
                ->name('api.topics.destroy');

            $api->post('topics/{topic}/replies', 'RepliesController@store')->name('api.topics.replies.store');

            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')
                ->name('api.topics.replies.destroy');

            $api->get('user/notifications', 'NotificationsController@index')
                ->name('api.user.notifications.index');

            $api->get('user/notifications/stats', 'NotificationsController@stats')
                ->name('api.user.notifications.stats');

            $api->patch('user/read/notifications', 'NotificationsController@read')
                ->name('api.user.notifications.read');

            $api->put('user/read/notifications', 'NotificationsController@read')
                ->name('api.user.notifications.read.put');

            $api->get('user/permissions', 'PermissionsController@index')
                ->name('api.user.permissions.index');
        });

    });
});
