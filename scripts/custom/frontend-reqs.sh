#!/bin/bash

set -e

echo "Installing frontend dependencies."

node -v

npm i --prefix docroot/themes/custom/dhsc_theme
