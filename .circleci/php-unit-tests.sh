#!/bin/sh

./bin/console do:da:cr
./bin/console do:sc:up --force
./bin/console do:fi:lo -n

./vendor/bin/phpunit --log-junit 'build/unitreport.xml' --coverage-clover 'build/cloverreport.xml'

./bin/console do:da:dr --force

