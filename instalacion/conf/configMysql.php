<?php 
/**
 * Archivo de Configuracion ejemplo 
 *
 * @category  
 * @package      SGD Orfeo
 * @subpackage   
 * @author       Jaime E. Gomez H.
 * @author       Skina Technologies SAS (http://www.skinatech.com)
 * @license      GNU/GPL <http://www.gnu.org/licenses/gpl-2.0.html>
 * @link         http://www.orfeolibre.org
 * @version      SVN: $Id$
 * @since        
 */

//############# Datos de Empresa instalada

$entidad= "Skina Technologies";                 //Acronimo de la empresa  //Nombre de la EmpresaCD
$entidad_largo= 'Skina Technologies';   //Variable usada generalmente para los titulos en informes.
$nit_entidad = '892,300,285-6';             // Nit de la empresa
$entidad_tel =  'PBX: +57  2262080';             //Telefono o PBX de la empresa.
$entidad_contacto = "http://www.skinatech.com.co";    //URL de contacto de la empresa o entidad
$entidad_dir = "Carrera 64 #96-17 Bogotá - Colombia";  //Direccion de la Empresa.
$pais = "Colombia";                         // Pais Empresa o Entidad

//############# Servidor de Bases de datos

$driver = "mysql";
$servidor="192.168.8.26:3306";
$servicio="orfeodb";
$usuario="admin";
$contrasena ='sveT1a%a';
$db = $servicio;
$assoc = 0; // Identifica si las columnas van en mayuscula o minuscula solo aplica para MariaDB

//#############  Servidor de correo Electronico

$servidor_mail_imap="imap.gmail.com";  //Servidor de consulta de correo
$protocolo_mail="imaps";              // Protocolo e consulta correo ( imap  | pop3 )
$puerto_mail_imap=993;                     // Puerto de consulta de correo
                                      
$servidor_mail_smtp="smtp.gmail.com";  // Servidor de Salida
$puerto_mail_smtp=587;                     // Puerto del servidor de Mail (25 | 587)
$cuenta_mail="soporte.skinatech@gmail.com";      // Usuario de conexion
$contrasena_mail="mUra&am1";           //  Contrasena

//#############  Servidor de autenticacion LDAP / AD

//Nombre o IP del servidor de autenticacion LDAP
//- $ldapServer = '192.1.1.100';
//Cadena de busqueda en el servidor.
//- $cadenaBusqLDAP = 'dc=skinatech,dc=com';
//Campo seleccionado (de las variables LDAP) para realizar la autenticacion.
//- $campoBusqLDAP = 'sAMAccountName';

//############# Librerias externas
/**
*	Se crea la variable $ADODB_PATH.
*	El Objetivo es que al independizar ADODB de ORFEO, este (ADODB) se pueda actualizar sin causar
*	traumatismos en el resto del codigo de ORFEO. En adelante se utilizara esta variable para hacer
*	referencia donde se encuentre ADODB
*/

$ADODB_PATH = "/var/www/html/orfeo/adodb";
$ADODB_CACHE_DIR = "/tmp";

// Variables que se usan para la radicacion del correo electronico
// Sitio en el que encontramos la libreria pear instalada
$PEAR_PATH="/var/www/html/orfeo/pear";

//#############  Configuracion / Personalizacion Orfeo

$menuAdicional = 0;
// 0 = Carpeta salida del radicador	>0 = Redirecciona a la dependencia especificada
$usua_perm_avaz = 1;
//Habilita la configuracion avanzada de usuarios
 //Variable que indica el ambiente de trabajo, sus valores pueden ser  desarrollo,prueba,orfeo
$ambiente = "orfeo";
//Servidor que procesa los documentos
$servProcDocs = "127.0.0.1:8000";

$MODULO_RADICACION_DOCS_ANEXOS=1;
$MODULO_ENVIOS = 2;

//Color de Fondo de OrfeoGPL
$colorFondo = "8cacc1";
// Correo Contacto o Administrador del Sistema
$administrador = "soporte@gmail.com";

// Directorio de estilos a Usar... Si no se establece una Ruta el sistema usara
//el que posee por Defecto en el directorio estilos.  
//orfeo.css para usarlo cree una carpeta con su personalizacion y luego copie 
//el archivo orfeo.css y cambie sus colores.
$ESTILOS_PATH = "/estilos/orfeo38/";
//ruta para estilos de prueba
$ESTILOS_PATH2 = "/estilos/orfeo50/";
$ESTILOS_PATH_ORFEO = "/estilos/orfeo.css";
$logoSuperiorOrfeo=true;  //Logo orfeo en el header
$imagenes = "imagenes";
$imagenes2 = "/estilos/orfeo50/imagenes50/";
$dependenciaPruebas= "998"; //Codigo dep pruebas, en consultas no tiene encuenta esta dep para listar radicados
$dependenciaSalida = "999";
$tipoRadicadoPqr = '4'; // Datos configuración para el formulario web pqrs 
$longitud_codigo_dependencia = '3';

// Dependencia encargada de manejar las PQRs
$depeRadicaFormularioWeb = str_pad("998",$longitud_codigo_dependencia,'0', STR_PAD_LEFT); 
$entidad_depsal = str_pad("999",$longitud_codigo_dependencia,'0', STR_PAD_LEFT);//Guarda el codigo de la dependencia de salida por defecto al radicar dcto de salida
$usuaRecibeWeb           = 1;  // Usuario que va a recepcionar los radicados web-pqr
$secRadicaFormularioWeb  = "secr_tp4_998"; // Esta secuencia es para los consecutivos de PQR para pruebas 
?>
