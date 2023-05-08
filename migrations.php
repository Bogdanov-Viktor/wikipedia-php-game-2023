<?php
//
//      php -f migrations.php
//
/*
Модуль SQLite3 доступен по умолчанию. Модуль можно отключить помощью --without-sqlite3 во время компиляции.

Пользователи Windows должны включить поддержку php_sqlite3.dll для того, чтобы использовать модуль.

Для того что бы подключить sqlite, открываем файл php.ini в любом текстовом редактор
и раскомментируем директивы подключения нужных нам модулей.

Для подключения php_sqlite.dll в php.ini, нужно сначала подключить расширения php_pdo.dll и php_pdo_sqlite.dll.
Только в такой последовательности:

extension=php_pdo.dll
extension=php_pdo_sqlite.dll
extension=php_sqlite.dll

Строки extension=php_pdo.dll может в ini и не быть, раскоментируем тогда только extension=php_pdo_sqlite.dll
и extension=php_sqlite.dll После всего этого перезапустим апачь.

-----
В Openserver php_sqlite3.dll включен в php.ini по умолчанию и не надо производить действий по насторйке,
! тогда нужно запускать команду php -f migrations.php из консоли Openserver !
-----

*/

//'games(id, players_qty, start_path, finish_path, player_now_id)'
//'results(id, game_id, user_id, user_steps_pty, user_path_now)'

$db = new SQLite3("db.sqlite3");
$db->exec('CREATE TABLE games(id INTEGER PRIMARY KEY, players_qty INTEGER, start_path TEXT, finish_path TEXT, player_now_id INTEGER)');
$db->exec('CREATE TABLE results(id INTEGER PRIMARY KEY AUTOINCREMENT, game_id INTEGER, user_id INTEGER, user_steps_qty INTEGER, user_path_now TEXT)');

$db->close();

?>