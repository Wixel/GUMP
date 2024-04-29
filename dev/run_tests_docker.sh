#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
# cd $DIR/../

set -e

declare -a phps=("7.1" "7.2" "7.3" "7.4" "8.0" "8.1" "8.2" "8.3")

for php_version in "${phps[@]}"; do

    update-alternatives --set php "/usr/bin/php${php_version}"

    composer update
    composer install --dev
    composer run-script test
done



