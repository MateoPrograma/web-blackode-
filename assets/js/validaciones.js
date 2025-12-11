// Este script asume que jQuery (código que empieza con $) ya fue cargado.

// --------------------------------------------------------
// 1. VALIDACIÓN DEL FORMULARIO DE CATEGORÍAS
// --------------------------------------------------------
$("#form_categorias").submit(function(){
    // Se usa #Nombre porque es el ID del campo de la categoría en tu HTML corregido.
    var nombre = $("#Nombre").val(); 

    // Verifica si el valor (trim elimina espacios en blanco al inicio y al final) está vacío
    if($.trim(nombre)===''){
      alert("Debe completar el nombre de la categoría.\nMateo Diocares");
      // Retorna false, lo que detiene el envío del formulario a categorias.php
      return false; 
    }
    // Retorna true, lo que permite el envío del formulario a categorias.php
    return true; 
});

// --------------------------------------------------------
// 2. VALIDACIÓN DEL FORMULARIO DE PRODUCTOS
// --------------------------------------------------------
$("#form_productos").submit(function(){
    // Se usa #nombre_producto que es el ID del campo de producto en tu HTML corregido.
    var nombre = $("#nombre_producto").val();

    // Verifica si el campo de nombre del producto está vacío
    if($.trim(nombre)===''){
      alert("Debe colocar el nombre del producto.\nMateo Diocares");
      // Detiene el envío del formulario
      return false; 
    }
    // Permite el envío del formulario a productos.php
    return true; 
});