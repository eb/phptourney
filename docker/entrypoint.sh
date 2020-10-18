#!/bin/bash

info() {
    echo "INFO: $1"
}

error() {
    echo "ERROR: $1"
    exit 1
}

if [ -z "$PHPTOURNEY_MAIL_FROM_ADDRESS" ]; then
    error "The environment variable 'PHPTOURNEY_MAIL_FROM_ADDRESS' has to be set"
fi

cat << EOT > /etc/msmtprc
account default
host mail
from $PHPTOURNEY_MAIL_FROM_ADDRESS
EOT

exec "$@"