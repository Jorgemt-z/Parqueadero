# 🚗 Parqeo - Sistema de Control de Parqueadero

Parqeo es un sistema web desarrollado en **PHP, HTML, CSS y MySQL** para la gestión integral de un parqueadero.  
Permite registrar ingresos y salidas de vehículos, calcular tarifas según modalidad, administrar pagos y visualizar métricas en un panel de control.

---

## ✨ Características principales
- **Autenticación de usuarios** con roles básicos.
- **Registro de ingreso** de vehículos (placa, tipo, modalidad).
- **Registro de salida** con cálculo automático de tarifas (horas, días, mensual).
- **Gestión de pagos** vinculados a cada vehículo.
- **Panel de control** con alertas de mensualidades vencidas.
- **Dashboard** con métricas diarias (vehículos registrados, activos, pagos, vencimientos).
- **Historial completo** de ingresos, salidas y pagos.
- **Interfaz moderna y responsiva** con CSS personalizado.

---

## 📂 Estructura del proyecto
- `conexion.php` → Conexión a la base de datos.  
- `login.php` / `logout.php` → Módulos de autenticación.  
- `ingreso.php` → Registro de entrada de vehículos.  
- `salida.php` → Registro de salida y cálculo de tarifas/pagos.  
- `panel.php` → Panel principal con navegación y alertas.  
- `dashboard.php` → Métricas y estadísticas del sistema.  
- `historial.php` → Historial de vehículos y pagos.  
- `estilo.css` → Estilos visuales y diseño responsivo.  

---

## ⚙️ Requisitos
- Servidor web con soporte PHP (ej. XAMPP, WAMP, Laragon).  
- MySQL/MariaDB para la base de datos.  
- Navegador moderno (Chrome, Edge, Firefox).
