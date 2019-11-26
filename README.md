# Vidmizer
The Vidmizer admission test

## Start up dockers
To start the application, ensure you've `make` installed, and type the following command 
```make start```

If make isn't installed you can do this one
```docker-compose up --build -d```

## Enable Migration
Migration may cause problems. If they do, you have to uncomment the line that set the DB path in the .env file to reach the 127.0.0.1 host, and comment the one which has the t_db container as db host.
Once migrations are done, you can comment this line again and then uncomment the other, which reach to t_db container