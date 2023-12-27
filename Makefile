#!make

# User has name "app" in container. GID and PID of this user match GID and PID
# of current host user by default but may be overriden by environment.
APP_USER_ID := $(shell id -u)
APP_GROUP_ID := $(shell id -g)

include .env
export

# Environment definitions to run commands as different users. Do not use --user option
# of docker-compose to "run" or "start" containers because entrypoint script will fail
# when running without root.
DOCKER_USER := APP_USER_ID=$(APP_USER_ID) APP_GROUP_ID=$(APP_GROUP_ID)
DOCKER_ROOT := APP_USER_ID=0

# Shortcut for running commands. "_NO_DEPS" mean do not start linked containers because
# it is not required or(and) take negative side effect.
RUN=docker-compose run --rm webserver
RUN_NO_DEPS=docker-compose run --rm --no-deps webserver


# Help decribes every other target
.PHONY: help
help:
	@ printf "\n \e[93mUsage: \n"
	@ printf "\n   \e[0mmake <command> [other commands ...]"
	@ printf "\n\n \e[93mCommands (main):\n"
	@ printf "\n   \e[32mhelp    \e[0m- print this message and exit"
	@ printf "\n   \e[32minit    \e[0m- prepare fresh instance to run"
	@ printf "\n   \e[32madmin   \e[0m- create framework admin user"
	@ printf "\n   \e[32mstart   \e[0m- create and start containers"
	@ printf "\n   \e[32mrestart \e[0m- restart \e[96mwebserver\e[0m container"
	@ printf "\n   \e[32mstop    \e[0m- stop containers (do not delete anything)"
	@ printf "\n   \e[32mupdate  \e[0m- update \e[96mcomposer\e[0m packages and up database"
	@ printf "\n   \e[32mremove  \e[0m- delete all containers, volumes and local images"
	@ printf "\n\n \e[93mCommands (aux):\n"
	@ printf "\n   \e[32mbuild   \e[0m- build local images"
	@ printf "\n   \e[32mtest    \e[0m- run PHPunit tests"
	@ printf "\n   \e[32mhtop    \e[0m- run process monitor in \e[96mwebserver\e[0m container"
	@ printf "\n   \e[32mdown    \e[0m- stop and delete containers and volumes (but keep named ones)"
	@ printf "\n   \e[32mfixfs   \e[0m- fix directory ownership and \"executable\" flags"
	@ printf "\n   \e[32mcache   \e[0m- refresh framework cache"
	@ printf "\n   \e[32muncache \e[0m- disable framework cache"
	@ printf "\n\n \e[93mCommands (shell):\n"
	@ printf "\n   \e[32mbash   \e[0m- run bash in \e[96mwebserver\e[0m container as regular user"
	@ printf "\n   \e[32mroot   \e[0m- run bash in \e[96mwebserver\e[0m container as superuser"
	@ printf "\n   \e[32mtinker \e[0m- run Tinker in \e[96mwebserver\e[0m container"
	@ printf "\n   \e[32mmysql  \e[0m- start a new MySQL CLI session"
	@ printf "\n\n \e[93mCommands (print):\n"
	@ printf "\n   \e[32mroutes \e[0m- print active HTTP routes"
	@ printf "\n   \e[32mlogs   \e[0m- print container logs"
	@ printf "\n   \e[32mfollow \e[0m- same as \"logs\" but realtime"
	@ printf "\n\n \e[93mTips:\n"
	@ printf "\n   \e[32m*\e[0m Set \e[95mXDEBUG_MODE\e[0m in \e[95m.env\e[0m like \e[95mdevelop,debug\e[0m or \e[95mdebug\e[0m and restart to enable debugging."
	@ printf "\n   \e[32m*\e[0m XDebug config listed in \e[95m./docker/webserver/php.ini\e[0m. Integration may differ acros IDEs."
	@ printf "\n   \e[32m*\e[0m You don't need explicity build, start or set something before running commands."
	@ printf "\n   \e[32m*\e[0m \e[95mDB_PASSWORD\e[0m, \e[95mPUSHER_APP_KEY\e[0m and \e[95mPUSHER_APP_SECRET\e[0m will be filled with random if leave it empty."
	@ printf "\n   \e[32m*\e[0m Use \e[32mmake stop\e[0m instead of \e[32mmake down\e[0m to keep your command history."
	@ printf "\n   \e[32m*\e[0m \e[32mmake restart\e[0m working faster than \e[32mmake stop start\e[0m."
	@ printf "\n   \e[32m*\e[0m There is some additional tools:"
	@ printf "\n         \e[32m-\e[0m \e[96m$(APP_URL)/websockets\e[0m - WebSockets dashboard"
	@ printf "\n         \e[32m-\e[0m \e[96m$(APP_URL):8080\e[0m       - Adminer DB administration tool"
	@ printf "\n         \e[32m-\e[0m \e[96m$(APP_URL):8025\e[0m       - Mailpit dashboard"
	@ printf "\n\n \\e[0m Don't forget run \e[32mmake init\e[0m on fresh instance. \n\n"


.PHONY: build
build:
	@ docker-compose build

.PHONY: update
update: vendor
	@ $(DOCKER_USER) $(RUN_NO_DEPS) composer install
	@ $(DOCKER_USER) $(RUN) php artisan migrate
	@ $(DOCKER_USER) $(RUN) php artisan db:seed


.PHONY: init
init: vendor
	@ make build
	@ $(DOCKER_USER) $(RUN) php artisan key:generate
	@ make update

.PHONY: admin
admin: vendor
	@ $(DOCKER_USER) $(RUN) php artisan orchid:admin


.PHONY: start
start: vendor
	@ $(DOCKER_USER) docker-compose up --no-recreate --detach

.PHONY: restart
restart: vendor
	@ $(DOCKER_USER) docker-compose restart webserver


.PHONY: test
test: vendor
	@ $(DOCKER_USER) $(RUN) php artisan test


.PHONY: stop
stop:
	@ docker-compose stop


.PHONY: down
down:
	@ docker-compose down


.PHONY: remove
remove:
	@ docker-compose down --volumes --rmi local
	@ docker-compose rm -fv


.PHONY: bash
bash:
	@ $(DOCKER_USER) docker-compose up --no-recreate --detach --no-deps webserver
	@ docker-compose exec -u app webserver bash


.PHONY: root
root:
	@ $(DOCKER_USER) docker-compose up --no-recreate --detach --no-deps webserver
	@ docker-compose exec -u root webserver bash


.PHONY: tinker
tinker: vendor
	@ $(DOCKER_USER) docker-compose up --no-recreate --detach webserver
	@ docker-compose exec -u app webserver php artisan tinker

.PHONY: htop
htop:
	@ $(DOCKER_USER) docker-compose up --no-recreate --detach webserver
	@ docker-compose exec -u root webserver htop


.PHONY: mysql
mysql:
	@ docker-compose up --no-recreate --detach mysql
	@ docker-compose exec mysql mysql -u $(DB_USERNAME) -p$(DB_PASSWORD) $(DB_DATABASE)


.PHONY: fixfs
fixfs:
	@ $(DOCKER_ROOT) $(RUN_NO_DEPS) bash -c 'chown -R $(APP_USER_ID):$(APP_USER_ID) .'
	@ $(DOCKER_ROOT) $(RUN_NO_DEPS) bash -c 'chmod +x /usr/local/bin/entrypoint.sh'
	@ $(DOCKER_ROOT) $(RUN_NO_DEPS) bash -c 'chmod +x /var/www/html/artisan'

.PHONY: cache
cache:
	@ $(DOCKER_USER) $(RUN) php artisan config:cache
	@ $(DOCKER_USER) $(RUN) php artisan route:cache
	@ $(DOCKER_USER) $(RUN) php artisan view:cache

.PHONY: uncache
uncache:
	@ $(DOCKER_USER) $(RUN) php artisan optimize:clear

.PHONY: routes
routes: vendor
	@ $(DOCKER_USER) $(RUN_NO_DEPS) php artisan route:list


.PHONY: logs
logs:
	@ docker-compose logs


.PHONY: follow
follow:
	@ docker-compose logs --follow


.env:
	@ $(DOCKER_USER) $(RUN_NO_DEPS) cp -n .env.example .env
	@ $(DOCKER_USER) $(RUN_NO_DEPS) bash -c "sed -i \"s/DB_PASSWORD=$$/DB_PASSWORD=\`cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 10 | head -n 1\`/\" .env"
	@ $(DOCKER_USER) $(RUN_NO_DEPS) bash -c "sed -i \"s/PUSHER_APP_KEY=$$/PUSHER_APP_KEY=\`cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 10 | head -n 1\`/\" .env"
	@ $(DOCKER_USER) $(RUN_NO_DEPS) bash -c "sed -i \"s/PUSHER_APP_SECRET=$$/PUSHER_APP_SECRET=\`cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 10 | head -n 1\`/\" .env"


composer.lock:
	@ $(DOCKER_USER) $(RUN_NO_DEPS) composer update


vendor:
	@ $(DOCKER_USER) $(RUN_NO_DEPS) composer install