<?php
/**
 * En este frame se van cargado cada una de las funcionalidades del sistema
 *
 * Descripcion Larga
 *
 * @category
 * @package      SGD Orfeo
 * @subpackage   Main
 * @author       Community
 * @author       Skina Technologies SAS (http://www.skinatech.com)
 * @license      GNU/GPL <http://www.gnu.org/licenses/gpl-2.0.html>
 * @link         http://www.orfeolibre.org
 * @version      SVN: $Id$
 * @since
 */
/* ---------------------------------------------------------+
  |                     INCLUDES                             |
  +--------------------------------------------------------- */


/* ---------------------------------------------------------+
  |                    DEFINICIONES                          |
  +--------------------------------------------------------- */
session_start();
error_reporting(7);
$url_raiz = "..";
$dir_raiz = $_SESSION['dir_raiz'];
$ESTILOS_PATH2 = $_SESSION['ESTILOS_PATH2'];
$assoc = $_SESSION['assoc'];
/* ---------------------------------------------------------+
  |                       MAIN                               |
  +--------------------------------------------------------- */

require_once("$dir_raiz/include/db/ConnectionHandler.php");

if (!$db)
    $db = new ConnectionHandler($dir_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);

include_once($dir_raiz . "/radicacion/crea_combos_universales.php");
//
//echo '<pre> ------> POST';
//print_r($_POST);
//echo '</pre>';

//En caso de no llegar la dependencia recupera la sesi�n
if (!$_SESSION['dependencia'])
    include "$dir_raiz/rec_session.php";
?>
<html>
    <head>
        <?php $url_raiz = "../../"; ?>
        <title>Orfeo. Consulta ESP</title>
        <link href="<?= $url_raiz . $ESTILOS_PATH2 ?>bootstrap.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="<?= $url_raiz . $_SESSION['ESTILOS_PATH_ORFEO'] ?>">
        <script language="JavaScript" src="../../js/crea_combos_2.js"></script>
        <script language="JavaScript">
<?php
//HLP. Convertimos los vectores de los paises, dptos y municipios creados en crea_combos_universales.php a vectores en JavaScript.
echo arrayToJsArray($vpaisesv, 'vp');
echo arrayToJsArray($vdptosv, 'vd');
echo arrayToJsArray($vmcposv, 'vm');
?>
            /**
             * Envia el formulario de acuerdo a la opcion seleccionada, que puede ser ver CSV o consultar
             */
            function enviar(argumento){
                document.formSeleccion.action = argumento + "&" + document.formSeleccion.params.value;
                document.formSeleccion.submit();
            }

            /*
             *	Funcion que se le envia el id del municipio en el formato general c-ppp-ddd-mmm y lo desgloza
             *	creando las variables en javascript para su uso individual, p.e. para los combos respectivos.
             */
            function crea_var_idlugar_defa(id_mcpio){
                if (id_mcpio == 0)
                    return;
                var str = id_mcpio.split('-');

                document.formSeleccion.idcont1.value = str[0] * 1;
                cambia(formSeleccion, 'idpais1', 'idcont1');
                document.formSeleccion.idpais1.value = str[1] * 1;
                cambia(formSeleccion, 'codep_us1', 'idpais1');
                document.formSeleccion.codep_us1.value = str[1] * 1 + '-' + str[2] * 1;
                cambia(formSeleccion, 'muni_us1', 'codep_us1');
                document.formSeleccion.muni_us1.value = str[1] * 1 + '-' + str[2] * 1 + '-' + str[3] * 1;
            }

            function activa_chk(forma) {	
                ////alert(forma.tbusqueda.value);
                //var obj = document.getElementById(chk_desact);
                if (forma.slc_tb.value == 0)
                    forma.chk_desact.disabled = false;
                else
                    forma.chk_desact.disabled = true;
            }
        </script>
    </head>
    <body onload="crea_var_idlugar_defa(<?php echo "'" . ($_SESSION['cod_local']) . "'"; ?>);">
        <?php
        $params = session_name() . "=" . session_id() . "&krd=$krd";
        ?>
        <form action="resultConsultaESP.php?<?= $params ?>" method="post" enctype="multipart/form-data" name="formSeleccion" id="formSeleccion" >
            <input type="hidden" name="selected0" value="<?= $selected0 ?>">
            <input type="hidden" name="selected1" value="<?= $selected1 ?>">
            <input type="hidden" name="selected2" value="<?= $selected2 ?>">
            <input type="hidden" name="selectedctt0" value="<?= $selectedctt0 ?>">
            <input type="hidden" name="selectedctt1" value="<?= $selectedctt1 ?>">
            <input type="hidden" name="selectedctt2" value="<?= $selectedctt2 ?>">
            <input type="hidden" name="tipo_masiva" value="<?= $_POST['masiva'] ?>">  <!-- Este valor viene cuando se invoca este archivo en selecConsultaESP.php -->
            <center>
                <br>
                <div id="titulo" style="width: 55%;" align="center">Consulta y selecci&oacute;n de destinatarios masiva</div>
                <table width="55%" border="1" cellspacing="5" cellpadding="0" align="center" class='borde_tab'>
                <!--	<tr align="center">
                                <td height="25" colspan="2" class='titulos2'>
                                        consulta y selecci&oacute;n de destinatarios masiva
                        </td>
                    </tr>-->
                    <tr align="center">
                    <input name="accion" type="hidden" id="accion">
                    <input type="hidden" name="params" value="<?= $params ?>">
                    <td width="31%" class='titulos2'><label for="nombre">Nombre</label></td>
                    <td width="69%" height="30" class='listado2' align="left">
                        <input name="nombre" id="nombre" type="input" size="50" class="tex_area" title="Escriba el nombre del destinatario">
                    </td>
                    </tr>
                    <tr align="center">
                        <td width="31%" class='titulos2'><label for="idcont1">Continente</label></td>
                        <td width="69%" height="30" class='listado2'>
                            <div align="left">
                                <?php
                                // Listamos los continentes.
                                echo $Rs_Cont->GetMenu2('idcont1', 0, "0:&lt;&lt; SELECCIONE &gt;&gt;", false, 0, "id=\"idcont1\" class=\"select\" title=\"Listado de continentes\" onchange=\"cambia(this.form,'idpais1','idcont1')\"");
                                $Rs_Cont->Move(0);
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr align="center">
                        <td width="31%" class='titulos2'><label for="idpais1">Pa&iacute;s</label></td>
                        <td width="69%" height="30" class='listado2'>
                            <div align="left">
                                <select name="idpais1" id="idpais1" class="select" onChange="cambia(this.form, 'codep_us1', 'idpais1')" title="Listado de paises">
                                    <option value="0" selected>&lt;&lt; Seleccione Continente &gt;&gt;</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr align="center">
                        <td width="31%" class='titulos2'><label for="codep_us1">Departamento</label></td>
                        <td height="30" class='listado2' width="69%">
                            <div align="left">
                                <select name='codep_us1' id ="codep_us1" class='select' onChange="cambia(this.form, 'muni_us1', 'codep_us1')" title="listado de departamentos"><option value='0' selected>&lt;&lt; Seleccione Pa&iacute;s &gt;&gt;</option></select>
                            </div>
                        </td>
                    </tr>
                    <tr align="center">
                        <td width="31%" class='titulos2'><label for="muni_us1">Municipio</label></td>
                        <td height="30" class='listado2' width="69%">
                            <div align="left">
                                <select title="Listado de municipios" name='muni_us1' id="muni_us1" class='select' ><option value='0' selected>&lt;&lt; Seleccione Dpto &gt;&gt;</option></select>
                            </div>
                        </td>
                    </tr>
                    <tr align="center">
                        <td width="31%" class='titulos2'><label for="slc_tb">Rango de Busqueda:</label></td>
                        <td height="30" class='listado2' width="69%" align="left">
                            <select name="slc_tb" id="slc_tb" class="select" onchange="activa_chk(this.form)" title="Lista de tipos de usuario ">
                                <!--Modificado skina-->
                                <option value="0" selected>Terceros</option>
                                <option value="1">Empresas</option>
                                <option value="2">Ciudadanos</option>
                            </select>&nbsp;&nbsp;
                            <input type="checkbox" name="chk_desact" id="chk_desact"><label for="chk_desact">Incluir no vigentes</label>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td class="titulos1" colspan="2">
                            <font style="font-family:verdana; font-size:x-small"><b>Nota:
                                <font color="Gray">
                                El conjunto de registros a seleccionar deben ir para un solo tipo de destino; es decir Local o Nacional o Internacional.
                                En caso de ser internacional la selecci&oacute;n ser&aacute; discriminada para solo el continente local o resto del mundo.
                            </b></font></font>
                        </td>
                    </tr>
                    <tr align="center">
                        <td height="30" colspan="2" class='listado1'>
                            <input name="Consultar" type="button"  class="botones" id="envia22"  onClick="enviar('resultConsultaESP.php?x=x');" value="Consultar">&nbsp;&nbsp;
                            <?php
                            //Si se ha seleccionado registros previamente se muestran los botones para guardar esta seleccion como CSV o para mostrarla como PDF
                            //Cada variable hace referencia al rango de busqueda del select 'slc_tb'
                            if (strlen(trim($selected0)) > 0 or strlen(trim($selected1)) > 0 or strlen(trim($selected2)) > 0) {
                                ?>
                                <input name="guardar" type="button" class="botones" id="envia22"  value="Guardar CSV" onClick="enviar('printConsultaESP.php?salida=csv');">&nbsp;&nbsp;
                                <input name="ver" type="button" class="botonesMediano2" id="envia22"  value="Ver Seleccionados"  onClick="enviar('printConsultaESP.php?salida=pdf');">
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </center>
        </form>
    </body>
</html>
