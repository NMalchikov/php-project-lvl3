start:
	php artisan serve
setup:
	composer install
	cp -n .env.example .env
	php artisan key:gen --ansi
	npm ci
	npm run build
lint:
	composer exec phpcs -- --standard=PSR12 app routes tests

lint-fix:
	composer phpcbf app routes tests database lang
test:
	php artisan test
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover ./build/logs/clover.xml
	
validate:
	composer validate
install:
	composer install
