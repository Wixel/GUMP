#!/usr/bin/env bash

set -e

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

readonly name="gump"

docker run -it --rm  -v "$DIR/../":/opt/project -w /opt/project $name ./dev/run_tests_docker.sh

