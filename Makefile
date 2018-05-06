default:
	echo "\\(^)>"

database-recreate:
	./bin/console doctrine:database:drop --force
	./bin/console doctrine:database:create
	./bin/console doctrine:schema:create

phpunit: database-recreate
	./bin/phpunit
