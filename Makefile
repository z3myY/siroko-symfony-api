clone-deploy: clone create-env up install migrate

clone:
	git clone https://github.com/z3myY/siroko-symfony-api.git

create-env:
	cp .docker/.env.nginx .docker/.env.nginx.local

up:
	cd .docker && docker compose up -d
    
test:
	docker exec -it siroko_api-php-1 php bin/phpunit

install:
	docker exec -it siroko_api-php-1 composer install

migrate:
	docker exec -it siroko_api-php-1 php bin/console doctrine:migrations:migrate --no-interaction

console:
	docker exec -it siroko_api-php-1 bash