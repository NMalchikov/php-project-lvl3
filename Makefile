start:
	php artisan serve
setup:
	cp -n .env.example .env
	php artisan key:gen --ansi
	php artisan migrate
	php artisan db:seed
	npm ci
	npm run build
deploy:
	git push heroku

lint:
	composer exec phpcs -- --standard=PSR12 app routes tests

lint-fix:
	composer phpcbf app routes tests database lang
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover ./build/logs/clover.xml
	
validate:
	composer validate
install:
	composer install
