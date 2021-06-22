#!/bin/bash

# Exit if any command fails.
set -e

# Include useful functions
. "$(dirname "$0")/includes.sh"

# Install theme-check package.
wp package install anhskohbo/wp-cli-themecheck

# Install theme-check plugin.
wp plugin install theme-check --version=20200922.1 --activate

# Run theme check.
wp themecheck --theme=astra --no-interactive
