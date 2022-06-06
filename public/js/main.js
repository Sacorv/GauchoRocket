
   var contenedorDeHorarios=document.getElementById("contenedor-horarios");

 var error=false;
function mostrarHorarios(){
 
    if(contenedorDeHorarios.style.display=="none"){
        contenedorDeHorarios.style.display="flex";
    }else{
 contenedorDeHorarios.style.display="flex";

}
    
}
document.getElementById("searchButton").addEventListener("click",mostrarHorarios);
