## Passo a passo para rodar o projeto
Clone o projeto
```sh
[git clone https://github.com/LeoMendes475/task-manager-back.git](https://github.com/LeoMendes475/comissia-back.git)
```
```sh
cd task-manager-back
```


Crie o arquivo .env e atualize essas variáveis de ambiente no arquivo
```dosini
APP_NAME='app'
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
```

Instalar dependências do projeto
```sh
composer update
```

Gere a key do projeto Laravel
```sh
php artisan key:generate
```

Rode o projeto
```sh
php artisan serve
```

Acesse o projeto
[http://localhost:8000](http://localhost:8000)
