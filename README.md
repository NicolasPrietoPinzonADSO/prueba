## Listado de requerimientos y solución de errores a sistema trabajado en clase. 

- Grupo 1
  - Andres Vargas 
  - Jhoan Garcia
- Grupo 2: 
  - MIshell Robles 
  - Nini Rios 
  - Zulay Ortegón 
- Grupo 3: 
  - Nicolas Prieto Pinzón 
  - Jesus David Jaimes 
  - Jarminthon Rueda
- Grupo 4: 
  - Brayan Danilo Florez 
  - Jhon Deiby Silva 
  - Jhonatan David Motta 
- Grupo 5: 
  - Camilo Mora 
  - Camilo Hernandez  
  - Yenny Marcela
- Grupo 6: 
  - Jhoan SEbastian Gelves 
  - Nicolle Daniela 
  - Jose Rey
- Grupo 7: 
  - Darir Ferney Bernal 
- Grupo 7: 
  - Jhodan Sebastian Corredor 
- Grupo 9: 
  - Sebastian Romero
  - Pedro Antonio

## Puntos a solucionar
- Validación de datos en el registro usuario, el registro queda consignado en la base de datos, pero el sistema me responde con un error bloqueante, el sistema al ocurrir un error bebe retroceder el registro y no quedar almacenados los datos en la base de datos, se debe informar al usuario los errores ocurridos para que él pueda solucionarlos. 
- Asignar los permisos a los roles, debe permitir asignar n cantidad de permisos y de igual forma retirar dichos permisos. 
- Permitir filtrar los permisos, el usuario puede ingresar una palabra clave y el sistema busca el resultado más acertado, de igual forma para los roles. 
- Validar que el usuario este autenticado, caso contrario el usuario se debe regresar a la vista login, esto en todas las rutas que sean protegidas por autenticación de usuario. 
- Validar si el usuario tiene permiso para ingresar a una ruta protegido, el usuario debe estar autenticado y con un rol asignado. Cada rol tiene una lista de permisos y con los permisos se validar si el usuario puede o no realizar la acción. 
- Ocultar las acciones que el usuario autenticado no pueda realizar, si el usuario autenticado tiene permisos para listar roles, pero no tiene permisos para editarlos, los botones o botón de editar no puede ser visible para este usuario. 
- Cuando el usuario este autenticado y el intente regresar o ver la vita del login, se debe enviar a la vista principal del sistema autenticado. 
- Cuando el usuario intente eliminar un permiso se requiere una confirmación, si el usuario confirma que, si desea eliminar el registro, el sistema procede a eliminar dicho registro, caso contrario no debe ocurrir nada en el sistema. 
- Realizar un paginado para la lista de permisos, se debe poder listar 12 registros por página y el usuario podrá ir a la siguiente página o regresar a la página anterior. 