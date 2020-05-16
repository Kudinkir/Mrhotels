# Mrtest
Клонируем проект и ставим либы
	git clone git@github.com:Kudinkir/Mrhotels.git
	cd Mrhotels
	composer update
	composer install 

Docker
1) docker-compose build
2) docker-compose up
3) docker exec -it hotels_php bash (+ в нем выролняем миграции )
4) docker exec -it hotels_db mysql -u root -proot hotels (+ Создаем пользователя)
5) проект доступен на http://localhost:1341/
	
Создаем базу 
	Прописываем реквизиты в .env
	Выполняем создание
		php bin/console doctrine:database:create 
	Выполняем миграции
		php bin/console doctrine:migrations:migrate
	Создаем пользователя
		Генерируем пароль
			php bin/console  security:encode-password
		Делаем Запрос
			INSERT INTO user (email, roles,password) VALUES ('admin@admin.com', 
	'["ROLE_USER"]', 'YOUR PASSWORD HERE'
	);

поднимаем локальный сервер 
	можно 
		php -S localhost:8001 -t public 

Заходим в админку http://localhost:8001/admin/
и Наслаждаемся

для апи доступны урлы
1)http://localhost:8001/hotels/list
2)http://localhost:8001/hotels/item/1
3)http://localhost:8001/hotels/add_reservation?room_id=1&entry_date=2020-01-03&exit_date=2020-01-05&phone=888888&email=sdfgsdfgsdfg
