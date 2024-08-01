INFO=\033[0;33m
ENDINFO=\033[0m
COMPOSE_FILE=docker-compose.yml

up:
	@echo "${INFO}Start BACKEND${ENDINFO}"
	docker-compose down
	docker-compose up -d --build
	#docker-compose exec backend composer install

fixer:
	docker-compose exec backend vendor/bin/php-cs-fixer fix src --allow-risky=yes

check:
	docker-compose exec backend vendor/bin/psalm

down:
	docker-compose down