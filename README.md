
Prueba técnica para backends (PHP)
=========


Simple aplicación para gestionar usuarios, roles y permisos.


##### Pre-requisitos
------------------


- `PHP` *_required_*
	- Minimum version: `5.5`
	- `pdo_mysql` extension required


- `MySQL` *_required_*
	- Version `5.6+` recommended

- `Composer` *_required_*
	- Version `1.2.1+` recommended


##### Componentes de Composer
------------------
- `slim/slim`
	- Versión `3.10.0`


- `slim/twig-view`
	- Versión `2.4.0"`


##### Instalación
------------

#### Clonar / Descargar del repositorio
	$ git clone https://github.com/miiglesi/testBackend.git

#### Instala dependencias de  `Composer`
	$ composer install 

#### Instala la base de datos con el fichero ./app/testbackend.sql

#### Ir a ./app/setting.php y configurar parametros de BBDD


##### Ejecutar aplicación:
------------


    $ php -S localhost:8000 -t ./public



##### Usuarios
------------

| USUARIO | PASSWORD | ROLES |
| --- | --- | --- |
|admin	| Admin00!!	| ADMIN
|usuario1	| Admin00!!	| PAGE_1
|usuario2	| Admin00!!	| PAGE_2
|usuario3	| Admin00!!	| PAGE_3
|usuario4	| Admin00!!	| PAGE_1,PAGE_2,PAGE_3
