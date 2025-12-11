<?php

spl_autoload_register(function($clase) {
    $ruta = __DIR__ . '/' . $clase . '.php';
    if (file_exists($ruta)) {
        require_once $ruta;
    }
});

if (isset($_POST['action'])) {

    include_once 'database.php';
    include_once 'categorias.php';
    include_once 'productos.php';

    $db = new DataBase();

    switch ($_POST['action']) {

        case 'listarCategorias':
            $categorias = $db->select("SELECT * FROM categoria");
            foreach ($categorias as $cat) {
                echo "<tr>
                        <td>{$cat['id']}</td>
                        <td>{$cat['nombre']}</td>
                      </tr>";
            }
            break;

        case 'listarProductos':
            $productos = $db->select("SELECT * FROM productos");

            foreach ($productos as $prod) {

                // Ruta de imagen
                $rutaImagen = "../../assets/img/" . $prod['imagen'];  

                echo "<tr>
                        <td>{$prod['id']}</td>
                        <td><img src='$rutaImagen' width='80'></td>
                        <td>{$prod['producto']}</td>
                        <td>{$prod['descripcion']}</td>
                        <td>{$prod['categoria']}</td>
                        <td>{$prod['precio']}</td>
                      </tr>";
            }
            break;
    }
}
?>
