<!-- Button trigger modal -->
<button hidden type="button" class="btn btn-success" id="btn_vendedores" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  Launch
</button>

<style>
  .labels{
    color: #000000;
  }
</style>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title fs-5 labels" id="staticBackdropLabel">Registro de datos</h4>
      </div>
      <div class="modal-body">
        <!-- Formulario para registrar datos -->
        <form id="form_registro_vendedores">
          <div class="mb-3">
            <label for="pais" class="form-label labels">Selecciona tu País</label>
            <select class="form-control" id="pais" onchange="cambiarLada()" name="sPais" required>
              <!--option value="" disabled selected>Selecciona tu país</option-->
              <option value="Mexico" selected>México</option>
              <option value="USA">USA</option>
              <option value="Colombia">Colombia</option>
              <option value="Argentina">Argentina</option>
              <option value="España">España</option>
              <option value="Chile">Chile</option>
              <option value="Peru">Perú</option>
              <option value="Venezuela">Venezuela</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="celular" class="form-label labels">Celular a donde te llegarán por whatsapp las quinielas de tus clientes</label>
            <div class="input-group">
              <span class="input-group-text" id="lada">+52</span>
              <input type="tel" class="form-control" id="celular"  name="txtCelular"  placeholder="Introduce tu número de celular" required oninput="validarNumeros(this)">
            </div>
          </div>

          <div class="mb-3">
            <label for="nombre" class="form-label labels">Ingresa el nombre del dominio para tu quiniela</label>
            <div class="input-group">
              <span class="input-group-text">https://www.qsports.com/</span>
              <input type="text" class="form-control" id="dominio" name="txtDominio" placeholder="Introduce tu dominio" required onkeyup="validarDominio()">
              <p id="mensaje-error" style="color: red; display: none;">El dominio solo puede contener letras, números y guiones.</p>                    
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
        <button form="form_registro" class="btn btn-primary" onclick="registrarVendedores();">Registrar</button>
      </div>
    </div>
  </div>
</div>

