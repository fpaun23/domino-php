# domino-php

### Install vendor files with composer

composer install

### Start local webserver 

symfony server:start

### Run a domino game (cli or postman)

curl --location -g --request POST 'localhost:8000/play?players[0]=bob&players[1]=alice&players[2]=tom&players[3]=mary'

players is an array with [min:2 - max:4] players
