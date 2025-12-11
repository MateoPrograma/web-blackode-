<?php
// 1. Incluye la clase de conexión (asegúrate que el nombre 'data_base.php' es correcto)
// Usa '..' para salir de la carpeta 'views', y luego entra a 'class'
require_once '../../class/autoload.php';

// =========================================================
// PARTE 1: DEFINICIÓN DE LA CLASE PRODUCTOS (MODELO)
// =========================================================
class Productos {
    private $id;
    private $nombre;
    private $imagen;
    private $descripcion;
    private $categoria; // Almacenará categoria_id
    private $precio;
    private $db;

    public function __construct() {
        $this->db = new DataBase();
    }

    // --- Setters ---
    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setImagen($imagen) { $this->imagen = $imagen; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setCategoria($categoria) { $this->categoria = $categoria; } // Usado para categoria_id
    public function setPrecio($precio) { $this->precio = $precio; }

    public function guardar() {
    
    // Asumo que tu tabla tiene: id (AI), producto, precio, descripcion, categoria, imagen
    // Y que la columna 'categoria' almacena el ID de la categoría (mejor práctica: categoria_id)
    
    // SQL: 5 columnas y 5 tokens (si el ID es AUTO_INCREMENT)
    $sql = "INSERT INTO Productos (producto, precio, descripción, categoría, imagen) 
             VALUES (?, ?, ?, ?, ?)"; 
             //          ^  ^  ^  ^  ^ <-- ¡5 TOKENS!

    // Parámetros: 5 variables en el orden del SQL
    $params = [
        $this->nombre,
        $this->precio,
        $this->descripcion,
        $this->categoria,
        $this->imagen  // La ruta del archivo (o nombre)
    ]; 

    return $this->db->insert($sql, $params);
}
}


// =========================================================
// PARTE 2: LÓGICA DEL CONTROLADOR (EL EJECUTOR / PROCESADOR)
// ESTA ES LA PARTE QUE HACE EL GUARDADO REAL
// =========================================================

// =========================================================
// PARTE DEL CONTROLADOR QUE RECIBE EL FORMULARIO
// =========================================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- 1. VALIDACIÓN BÁSICA ---
    if (empty(trim($_POST['nombre_producto'])) || empty($_POST['precio']) || empty($_POST['categoria_id'])) {
        echo "<h1>❌ ERROR: Faltan campos obligatorios.</h1>";
        exit;
    }

    // --- 2. MANEJO Y MOVIDA DE LA IMAGEN ---
    $ruta_imagen_db = null; // Inicializamos a NULL si no hay subida o falla
    $directorio_destino = "../../assets/img/"; // La ruta que sale de 'views/' y 'backend/'
    
    // Asegúrate que la carpeta exista ANTES de intentar mover el archivo
    if (!is_dir($directorio_destino)) {
        // Podrías intentar crearla o dar un error más específico si no existe
    }
    
    // El nombre del campo de archivo es 'imagen_producto' (del HTML)
    if (isset($_FILES['imagen_producto']) && $_FILES['imagen_producto']['error'] === UPLOAD_ERR_OK) {
        
        $nombre_archivo = basename($_FILES['imagen_producto']['name']);
        $ruta_final = $directorio_destino . $nombre_archivo;
        
        // Intentamos mover el archivo temporal
        if (move_uploaded_file($_FILES['imagen_producto']['tmp_name'], $ruta_final)) {
            // ÉXITO: Guardamos SOLO el nombre del archivo para la base de datos
            $ruta_imagen_db = $nombre_archivo; 
        } else {
             // FALLO: Error de permisos o ruta. Asignamos un valor por defecto.
             echo "<h1>❌ ATENCIÓN: Falló la subida física del archivo.</h1>";
             $ruta_imagen_db = "default.jpg";
        }
    } else {
        // Si no se subió archivo y la columna 'imagen' permite NULL, esto está bien.
        // Si no permite NULL, $ruta_imagen_db debe ser "default.jpg".
    }

    // --- 3. CAPTURAR Y ASIGNAR VALORES ---
    
    // Capturamos las variables del formulario (usando los names del HTML)
    $nombre_producto = htmlspecialchars(trim($_POST['nombre_producto'])); 
    $descripcion_producto = htmlspecialchars(trim($_POST['descripcion']));
    $precio = (float) $_POST['precio']; 
    $categoria_id = (int) $_POST['categoria_id']; 

    // Instanciar la clase y asignar valores
    $producto = new Productos();
    $producto->setNombre($nombre_producto);
    $producto->setPrecio($precio);
    $producto->setDescripcion($descripcion_producto);
    $producto->setCategoria($categoria_id);
    $producto->setImagen($ruta_imagen_db); // Guarda el nombre/ruta para la BD
    
    // --- 4. GUARDAR EN LA BASE DE DATOS ---
    if ($producto->guardar()) {
        echo "<h1>✅ Producto '{$nombre_producto}' guardado con éxito!</h1>";
    } else {
        echo "<h1>❌ ERROR: No se pudo ejecutar la inserción en la base de datos.</h1>";
    }
}