# examenVaKeVA
Examen técnico para evaluación de conocimientos. Realizado por Pablo César Sánchez Porta.

#Indicaciones

Principales:
Abrir un repositorio en Github o Bitbucket. -> Github
Compartir el acceso del mismo (dentro de las primeras 24 horas) a la siguiente cuenta de correo: "enviado"
Crear un sistema de usuarios en PHP.
El registro/login de los mismos debe ser por FB Connect.
 
Los usuarios deben ser capaces de agregar cifras numéricas como registros a una tabla en la base de datos (identificando al usuario que lo hizo y la fecha-hora en que se agregaron estos datos).

Las cantidades capturadas no deben repetirse (ni por el mismo usuario que las puso originalmente).

Además del formulario de captura de cantidades, el usuario debe ser capaz de ver en todo momento las cifras que ha capturado correctamente, y ordenar las mismas por su valor así como por la fecha-hora.


Adicionales:

Tras 3 errores (por repetición) en un lapso inferior a 1 minuto, el usuario quedará inhabilitado para seguir registrando más números hasta que haya transcurrido 1 minuto desde su última repetición.

Tras 10 envíos en un lapso inferior a 1 minuto, el usuario tendrá que proveer un captcha junto con cada envío posterior hasta que haya transcurrido 1 minuto desde su último envío de formulario.

Los usuarios deben tener identificadores únicos, no incrementales, aleatorios, constituidos por números y letras (case insensitive).

Se debe contar con una ruta amigable para acceder al perfil de otros usuarios, que contará con la información de perfil y el número de registros que este usuario ha generado (sin la lista).

El perfil del usuario debe mostrar una versión modificada de la foto provista por FB Connect (p.ej.: negativo, marca de agua, etc.), que se debe almacenar en el sistema junto con la original, para evitar depender de FB.


Notas:
La organización del repositorio también será tomada en cuenta.
La retroalimentación para el usuario es un factor deseable.
Todo dato no especificado en los puntos anteriores es a libre criterio, solo que deberá documentarse lo que elijas en estos casos.
Cualquier framework, plugin, biblioteca, servicio o tecnología utilizados en el proyecto debe especificarse dentro del mismo.
Documentar los pasos necesarios para la reproducción del proyecto (obviando la infraestructura del servidor).

Será tomado en cuenta, por un lado, el avance que tenga el repositorio tras las primeras 24 horas; posterior a eso, cualquier mejora en las siguientes 24 horas sería un plus. Procura enfocarte en los requerimientos puntuales primero.

