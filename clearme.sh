#!/bin/bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
#curl --data-urlencode "token="$1 -X GET 'http://192.168.10.4/Barber/public/api/auth/getparticipants?token="$2y$10$MQpe21mxBFaS1h7hBzCpxuvHlHh3H8Q/jkq98ZKufhn6UXYwZMY5S"&id=2&msg=to%20test%20participant%20id'
# python3 request.py $1 $2
python -c "import os;os.system('clear')"
