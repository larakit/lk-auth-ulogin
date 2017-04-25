<?php
//;redirect_uri=http%3A%2F%2Flarakit.ru%2Fopenid"></div>
$url = '/openid';
return [
    'url'          => $url,
    'title'        => 'Авторизация через социальные сети',
    'display'      => 'panel',
    'fields'       => [
        //first_name – имя пользователя,
        'first_name',
        //last_name – фамилия пользователя,
        'last_name',
        //email – email пользователя [можно запросить его верификацию],
        'email',
        //nickname – псевдоним пользователя,
        //        'nickname',
        //bdate – дата рождения в формате DD.MM.YYYY,
        'bdate',
        //sex – пол пользователя (0 – не определен, 1 – женский, 2 – мужской),
        'sex',
        //phone – телефон пользователя в цифровом формате без лишних символов,
        'phone',
        //photo – адрес квадратной аватарки (до 100*100),
        'photo',
        //photo_big – адрес самой большой аватарки, выдаваемой соц. сетью,
        'photo_big',
        //city – город,
        'city',
        //country – страна.
        'country',
    ],

    'providers'    => [
        'vkontakte',
        'google',
        'facebook',
        'odnoklassniki',
        'mailru',
        'instagram',
    ],
    'hidden'       => 'other',
    'redirect_uri' => URL::to($url)
];