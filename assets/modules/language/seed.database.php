<?php
/**
 * Создать и заполнить данными таблицы модуля
 * Док аннотация модуля должна содержать строку вида: * @internal	@dependencies requires files located at /assets/modules/language/
 * @builder - обязательный параметр.
 * `{PREFIX_TABLE_NAME}` - автомачтически формирует валидное имя таблицы.
 *
 * "имя_таблицы" => [
 *     "builder" => "строка SQL запроса для создания структуры таблицы",
 *     "seeder" => "строка SQL запроса для наполнения таблицы начальными данными",
 * ]
 */
 return [
	"a_lang" => [
	    "builder" => "
	        CREATE TABLE IF NOT EXISTS `{PREFIX_TABLE_NAME}` (
                `lang_id` int(11) NOT NULL AUTO_INCREMENT,
                `ua` text NULL COMMENT 'ua',
                `ru` text NULL COMMENT 'ru',
                `en` text NULL COMMENT 'en',
                PRIMARY KEY (`lang_id`)
            ) ENGINE=InnoDB;
	    ",
        "seeder" => "
            REPLACE INTO `{PREFIX_TABLE_NAME}` (`lang_id`,`ua`,`ru`,`en`) VALUES
                (1,'Для повної функціональності цього сайту необхідно включити JavaScript. Ось','Для полной функциональности этого сайта необходимо включить JavaScript. Вот','For the full functionality of this site, you must enable JavaScript. Here are the'),
                (2,'інструкції','инструкции','instructions'),
                (3,', як увімкнути JavaScript у вашому браузері.',', как включить JavaScript в вашем браузере.',', how to enable JavaScript in your browser.'),
                (4,'Ваш браузер застарів, будь ласка &lt;b&gt; поновіть &lt;/ b&gt; його.','Ваш браузер устарел, пожалуйста &lt;b&gt;обновите&lt;/b&gt; его.','Your browser is out of date, please &lt;b&gt; update &lt;/ b&gt; it.'),
                (5,'Фільтри','Фильтры','Filters'),
                (6,'Ціна','Цена','Price'),
                (7,'Товарів','Товаров','Items'),
                (8,'Товар','Товар','Item'),
                (9,'Товара','Товара','Items'),
                (10,'Увійти','Войти','Login'),
                (11,'Зареєструватися','Зарегистрироваться','Register'),
                (12,'Користувача з таким Email вже зареєстровано','Пользователь с таким Email уже зарегистрирован','Email already registered'),
                (13,'Користувача не знайдено','Пользователь не найден','User is not found'),
                (14,'Невірний логін або пароль','Неверный логин или пароль','Wrong login or password'),
                (15,'Пароль','Пароль','Password'),
                (16,'Користувача успішно зареєстровано','Пользователь успешно зарегистрирован','User successfully registered'),
                (17,'Введіть коректний Email','Введите корректный Email','Please enter a valid Email'),
                (18,'Запам&#039;ятати мене','Запомнить меня','Remember me'),
                (19,'Дякуємо!','Спасибо!','Thank you!'),
                (20,'Операція пройшла успішно.','Операция выполнена успешно.','The operation was successful.'),
                (21,'Увага!','Внимание!','Attention!'),
                (22,'Щось пішло не так.','Что-то пошло не так.','Something went wrong.'),
                (23,'Вихід','Выход','Logout'),
                (24,'Забули пароль?','Забыли пароль?','Forgot your password?'),
                (25,'Введіть ваш E-mail','Введіть ваш E-mail','Введіть ваш E-mail'),
                (26,'Відправити','Отправить','Send'),
                (27,'На Ваш email відправлені інструкції.','На Ваш email отправлены инструкции.','Your instructions have been sent to your email.'),
                (28,'Ваш пароль успішно відновлено. Увійдіть використовуючи дані з Email.','Ваш пароль успешно восстановлен. Войдите используя данные из Email.','Your password has been successfully restored. Log in using data from Email.'),
                (29,'сторінка-','страница-','page-');
        "
    ],
];