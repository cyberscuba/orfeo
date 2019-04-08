<?php

/* * ********************************************************************************** */
/* ORFEO GPL:Sistema de Gestion Documental		http://www.orfeogpl.org	     */
/* 	Idea Original de la SUPERINTENDENCIA DE SERVICIOS PUBLICOS DOMICILIARIOS     */
/* 				COLOMBIA TEL. (57) (1) 6913005  orfeogpl@gmail.com   */
/* ===========================                                                       */
/*                                                                                   */
/* Este programa es software libre. usted puede redistribuirlo y/o modificarlo       */
/* bajo los terminos de la licencia GNU General Public publicada por                 */
/* la "Free Software Foundation"; Licencia version 2. 			             */
/*                                                                                   */
/* Copyright (c) 2005 por :	  	  	                                     */
/* SSPD "Superintendencia de Servicios Publicos Domiciliarios"                       */
/*   Jairo Hernan Losada  jlosada@gmail.com                Desarrollador             */
/*   Sixto Angel Pinzón López --- angel.pinzon@gmail.com   Desarrollador             */
/* C.R.A.  "COMISION DE REGULACION DE AGUAS Y SANEAMIENTO AMBIENTAL"                 */
/*   Liliana Gomez        lgomezv@gmail.com                Desarrolladora            */
/*   Lucia Ojeda          lojedaster@gmail.com             Desarrolladora            */
/* D.N.P. "Departamento Nacional de Planeación"                                      */
/*   Hollman Ladino       hladino@gmail.com                Desarrollador             */
/*                                                                                   */
/* Colocar desde esta lInea las Modificaciones Realizadas Luego de la Version 3.5    */
/*  Nombre Desarrollador   Correo     Fecha   Modificacion                           */
/* * ********************************************************************************** */

/** CONSULTA 011
 * Estadiscas de Numero de Radicados digitalizados y Hojas Digitalizadas.
 * @autor JAIRO H LOSADA - SSPD
 * @version ORFEO 3.1
 *
 */
/* include_once "$ruta_raiz/include/db/ConnectionHandler.php";
  $db2 = new ConnectionHandler($ruta_raiz);

  $db2->conn->SetFetchMode(ADODB_FETCH_ASSOC);
  //$db2->conn->debug = true;
 */
$coltp3Esp = '"' . $tip3Nombre[3][2] . '"';
if (!$orno)
    $orno = 2;
/**
 * $db-driver Variable que trae el driver seleccionado en la conexion
 * @var string
 * @access public
 */
/**
 * $fecha_ini Variable que trae la fecha de Inicio Seleccionada  viene en formato Y-m-d
 * @var string
 * @access public
 */
/**
 * $fecha_fin Variable que trae la fecha de Fin Seleccionada
 * @var string
 * @access public
 */
/**
 * $mrecCodi Variable que trae el medio de recepcion por el cual va a sacar el detalle de la Consulta.
 * @var string
 * @access public
 */
if ($dependencia_busq != '99999')
    $condicionE = " AND h.DEPE_CODI='$dependencia_busq' ";

switch ($db->driver) {
    case 'mssql':
    case 'mysql': {
            $queryE = "SELECT b.USUA_NOMB AS USUARIO
                        , count(a.anex_numero) AS RADICADOS
                        , SUM(a.anex_num_hoja) AS HOJAS_DIGITALIZADAS
                        , MIN(b.USUA_DOC) AS HID_COD_USUARIO
                FROM  RADICADO r, USUARIO b, HIST_EVENTOS h, ANEXOS a
                WHERE
                        h.USUA_DOC=b.usua_DOC
                        $condicionE
                        AND h.RADI_NUME_RADI=a.ANEX_RADI_NUME
                        AND r.RADI_NUME_RADI=a.ANEX_RADI_NUME
                        AND h.SGD_TTR_CODIGO in (29)

                        AND " . $db->conn->SQLDate('Y/m/d', 'h.hist_fech') . " BETWEEN '$fecha_ini' AND '$fecha_fin'
                        AND " . $db->conn->SQLDate('Y/m/d HH:MI', 'h.hist_fech') . "=" . $db->conn->SQLDate('Y/m/d HH:MI', 'a.ANEX_FECH_ANEX') . "
                        AND " . $db->conn->SQLDate('Y/m/d', 'a.ANEX_FECH_ANEX') . " BETWEEN '$fecha_ini' AND '$fecha_fin'
                $whereTipoRadicado
                GROUP BY b.USUA_NOMB
                ORDER BY $orno $ascdesc";

            //  consulta  para traer anexos y numero de hojas en anexos
            $queryEDetalle = "SELECT a.ANEX_CODIGO  AS ANEX_CODIGO
                        , r.RADI_NUME_RADI AS RADICADO
                        , k.SGD_TPR_DESCRIP AS TIPO_DOCUMENTO
                        , b.USUA_NOMB AS USUARIO_DIGITALIZADOR
                        , h.HIST_OBSE AS OBSERVACIONES
                        , CAST(r.RADI_FECH_RADI AS CHAR) AS FECHA_RADICACION
                        , CAST(h.HIST_FECH AS CHAR) AS FECHA_DIGITALIZACION
                        , mr.mrec_desc AS MEDIO_RECEPCION_ENVIO
                        , r.RADI_PATH AS HID_RADI_PATH{$seguridad}
                        , a.ANEX_NOMB_ARCHIVO  as  ANEX_NOMBRE
                        FROM RADICADO r, USUARIO b, HIST_EVENTOS h, MEDIO_RECEPCION mr, SGD_TPR_TPDCUMENTO k, ANEXOS a
                    WHERE
                        h.USUA_DOC=b.usua_DOC
                        $condicionE
                        AND h.RADI_NUME_RADI=r.RADI_NUME_RADI
                        AND r.RADI_NUME_RADI=a.ANEX_RADI_NUME
                        AND r.tdoc_codi=k.sgd_tpr_codigo
                        AND r.MREC_CODI=mr.MREC_CODI
                        AND b.USUA_DOC= '$codUs'
                        AND h.SGD_TTR_CODIGO in (29)  
                        AND  " . $db->conn->SQLDate('Y/m/d', 'h.hist_fech') . " BETWEEN '$fecha_ini' AND '$fecha_fin'
                        $whereTipoRadicado
                       AND " . $db->conn->SQLDate('Y/m/d', 'h.hist_fech') . "=" . $db->conn->SQLDate('Y/m/d', 'a.ANEX_FECH_ANEX') . "
                        AND " . $db->conn->SQLDate('Y/m/d', 'a.ANEX_FECH_ANEX') . " BETWEEN '$fecha_ini' AND '$fecha_fin'
                ORDER BY $orno $ascdesc";
        }break;
    case 'postgres': {
            $queryE = "
			    SELECT b.USUA_NOMB AS USUARIO
					, count(a.anex_numero) AS RADICADOS
					, SUM(a.anex_num_hoja) AS HOJAS_DIGITALIZADAS
					, MIN(b.USUA_DOC) AS HID_COD_USUARIO
				FROM  RADICADO r, USUARIO b, HIST_EVENTOS h, ANEXOS a
				WHERE
					h.USUA_DOC=b.usua_DOC
					$condicionE
					AND h.RADI_NUME_RADI=a.ANEX_RADI_NUME
					AND r.RADI_NUME_RADI=a.ANEX_RADI_NUME
					AND h.SGD_TTR_CODIGO in (29)
                                        
                                        AND " . $db->conn->SQLDate('Y/m/d', 'h.hist_fech') . " BETWEEN '$fecha_ini' AND '$fecha_fin'
			 		AND TO_CHAR(h.hist_fech, 'YYYY/MM/DD HH:MI')=TO_CHAR(a.ANEX_FECH_ANEX, 'YYYY/MM/DD HH:MI') 
                                        AND TO_CHAR(a.ANEX_FECH_ANEX, 'YYYY/MM/DD') BETWEEN '$fecha_ini' AND '$fecha_fin'
				$whereTipoRadicado
			GROUP BY b.USUA_NOMB
			ORDER BY $orno $ascdesc";

            //  consulta  para traer anexos y numero de hojas en anexos
            // $queryANEX="";

            $queryEDetalle = "SELECT
                                          a.ANEX_CODIGO  AS ANEX_CODIGO
                                        , r.RADI_NUME_RADI AS RADICADO
                                        , k.SGD_TPR_DESCRIP AS TIPO_DOCUMENTO
                                        , b.USUA_NOMB AS USUARIO_DIGITALIZADOR
                                        , h.HIST_OBSE AS OBSERVACIONES
                                        , CAST(r.RADI_FECH_RADI AS VARCHAR) AS FECHA_RADICACION
                                        , CAST(h.HIST_FECH AS VARCHAR) AS FECHA_DIGITALIZACION
                                        , mr.mrec_desc AS MEDIO_RECEPCION_ENVIO
                                        , r.RADI_PATH AS HID_RADI_PATH{$seguridad}
                                        , a.ANEX_NOMB_ARCHIVO  as  ANEX_NOMBRE
                                        FROM RADICADO r, USUARIO b, HIST_EVENTOS h, MEDIO_RECEPCION mr, SGD_TPR_TPDCUMENTO k, ANEXOS a
                            WHERE
                                h.USUA_DOC=b.usua_DOC
                                $condicionE
                                AND h.RADI_NUME_RADI=r.RADI_NUME_RADI
                                AND r.RADI_NUME_RADI=a.ANEX_RADI_NUME
                                AND r.tdoc_codi=k.sgd_tpr_codigo
                                AND r.MREC_CODI=mr.MREC_CODI
                                AND b.USUA_DOC= '$codUs'
                                AND h.SGD_TTR_CODIGO in (29)  
                                AND  " . $db->conn->SQLDate('Y/m/d', 'h.hist_fech') . " BETWEEN '$fecha_ini' AND '$fecha_fin'
                                        $whereTipoRadicado
                                       AND TO_CHAR(h.hist_fech, 'YYYY/MM/DD HH:MI')=TO_CHAR(a.ANEX_FECH_ANEX, 'YYYY/MM/DD HH:MI') 
                                       AND TO_CHAR(a.ANEX_FECH_ANEX, 'YYYY/MM/DD') BETWEEN '$fecha_ini' AND '$fecha_fin'

                        ORDER BY $orno $ascdesc";
            //       AND TO_CHAR(h.hist_fech, 'YYYY/MM/DD')=TO_CHAR(a.ANEX_FECH_ANEX, 'YYYY/MM/DD') 
            //                      AND TO_CHAR(a.ANEX_FECH_ANEX, 'YYYY/MM/DD') BETWEEN '$fecha_ini' AND '$fecha_fin'
        }break;
    case 'oracle':
    case 'oci8':
    case 'oci805':
    case 'ocipo': {
            $queryE = "
				SELECT b.USUA_NOMB USUARIO
					, count(*) RADICADOS
					, SUM(r.RADI_NUME_HOJA) 	HOJAS_DIGITALIZADAS
					, MIN(b.USUA_DOC) HID_COD_USUARIO
					FROM RADICADO r, USUARIO b, HIST_EVENTOS h
				WHERE
					h.USUA_DOC=b.usua_DOC
					$condicionE
					AND h.RADI_NUME_RADI=r.RADI_NUME_RADI
					AND h.SGD_TTR_CODIGO in (22,23)
					AND TO_CHAR(h.hist_fech,'yyyy/mm/dd') BETWEEN '$fecha_ini'  AND '$fecha_fin'
				$whereTipoRadicado
				GROUP BY b.USUA_NOMB
				ORDER BY $orno $ascdesc";

            /** CONSULTA PARA VER DETALLES
             */
            $queryEDetalle = "SELECT
				r.RADI_NUME_RADI RADICADO
				, k.SGD_TPR_DESCRIP TIPO_DOCUMENTO
				, b.USUA_NOMB USUARIO_DIGITALIZADOR
				, h.HIST_OBSE OBSERVACIONES
				, TO_CHAR(r.RADI_FECH_RADI, 'DD/MM/YYYY HH24:MI:SS') FECHA_RADICACION
				, TO_CHAR(h.HIST_FECH, 'DD/MM/YYYY HH24:MI:SS') FECHA_DIGITALIZACION
				, mr.mrec_desc MEDIO_RECEPCION_ENVIO
				, r.RADI_PATH HID_RADI_PATH{$seguridad}
				FROM RADICADO r, USUARIO b, HIST_EVENTOS h, MEDIO_RECEPCION mr, SGD_TPR_TPDCUMENTO k
			WHERE
				h.USUA_DOC=b.usua_DOC
				$condicionE
				AND h.RADI_NUME_RADI=r.RADI_NUME_RADI
				and r.tdoc_codi=k.sgd_tpr_codigo
				AND r.MREC_CODI=mr.MREC_CODI(+)
				AND b.USUA_DOC= '$codUs'
				AND h.SGD_TTR_CODIGO in (22,23)
				AND TO_CHAR(h.hist_fech,'yyyy/mm/dd') BETWEEN '$fecha_ini'  AND '$fecha_fin'
				$whereTipoRadicado
			ORDER BY $orno $ascdesc";
        }break;
}
//  skina 13-10-2011
if (isset($_GET['genDetalle']) && $_GET['denDetalle'] = 1)
    $titulos = array("#", "1#RADICADO", "2#ANEXOS", "3#TIPO DE DOCUMENTO", "4#USUARIO DIGITALIZADOR", "5#OBSERVACIONES", "6#FECHA RADICACION", "7#FECHA  DE DIGITALIZACION", "8#MEDIO DE RECEPCION");
else
    $titulos = array("#", "1#Usuario", "2#TOTAL MODIFICADOS", "3#HOJAS DIGITALIZADAS");

function pintarEstadistica($fila, $indice, $numColumna) {
    global $ruta_raiz, $_POST, $_GET, $krd;

    if (isset($fila['USUARIO'])) {
        $usuario = $fila['USUARIO'];
        $radicado = $fila['RADICADOS'];
        $hojasDig = $fila['HOJAS_DIGITALIZADAS'];
        $hidCod = $fila['HID_COD_USUARIO'];
    } else {
        $usuario = $fila[0];
        $radicado = $fila[1];
        $hojasDig = $fila[2];
        $hidCod = $fila[3];
    }

    $salida = "";
    switch ($numColumna) {
        case 0:
            $salida = $indice;
            break;
        case 1:
            $salida = $usuario;
            break;
        case 2:
            $datosEnvioDetalle = "tipoEstadistica=" . $_POST['tipoEstadistica'] . "&amp;genDetalle=1&amp;usua_doc=" . urlencode($hidCod) . "&amp;dependencia_busq=" . $_POST['dependencia_busq'] . "&amp;fecha_ini=" . $_POST['fecha_ini'] . "&amp;fecha_fin=" . $_POST['fecha_fin'] . "&amp;tipoRadicado=" . $_POST['tipoRadicado'] . "&amp;tipoDocumento=" . $_POST['tipoDocumento'] . "&amp;codUs=" . $hidCod . "&amp;depeUs=" . $fila['HID_DEPE_USUA'];
            $datosEnvioDetalle = (isset($_POST['usActivos'])) ? $datosEnvioDetalle . "&amp;usActivos=" . $_POST['usActivos'] : $datosEnvioDetalle;
            $salida = "<a href=\"genEstadistica.php?{$datosEnvioDetalle}&amp;krd={$krd}\" target=\"detallesSec\" >" . $radicado . "</a>";
            break;
        case 3:
            $salida = $hojasDig + $hojas_anexos;
            break;
        default: $salida = false;
    }
    return $salida;
}

function pintarEstadisticaDetalle($fila, $indice, $numColumna) {
    global $ruta_raiz, $encabezado, $krd;

    if (isset($fila['ANEX_CODIGO'])) {
        $anexCodi = $fila['ANEX_CODIGO'];
        $radicado = $fila['RADICADO'];
        $tipoDocs = $fila['TIPO_DOCUMENTO'];
        $usuaDigi = $fila['USUARIO_DIGITALIZADOR'];
        $observaciones = $fila['OBSERVACIONES'];
        $fecharadicado = $fila['FECHA_RADICACION'];
        $fechaDig = $fila['FECHA_DIGITALIZACION'];
        $medioReceo = $fila['MEDIO_RECEPCION_ENVIO'];
        $anexNomb = $fila['ANEX_NOMBRE'];
    } else {
        $anexCodi = $fila[0];
        $radicado = $fila[1];
        $tipoDocs = $fila[2];
        $usuaDigi = $fila[3];
        $observaciones = $fila[4];
        $fecharadicado = $fila[5];
        $fechaDig = $fila[6];
        $medioReceo = $fila[7];
        $radiPath = $fila[8];
        $anexNomb = $fila[9];
    }

    $verImg = ($fila['SGD_SPUB_CODIGO'] == 1) ? ($usuaDigi != $_SESSION['usua_nomb'] ? false : true) : ($fila['USUA_NIVEL'] > $_SESSION['nivelus'] ? false : true);
    $numRadicado = $radicado;

    $path_anex = substr($radiPath, 0, 10) . "docs/";

    switch ($numColumna) {
        case 0:
            $salida = $indice;
            break;
        case 1:
            if ($radiPath && $verImg)
                $salida = "<center><a href=\"{$ruta_raiz}bodega" . $radiPath . "\">" . $radicado . "</a></center>"; // echo  $salida;
            else
                $salida = "<center class=\"leidos\">{$radicado}</center>";
            break;
        case 2:  //   fila   codigo  anexo
            //	$salida="<center class=\"leidos\">".$fila['ANEX_CODIGO']."</center>";
            $salida = "<center><a href=\"{$ruta_raiz}bodega" . $path_anex . $anexNomb . "\">" . $anexCodi . "</a></center>";
            break;
        case 3:
            $salida = "<center class=\"leidos\">" . $tipoDocs . "</center>";
            break;
        case 4:
            $salida = "<center class=\"leidos\">" . $usuaDigi. "</center>";
            break;
        case 5:
            $salida = "<center class=\"leidos\">" . $observaciones . "</center>";
            break;
        case 6:
            if ($verImg)
                $salida = "<a class=\"vinculos\" href=\"{$ruta_raiz}verradicado.php?verrad=" . $radicado . "&amp;" . session_name() . "=" . session_id() . "&amp;krd=" . $_GET['krd'] . "&amp;carpeta=8&amp;nomcarpeta=Busquedas&amp;tipo_carp=0 \" >" . $fecharadicado . "</a>";
            else
                $salida = "<a class=\"vinculos\" href=\"#\" onclick=\"alert(\"ud no tiene permisos para ver el radicado\");\">" . $fecharadicado . "</a>";
            break;
        case 7:
            $salida = "<center class=\"leidos\">" . $fechaDig. "</center>";
            break;
        case 8:
            $salida = "<center class=\"leidos\">" . $medioReceo . "</center>";
            break;
    }
    return $salida;
}

?>
