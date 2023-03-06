#!/bin/bash

set -e

echo "Building frontend assets."

npm i --prefix docroot/themes/custom/dhsc_theme
npm run build --prefix docroot/themes/custom/dhsc_theme

echo "No frontend assets"
