# Securitm test 
[link to test](https://gist.github.com/f1uder/91f428ceedcc7ea8ef66a71b2128b9f7)
## Getting started
### Manual Point-by-Point Installation
1) create db with name 'securitm' and add new dbuser securitm
2) change db config with your auth data
3) change DB_ variables in .env file
4) run ```php artisan migrate --seed```
5) run ```npm run dev```

### or use Docker Compose
> [!WARNING]
> Attention .env file is already configured to work with the docker container
1) run ```docker-compose up -d --build appp```
2) run ```docker-compose run --rm composer update```
3) run ```docker-compose run --rm npm run dev```
4) run ```docker-compose run --rm artisan migrate --seed```
   
   
