<html>
    <head>
    
    </head>
    <body>
    <h3><center>MOBILE FORENSIC INVESTIGATION TOOL</center></h3>
    <h1><center><u>CRIME SCENE REPORT</u></center></h1>

       <table Border = 3 Width = 100% CellPadding = 3 CellSpacing = 3>
         <tr>
             <td colspan="3"><b>Investigator Name: </b>  ( {{$x->invID}} )  {{$x->name}} </td>
         </tr>
         <tr>
             <td><b>Case No: </b>{{$x->caseNo}}</td>
             <td><b>Date: </b>{{$x->date}}</td>
             <td><b>Time: </b>{{$x->time}}</td>
         </tr>
         <tr>
             <td colspan="3" ><div style="font-weight:bold">Case Name: </div><div>{{$x->caseName}}</div></td>
        </tr>
         <tr>
             <td rowspan="2" colspan="2"><div style="font-weight:bold">Location: </div>
             <div>Latitude = {{$x->latitude}}</div>
             <div>Longitude = {{$x->longitude}}</div>
             <div>Address = {{$x->address}}</div></td>
             <td><b>Scene: </b> {{$x->scene}}</td>
         </tr>
         <tr>
             <td><b>Weather: </b> {{$x->weather}}</td>
         </tr>
         <tr>
             <td colspan="3"><div style="font-weight:bold">Victim/s: </div><div>{{$x->victim}}</div></td>
         </tr>
         <tr>
             <td colspan="3"><div style="font-weight:bold">Person Involve: </div>
             <div>- {{$x->involveA}}</div>
             <div>- {{$x->involveB}}</div>
             <div>- {{$x->involveC}}</div>
             <div>- {{$x->involveD}}</div>
             </td>
         </tr>
         <tr>
             <td colspan="3"><div style="font-weight:bold">Case Details: </div><div>{{$x->caseDetail}}</div></td>
         </tr>
         <tr>
             <td colspan="3"><div style="font-weight:bold">Evidence: </div>
             <!-- https://github.com/barryvdh/laravel-dompdf/issues/303 -->
             <img src="/evidence/test0.jpg" width="100%" height="100%"/>
             <!-- <img src="{{ url('/evidence/test0.jpg') }}" width='100%' height='100%'> -->
             </td>
         </tr>
       </table>
    </body>
</html>