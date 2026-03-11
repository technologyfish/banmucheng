<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Debug login (remove in production)
$router->get('/api/ping', function () {
    try {
        $admin = \App\Models\AdminUser::where('username', 'admin')->first();
        if (!$admin) {
            return response()->json(['error' => 'admin user not found']);
        }
        $verify = password_verify('admin123', $admin->password);
        return response()->json([
            'db'           => 'ok',
            'admin_found'  => true,
            'hash'         => $admin->password,
            'verify'       => $verify,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Public Routes
$router->group(['prefix' => 'api'], function () use ($router) {

    // Categories
    $router->get('categories', 'CategoryController@index');

    // Products
    $router->get('products', 'ProductController@index');
    $router->get('products/{id}', 'ProductController@show');

    // Options (public)
    $router->get('options', 'OptionController@publicIndex');

    // Articles (public)
    $router->get('articles',      'ArticleController@publicIndex');
    $router->get('articles/{id}', 'ArticleController@publicShow');

    // Admin login
    $router->post('admin/login', 'AdminController@login');

    // Settings (public)
    $router->get('settings', 'SettingController@publicIndex');
});

// Admin Protected Routes
$router->group(['prefix' => 'api/admin', 'middleware' => 'auth.admin'], function () use ($router) {

    // Auth
    $router->get('me', 'AdminController@me');
    $router->post('logout', 'AdminController@logout');

    // Categories
    $router->get('categories', 'CategoryController@adminIndex');
    $router->post('categories', 'CategoryController@store');
    $router->put('categories/{id}', 'CategoryController@update');
    $router->delete('categories/{id}', 'CategoryController@destroy');
    $router->post('categories/{id}/reorder', 'CategoryController@reorder');

    // Products
    $router->get('products', 'ProductController@adminIndex');
    $router->post('products', 'ProductController@store');
    $router->put('products/{id}', 'ProductController@update');
    $router->delete('products/{id}', 'ProductController@destroy');
    $router->post('products/{id}/reorder', 'ProductController@reorder');

    // Images
    $router->delete('product-images/{id}', 'ProductController@deleteImage');
    $router->post('product-images/sort', 'ProductController@sortImages');

    // Upload
    $router->post('upload', 'UploadController@upload');

    // Options CRUD
    $router->get('options',          'OptionController@index');
    $router->post('options',         'OptionController@store');
    $router->put('options/{id}',     'OptionController@update');
    $router->delete('options/{id}',  'OptionController@destroy');

    // Article Categories
    $router->get('article-categories',                    'ArticleController@categoryIndex');
    $router->get('article-categories/{id}',               'ArticleController@categoryShow');
    $router->post('article-categories',                   'ArticleController@categoryStore');
    $router->put('article-categories/{id}',               'ArticleController@categoryUpdate');
    $router->delete('article-categories/{id}',            'ArticleController@categoryDestroy');
    $router->post('article-categories/{id}/reorder',      'ArticleController@categoryReorder');

    // Settings
    $router->get('settings',                    'SettingController@adminIndex');
    $router->post('settings',                   'SettingController@adminUpdate');

    // Articles
    $router->get('articles',                    'ArticleController@adminIndex');
    $router->get('articles/{id}',               'ArticleController@adminShow');
    $router->post('articles',                   'ArticleController@store');
    $router->put('articles/{id}',               'ArticleController@update');
    $router->delete('articles/{id}',            'ArticleController@destroy');
    $router->post('articles/{id}/reorder',      'ArticleController@reorder');
});
