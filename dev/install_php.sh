#!/usr/bin/env bash

add-apt-repository ppa:ondrej/php -y

declare -a phps=("7.1" "7.2" "7.3" "7.4" "8.0" "8.1" "8.2" "8.3")

declare -a exts=("curl" "zip" "dom" "mbstring" "bcmath" "iconv" "intl")

command="apt-get install --no-install-recommends -y"

for php in "${phps[@]}"; do
    command+=" php${php}"

    for ext in "${exts[@]}"; do
        command+=" php${php}-${ext}"
    done
done

eval "$command"
