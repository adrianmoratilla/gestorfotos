## Explicación previa

La aplicación permite gestionar una colección de fotos, añadiendo las imágenes con un título y una calificación.
Cada usuario debe registrarse y tendrá su propia galería individual.


## Tecnologías usadas y justificación

- Laravel: Es el framework que actualmente más utilizo, por lo que me resulta más cómodo. No obstante, para la parte de la edición de fotos he decidido implementar las funcionalidades con JavaScript puro, en lugar de usar a fondo las plantillas de Laravel, para darle más variedad (y que así es más dinámico el manejo).
- SQLite: He decidido integrar la base de datos usando una interna en SQLite, por simplicidad para compartir el proyecto y que sea más fácil instalarlo y ponerlo a funcionar.
- Bootstrap: Para ahorrar tiempo en maquetación, he utilizado la librería Bootstrap, así que todo está hecho usando las clases y componentes que nos ofrece.
- Selector de estrellas: https://github.com/pryley/star-rating.js/blob/main/README.md

## Pasos para instalarlo

1. Descargar el proyecto.
2. Abrir una terminal ubicando la ruta en la carpeta donde se encuentre el proyecto.
3. Actualizar las dependencias ejecutando 'composer install' (será necesario instalar Composer antes si no se tiene).
4. Hacer una copia del archivo .env.example y renombrarlo a .env.
5. Ejecutar 'php artisan key:generate'.
6. Ejecutar 'php artisan database:generate'.
7. Ejecutar 'php artisan migrate'.
8. Arrancar la aplicación con 'php artisan serve' y conectar mediante el navegador en la dirección proporcionada por el comando.

Adicionalmente, pueden probarse los test (solo están implementados los referentes al usuario) con 'php artisan test'.