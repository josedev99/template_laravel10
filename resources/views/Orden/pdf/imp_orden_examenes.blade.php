<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>ORDEN DE EXAMENES</title>
  <style>
    html {
      margin-top: 0;
      margin-left: 28px;
      margin-right: 20px;
      margin-bottom: 0;
    }

    .info_empleado{
      font-size: 12px;
      font-family: Helvetica, Arial, sans-serif;
      text-align: center;
    }
    .container_table{
      border: 1px solid #1881db;border-radius: 4px;
    }

    .stilot1 {
      padding: 5px;
      font-size: 12px;
      font-family: Helvetica, Arial, sans-serif;
      border-collapse: collapse;
      text-align: center;
    }

    .stilot2 {
      text-align: center;
      font-size: 11px;
      font-family: Helvetica, Arial, sans-serif;
    }

    .table2 {
      border-collapse: collapse;
      width: 100%;
    }
  </style>
</head>

<body>

  <div style="margin-top:30px;height:200px">
    <table class="table2">
      <tr>
        <td colspan="40" style="width: 40%;text-align: left">
          @if($empresa['logo'] != "")
            <img src='{{ "storage/".$empresa['logo'] }}' width="100" height="60" />
            @endif
        </td>
        <td colspan="60" style="width: 60%;">
          <div class="container_table" style="padding: 6px">
          <table class="table2">
            <tr>
              <td colspan="20" class="info_empleado" style="20%;padding: 0px 10px 0px 0px"><strong>CÓDIGO:</strong> <br> {{ $data_orden['codigo_empleado'] }}</td>
              <td colspan="30" class="info_empleado" style="30%;border-left: 1px solid #1881db;padding: 0px 10px 0px 0px"><strong>NOMBRE:</strong> <br> {{ $data_orden['colaborador'] }}</td>
              <td colspan="20" class="info_empleado" style="20%;border-left: 1px solid #1881db;padding: 0px 10px 0px 0px"><strong>DEPARTAMENTO:</strong> <br> {{ $data_orden['area_depto'] }}</td>
              <td colspan="30" class="info_empleado" style="30%;border-left: 1px solid #1881db;padding: 0px"><strong>EMPRESA:</strong> <br> {{ strtoupper($data_orden['empresa']) }}</p></td>
              </tr>
          </table>
        </div>
        </td>
      </tr>
    </table>
    <div class="container_table">
      <table width="100%" class="table2">
        <tr>
          <td colspan="100" style="text-align: center; background:#034f84;color:#fff;border-bottom: 1px solid #1881db;border-top-left-radius: 4px;border-top-rigth-radius: 4px" class="stilot1">EXAMENES</td>
        </tr>
        <tr style="height:40px;">
          <td colspan="100" style="border-bottom: 1px solid #1881db;font-family: Helvetica, Arial, sans-serif;font-size: 12px;text-align: center;margin:20px;height: 70px;white-space: wrap;"
            align="center">
            @php
                $id = 0;
            @endphp
            @for($i=0; $i < count($data_orden['examenes']); $i++)
              @php
                $id += 1;
              @endphp
                {!! "<b>". $id."</b>. " . ucfirst($data_orden['examenes'][$i]["examen"]) !!}&nbsp;&nbsp;&nbsp;
              @if($i == 5)
                <br>
              @endif
            @endfor
          </td>
        <tr>
          <td colspan="100"
            style="font-family: Helvetica, Arial, sans-serif;font-size: 12px;text-align: center;margin:20px;white-space: wrap;text-align: justify;padding: 5px"
            align="center">
            @php
              $recomendacion = "<strong>RECOMENDACIONES PARA RECOLECCIÓN DE MUESTRAS: </strong><br><strong>Examenes sanguineos: </strong>
        Cena previa normal. Presentarse con un ayuno estricto de 12 a 14 horas. 
        Puede ingerir agua si lo desea.
        Si toma algun medicamento este debe ser tomado luego de haberse realizado el examen.<br><br>";
            @endphp
  
            @for($i=0; $i < count($data_orden['examenes']); $i++)
              @php
                 $heces = "<strong>Heces</strong>: En el recipiente color verde  boca ancha colocar una pequeña cantidad de muestra.
                Con ayuda de una espatula tomar la muestra y colocar en frasco, esta muestra no tiene que tener contacto ni con el inodoro y con la orina para evitar el deterioro de parasitos.<br>
                ";
                $baciloscopia ="<strong> Baciloscopia (muestra de esputo o flema): </strong>
                En el recipiente transparente boca ancha tome una muestra de esputo, inspirando fuertemente y expulsando con un esfuerzo dentro de tos dentro del recipiente. 
                La muestra debe ser tomada en ayunas y sin cepillarse los dientes.<br><br>
                ";
  
                $exo ="<strong>Exudado faringeo</strong>
                Se le tomara una muestra de la garganta llamada cepillado de garganta.
                No se deben usar enjuagues bucales antisepticos antes del examen.
                Se realiza en ayunas y sin haberse cepillado los dientes.<br><br>";
  
                $orina ="<strong>Orina</strong> 
                Lavar el area genital con jabon y abundante agua. Secar minuciosamente.
                Se recomienda que sea la primer orina del dia.
                Inicie la miccion en el baño y a mitad del chorro coloque en frasco, tapar inmediatamente. No colocar plastico, papel u otro material entre la boca del frasco y la tapadera.<br><br>";
              @endphp
              @if ($data_orden['examenes'][$i]["categoria"] == "HECES")
                @php
                  $recomendacion = $recomendacion . $heces;
                @endphp
              @elseif ($data_orden['examenes'][$i]["examen"] == "BACILOSCOPIA")
                @php
                  $recomendacion = $recomendacion . $baciloscopia;
                @endphp
              @elseif ($data_orden['examenes'][$i]["categoria"] == "ORINA")
                @php
                  $recomendacion = $recomendacion . $orina;
                @endphp
              @elseif ($data_orden['examenes'][$i]["categoria"] == "BACTERIOLOGIA")
                @php
                  $recomendacion = $recomendacion . $exo;
                @endphp
              @endif
            @endfor
            
       {!! $recomendacion !!}
       {!! "<b>Espirometria:</b>No fumar dos horas antes de la prueba. Indicarle al medico si tiene gripe.<br> <b>Audiometría.</b>" !!}
  
          </td>
        </tr>
      </table>
    </div>
  </div>
</body>

</html>