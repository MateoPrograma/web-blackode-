<?php
// 1. Incluye la clase de conexión
// Usa '..' para salir de la carpeta 'views', y luego entra a 'class'
require_once '../../class/autoload.php';

// =========================================================
// PARTE 1: DEFINICIÓN DE LA CLASE (EL MODELO)
// =========================================================
class Categorias {
    private $id;
    private $nombre;      
    private $db;

    public function __construct() {
        $this->db = new DataBase();
    }

    // Setters (Necesarios para pasar los datos del formulario a la clase)
    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }      

    // Método guardar (Lógica de inserción en la BD)
    public function guardar() {
        $sql = "INSERT INTO categoria (nombre) VALUES (?)";
        $params = [$this->nombre];
        return $this->db->insert($sql, $params);
    }
    
    // Método eliminar
    public function eliminar($id) {
        // Nota: la tabla en el DELETE es 'categorias' y en el INSERT es 'categoria'. 
        // Asegúrate de que los nombres de las tablas sean consistentes en tu BD.
        $sql = "DELETE FROM categorias WHERE id = ?"; 
        $params = [$id];
        return $this->db->delete($sql, $params);
    }
}


// =========================================================
// PARTE 2: LÓGICA DEL CONTROLADOR (EL CHEF/EJECUTOR)
// ESTA ES LA PARTE QUE FALTABA Y HACE EL GUARDADO REAL
// =========================================================

// 1. Verifica si la página fue accedida mediante el envío del formulario (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Comprueba que el campo 'nombre' fue enviado y no está vacío.
    // Recuerda que el 'name' en el HTML era 'nombre'.
    if (isset($_POST['nombre']) && !empty(trim($_POST['nombre']))) {
        
        // Limpiamos y capturamos el valor
        $nombre_categoria = htmlspecialchars(trim($_POST['nombre']));
        
        // 3. Instanciar la clase (Crear el objeto)
        $categoria = new Categorias();
        
        // 4. Asignar el valor recibido usando el setter
        $categoria->setNombre($nombre_categoria);
        
        // 5. Llamar al método guardar()
        if ($categoria->guardar()) {
            // Éxito: Muestra un mensaje y redirige
            echo "<h1>✅ ¡Categoría guardada con éxito!</h1>";
            // header("Location: /tu_formulario.html?status=success");
            // exit();
        } else {
            // Error en la base de datos (Ej: problema de conexión o tabla no encontrada)
            echo "<h1>❌ ERROR al guardar: No se pudo ejecutar la inserción en la base de datos.</h1>";
        }
    } else {
        echo "<h1>❌ ERROR: El nombre de la categoría no fue enviado o está vacío.</h1>";
    }
} else {
    // Si alguien intenta acceder a categorias.php directamente (sin enviar el formulario)
    // echo "Acceso no permitido."; 
}

?>