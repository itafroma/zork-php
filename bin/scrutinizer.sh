#!/bin/sh

#
# Uploads code coverage data to Scrutinizer.
#

# Exit on first non-zero error and set verbose mode.
set -ev

# HHVM incorrectly reports 100% code coverage
if [[ "$TRAVIS_PHP_VERSION" != hhvm* ]]; then
    mkdir -P ./build/bin
    wget -P ./build/bin https://scrutinizer-ci.com/ocular.phar
    php ./build/bin/ocular.phar code-coverage:upload --format=php-clover ./build/logs/coverage/clover.xml
end
