#----------------------
# Parse makefile arguments
#----------------------
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
$(eval $(RUN_ARGS):;@:)

#----------------------
# Silence GNU Make
#----------------------
ifndef VERBOSE
MAKEFLAGS += --no-print-directory
endif

#----------------------
# Load .env file
#----------------------
ifneq ("$(wildcard .env)","")
include .env
export
else
endif

#----------------------
# Terminal
#----------------------

GREEN  := $(shell tput -Txterm setaf 2)
WHITE  := $(shell tput -Txterm setaf 7)
YELLOW := $(shell tput -Txterm setaf 3)
RESET  := $(shell tput -Txterm sgr0)

#------------------------------------------------------------------
# - Add the following 'help' target to your Makefile
# - Add help text after each target name starting with '\#\#'
# - A category can be added with @category
#------------------------------------------------------------------

HELP_FUN = \
	%help; \
	while(<>) { \
		push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ }; \
		print "-----------------------------------------\n"; \
		print "| Make Menu\n"; \
		print "-----------------------------------------\n"; \
		print "| usage: make [command]\n"; \
		print "-----------------------------------------\n\n"; \
		for (sort keys %help) { \
			print "${WHITE}$$_:${RESET \
		}\n"; \
		for (@{$$help{$$_}}) { \
			$$sep = " " x (32 - length $$_->[0]); \
			print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; \
		}; \
		print "\n"; \
	}

help: ##@other Show this help.
	@perl -e '$(HELP_FUN)' $(MAKEFILE_LIST)

seed-peq-database: ##@seed
	docker-compose up -d workspace
	docker-compose exec workspace bash -c "sudo apt-get update && sudo apt-get install -y curl unzip mysql-client"
	docker-compose exec workspace bash -c "curl http://db.projecteq.net/api/v1/dump/latest -o /tmp/db.zip"
	docker-compose exec workspace bash -c "unzip -o /tmp/db.zip -d /tmp/db/"
	docker-compose exec workspace bash -c "mysql -h mariadb -u${MARIADB_USER} -p${MARIADB_PASSWORD} ${MARIADB_DATABASE} -e 'DROP DATABASE ${MARIADB_DATABASE}; CREATE DATABASE ${MARIADB_DATABASE};'"
	docker-compose exec workspace bash -c "cd /tmp/db/peq-dump/ && mysql -h mariadb -u${MARIADB_USER} -p${MARIADB_PASSWORD} ${MARIADB_DATABASE} < ./create_all_tables.sql"
	docker-compose exec workspace bash -c "rm -rf /tmp/db/"

#----------------------
# Workflow
#----------------------

bash: ##@workflow Bash into workspace
	docker-compose exec workspace bash

mc: ##@workflow Jump into the MySQL container console
	docker-compose exec mariadb bash -c "mysql -uroot -p${MARIADB_ROOT_PASSWORD} -h localhost ${MARIADB_DATABASE}"
