#!/bin/sh

./bin/console do:da:cr --env=test
./bin/console do:sc:up --force --env=test
./bin/console do:fi:lo -n --env=test

./vendor/bin/phpunit --log-junit 'build/unitreport.xml' --coverage-clover 'build/cloverreport.xml'

./bin/console do:da:dr --force --env=test

