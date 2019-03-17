#!/bin/bash

cleanUp() {
    ./bin/console --env=test do:da:dr --force
}

trap cleanUp EXIT

rm -Rf var/cache/test

./bin/console --env=test do:da:cr
./bin/console --env=test do:sc:up --force
./bin/console --env=test do:fi:lo -n
./vendor/bin/phpunit

