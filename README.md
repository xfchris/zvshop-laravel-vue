<p align="center">

  <h2 align="center">ZVShop</h2>
  
  <p align="center"><img src="https://i.imgur.com/HljME6ub.png" alt="Logo"></p>

  <p align="center">Una plataforma para la gestión, compra y venta de productos online (demo)</p>
</p>

### Pre-requisitos 📋

- PHP >= 8.0
- mysql >= 8.0
- redis
- Composer >= 2.0
- node >= 16.13


### Instalación con Laravel Sail 🔧

1. Ejecutar comando `sail up -d`

2. Copiar el archivo `.env.example` incluido en uno de nombre `.env` y completar variables faltantes

3. Ejecutar comando `sail php artisan migrate:fresh --seed` 

4. Instalar paquetes de node `sail npm install && sail npm run prod`

5. Ejecutar pruebas `sail php artisan test`

6. Acceder al sitio `http://localhost`



## Credeniales de admin. 🔑

Email|Password|
 ------ | ------ |
admin@gmail.com|password

------------------------
