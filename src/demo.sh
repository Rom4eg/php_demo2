#!/bin/bash

SCRIPT_DIR="$( dirname -- ${BASH_SOURCE[0]} )"

if [ -z $1 ]; then
    php "$SCRIPT_DIR/Utils/Cli/cli.php" --cmd=help "${@:2}"
elif [[ $1 == --* ]] || [[ $1 == -* ]]; then
    php "$SCRIPT_DIR/Utils/Cli/cli.php" "$@"
else
    php "$SCRIPT_DIR/Utils/Cli/cli.php" --cmd=$1 "${@:2}"
fi
