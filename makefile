test:
	docker-compose -f docker-compose.yml exec php ./vendor/bin/phpunit
#make -B swagger
swagger:
	docker-compose -f docker-compose.yml exec php php swagger/swaggerGenerator.php
generate-migration:
	docker-compose -f docker-compose.yml exec php ./vendor/bin/doctrine-migrations generate
apply-migrations:
	docker-compose -f docker-compose.yml exec php ./vendor/bin/doctrine-migrations migrate
psalm:
	docker exec -u root -it api_content_php_1 vendor/bin/psalm
#executing psalm - chmod -R 777 /var/www/html, vendor/bin/psalm
php_container:
	docker exec -u root -it api_content_php_1 bash

#./vendor/bin/doctrine-migrations migrations:execute migrations\\Version20230824192449 --up down migrations
