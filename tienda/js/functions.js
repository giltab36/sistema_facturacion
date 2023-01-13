$(document).ready(function () {

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change", function () {
        var uploadFoto = document.getElementById("foto").value;
        var foto = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');

        if (uploadFoto != '') {
            var type = foto[0].type;
            var name = foto[0].name;
            if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
                contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                $("#img").remove();
                $(".delPhoto").addClass('notBlock');
                $('#foto').val('');
                return false;
            } else {
                contactAlert.innerHTML = '';
                $("#img").remove();
                $(".delPhoto").removeClass('notBlock');
                var objeto_url = nav.createObjectURL(this.files[0]);
                $(".prevPhoto").append("<img id='img' src=" + objeto_url + ">");
                $(".upimg label").remove();

            }
        } else {
            alert("No selecciono foto");
            $("#img").remove();
        }
    });

    $('.delPhoto').click(function () {
        $('#foto').val('');
        $(".delPhoto").addClass('notBlock');
        $("#img").remove();

        if ($("#foto_actual") && $("#foto_remove")) {
            $("#foto_remove").val('img_producto.png');
        }
    });

    //Modal Form Add Product
    $('.add_product').click(function (event) {
        event.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: { action: action, producto: producto },

            success: function (response) {
                if (response != 'error') {

                    var info = JSON.parse(response);
                    //$('#producto_id').val(info.cod_producto);
                    //$('.nameProducto').html(info.descripcion);

                    $('.bodyModal').hmtl('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">' +
                        '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i> <br> Agregar Producto</h1>' +
                        '<h2 class="nameProducto">' + info.descripcion + '</h2><br>' +
                        '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del producto" required><br>' +
                        '<input type="text" name="precio" id="txtPrecio" placeholder="Precio del producto" required>' +
                        '<input type="" name="producto_id" id="producto_id" value="' + info.cod_producto + '" required readonly>' +
                        '<input type="" name="action" value="addPorduct" readonly>' +
                        '<div class="alert alertAddProduct"></div>' +
                        '<button type="submit" class="btn_ok"><i class="fas fa-plus"></i> Agregar</button>' +
                        '<a href="#" class="btn_cancel closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>' +
                        '</form>');
                }
            },

            error: function (error) {
                console.log(error);
            }

        });

        $('.modal').fadeIn();
    });

    //Modal Form Delete Product
    $('.del_product').click(function (event) {
        event.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: { action: action, producto: producto },

            success: function (response) {
                if (response != 'error') {

                    var info = JSON.parse(response);

                    $('.bodyModal').hmtl('<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">' +
                        '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i> <br> Eliminar Producto</h1>' +
                        '<p><b>¿Seguro que desea eliminar estos datos?</b></p>' +
                        '<h2 class="nameProducto">' + info.producto_id + '</h2><br>' +
                        '<input type="" name="producto_id" id="producto_id" value="' + info.cod_producto + '" required readonly>' +
                        '<input type="" name="action" value="delPorduct" readonly>' +
                        '<div class="alert alertAddProduct"></div>' +
                        '<a href="#" class="btn_cancel" onclick="closeModal();>Cancelar</a>' +
                        '<button type="submit" class="btn_ok"><i class="fa-solid fa-check"></i> Eliminar</button>' +
                        '</form>');
                }
            },

            error: function (error) {
                console.log(error);
            }

        });

        $('.modal').fadeIn();
    });


});

//Agregar Producto
function sendDataProduct() {

    $('.alertAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#from_add_product').serialize(),

        success: function (response) {
            if (response == 'error') {
                $('.alertAddProduct').html('<p style="color: red;">Error al agregar el producto.</p>');
            } else {
                var info = JSONJ.parse(response);
                $('.row' + info.producto_id + '.celPrecio').hmtl(info.nuevo_precio);
                $('.row' + info.producto_id + '.celExistencia').hmtl(info.nuevo_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');
                $('.alertAddProduct').hmtl('<p>Producto guardado correctamente</p>');
            }
        },

        error: function (error) {
            console.log(error);
        }

    });
}

//Eliminar Producto
function delProduct() {

    $('.alertAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#from_del_product').serialize(),

        success: function (response) {

            if (response == 'error') {
                $('.alertAddProduct').html('<p style="color: red;">Error al eliminar el producto.</p>');
            } else {
                var info = JSONJ.parse(response);
                $('.row' + info.producto_id + '.celPrecio').hmtl(info.nuevo_precio);
                $('.row' + info.producto_id + '.celExistencia').hmtl(info.nuevo_existencia);
                $('#txtCantidad').val('');
                $('#txtPrecio').val('');
                $('.alertAddProduct').hmtl('<p>Producto guardado correctamente</p>');
            }
        },

        error: function (error) {
            console.log(error);
        }

    });
}


function closeModal() {
    $('.alertAddProduct').html('');
    $('#txtCantidad').val('');
    $('#txtPrecio').val('');
    $('.modal').fadeOut();
}