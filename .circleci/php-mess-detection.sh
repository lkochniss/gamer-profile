#!/bin/sh

./vendor/bin/phpmd src/ text cleancode --exclude src/Migrations
./vendor/bin/phpmd src/ text codesize --exclude src/Migrations
./vendor/bin/phpmd src/ text controversial --exclude src/Migrations
./vendor/bin/phpmd src/ text design --exclude src/Migrations
./vendor/bin/phpmd src/ text naming --exclude src/Migrations
./vendor/bin/phpmd src/ text unusedcode --exclude src/Migrations
