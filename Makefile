path = docker

stop:
	docker-compose stop

clean:
	docker rm t_db t_php t_ngn

start:
	docker-compose up --build -d

compose:
	docker-compose up -d

restart: stop clean start

recompose: stop clean compose