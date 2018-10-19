
Instalación:

- Entorno

He creado el fichero application/src/Config/Config.php en el que se encuentra la configuración de la app.

También he creado el fichero application/src/Config/Env.php que sobrescribe las constantes necesarias para que funcione la app.   El objetivo de este fichero es que no se trackee en el repositorio y que pueda haber diferentes Env.php dependiendo del entorno con diferentes configuraciones, como por ejemplo el acceso a la Base de datos.

He creado también el Env.sample.php que si se trackearía para que cuando se instale en otro entorno, tan sólo haya que renombrarlo y cambiar valores.

- Base de datos

Para poder ver la api hay que poblar la base de datos, y para ello he creado varios scripts sql.

Los scripts para la creación y población de las tablas necesarias están en la carpeta aplication/src/Database/scripts, y los archivos son:

- Creación de las tablas -> application/src/Database/scripts/migrations.sql
- Poblar tablas con datos -> application/src/Database/scripts/seeders.sql
- Las dos juntas -> application/src/Database/scripts/querys.sql

Si se prefiere se puede lanzar el script querys.sql y se crearan todos los datos necesarios para la prueba o por separado, primero migrations.sql y despues seeders.sql.




Rutas creadas:

Web

- GET /
	- Obtiene la página de inicio, cargando el fichero application/public/home.html, en el que está el front con el formulario para consumir la api hacia la ruta /api/search/{term}

- GET /.*
    - Cualquier ruta que se pida y que no esté definida pasará por aqui.
	- Obtiene la página de de error 404, cargando el fichero application/public/errors/404.html.   

Api

- GET /api/search/{term}
	- Obtiene la información de relacionada con las propiedades del usuario.

- GET /api/user/{id}
	- Obtiene la información de el usuario con el id seleccionado
    - Con esta ruta no he hecho nada, pero la intención es que desde el front cuando se pintara el resultado de la búsqueda, poner un enlace que me llevara a otra vista con la información pormenorizada del usuario.



Testing con PHPUnit

Para el testing he inlcuido el componente PHPUnit en composer.json sólo en require-dev.

Los test están en la carpeta application/tests.

También he includio el archivo phpunit.xml con la configuración necesaria para que apunte a esa carpeta y tan sólo se tenga que lanzar el comando vendor/bin/phpunit

Para probar con PHPUnit es necesario que en archivo de configuración Env.php del entorno se añada el host desde el que estamos probando la app, a no ser que sea localhost, con la constante TEST_HOST_CONFIG.

Esto es así porque para hacer los test he includio en composer.json require-dev el componente guzzlehttp/guzzle para facilitar las peticiones Http.  Tan sólo lo he utilizado para el testing con PHPUnit.


Observaciones:

La api consiste en una mini aplicación web que permita localizar personas y sus características que tengan un determinado valor. Por ejemplo, disponiendo de la información de tres personas:
- La primera (Juan) tiene las características  "color de los ojos" y "color del coche", ambas con valor "azul claro"

- La segunda (Irene) tiene las características "color de los ojos", "color de la casa" y "color del coche" con los valores "azulados", "azul" y "rojo"

- La tercera (Manuel) tiene únicamente la característica "color de la casa" con un color "naranja"

    
Por ejemplo, si el usuario busca "azu", el sistema debería devolverle:
- Manuel
    - color de los ojos: "azul oscuro"
    - color del coche: "azul claro"
- Irene
    - color de los ojos: "azulados"
    - color de la coche: "azul"

Probar la app

Para probar la app tan sólo hay que ir a la ruta / y en el único input de tipo text que hay poner el término que queremos buscar.   Si existe en la Base de Datos, nos pintará con javascript el un listado de usuarios que tienen alguna propiedad cuyo valor, o una parte del valor de esa propiedad, sea como la que se incluye en la búsqueda.
