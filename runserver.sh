#!/bin/bash
cd public
if [ -z "$1" ]
  then
    php -S 127.0.0.1:8001
else
	php -S 127.0.0.1:$1
fi
