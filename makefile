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
	docker-compose -f docker-compose.yml exec -u root php chmod -r 777 .
	docker-compose -f docker-compose.yml exec php ./vendor/bin/psalm