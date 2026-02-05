function registro(){
    event.preventDefault(); // Evita el envío del formulario
    
    let nombre = document.querySelector("input[name='txtNombre']").value.trim();
    let apellido = document.querySelector("input[name='txtApellido']").value.trim();
    let correo = document.querySelector("input[name='txtCorreo']").value.trim();
    let contra = document.querySelector("input[name='txtContra']").value.trim();
    
    if (!nombre || !apellido || !correo || !contra) {
        alert("Todos los campos son obligatorios.");
        return;
    }
}