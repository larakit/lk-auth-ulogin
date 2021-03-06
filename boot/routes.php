<?php
Route::get('/logout', function () {
    return [
        'result'  => 'error',
        'message' => 'Запрещен метод выхода',
    ];
})->name('logout')->middleware('web');
Route::post('/logout', function () {
    Auth::logout();
    
    return [
        'result'  => 'success',
        'message' => 'Вы вышли с проекта',
    ];
})->name('logout')->middleware('web');

Route::get('/login', function () {
    return \Larakit\Page\LkPage::instance()->setBodyContent('
<script src="//ulogin.ru/js/ulogin.js"></script>
<div style="width: 100%; margin:auto;height: 100vh; padding-top:300px; text-align: center">
<h1>' . config('larakit.lk-auth-ulogin.title') . '</h1>
<br>
<div id="uLogin"
data-ulogin="display=' . config('larakit.lk-auth-ulogin.display') . ';theme=classic;fields=' . implode(',', config('larakit.lk-auth-ulogin.fields')) . ';providers=' . implode(',', config('larakit.lk-auth-ulogin.providers')) . ';hidden=' . config('larakit.lk-auth-ulogin.hidden') . ';redirect_uri=' . urlencode(route('login') . '?_token=' . csrf_token()) . ';mobilebuttons=0;"></div>
                        </div>');
})->name('login')->middleware('web')->middleware('guest');

Route::post('/login', function () {
    $token = Request::input('token');
    if(!$token) {
        throw new \Exception('Не удалось получить токен!');
    }
    $url             = 'http://ulogin.ru/token.php?token=' . $token . '&host=' . $_SERVER['HTTP_HOST'];
    $s               = file_get_contents($url);
    $data            = json_decode($s, true);
    $ulogin_identity = \Illuminate\Support\Arr::get($data, 'identity');
    $ulogin_network  = \Illuminate\Support\Arr::get($data, 'network');
    $user            = \App\User::where('ulogin_identity', '=', $ulogin_identity)
        ->first();
    if($user) {
        \Auth::login($user, true);
        
        return \Redirect::intended();
    } else {
        $email = \Illuminate\Support\Arr::get($data, 'email');
        $user  = \App\User::where('email', '=', $email)
            ->first();
        if($user) {
            throw new \Exception('На сайте уже есть пользователь с таким E-mail');
        } else {
            $username = \Illuminate\Support\Arr::get($data, 'last_name') . ' ' . \Illuminate\Support\Arr::get($data, 'first_name');
            //регистрируем пользователя
            $model                  = \App\User::firstOrCreate([
                'name'     => $username,
                'password' => Hash::make(md5(microtime(true))),
                'email'    => (string) ($email ? $email : microtime(true)),
            ]);
            $model->ulogin_identity = $ulogin_identity;
            $model->ulogin_network  = $ulogin_network;
            $model->save();
            \Auth::login($model, true);
            
            return \Redirect::intended();
        }
    }
})->name('login')->middleware('web')->middleware('guest');