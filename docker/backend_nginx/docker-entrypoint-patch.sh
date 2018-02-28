#!/bin/bash

#set -x

chmod -R +rw /var/www/html/backend/web/assets

exec "$@"
