// app.js

// ========================================================
function adicionar_carrinho(id_item) {

    // adicionar produto ao carrinho
    axios.defaults.withCredentials = true; // permissão do AXIOS
    axios.get('?a=adicionar_carrinho&id_item=' + id_item)
        .then(function (response) {

            var total_item = response.data;
            document.getElementById('carrinho').innerText = total_item;
        });
}

// ========================================================
function limpar_carrinho() {
    var e = document.getElementById("confirmar_limpar_carrinho");
    e.style.display = "inline";
}

// ========================================================
function limpar_carrinho_off() {
    var e = document.getElementById("confirmar_limpar_carrinho");
    e.style.display = "none";
}

// ========================================================
function pedido_local() {

    // mostrar ou esconder o espaço para a morada alternativa.
    var e = document.getElementById('check_pedido_local');
    document.getElementById("pedido_fora").style.display = 'none';


    if (e.checked == true) {

        // mostra o quadro para definir morada alternativa
        document.getElementById("pedido_local").style.display = 'block';
        // buttons

    } else {

        // esconde o quadro para definir morada alternativa
        document.getElementById("pedido_local").style.display = 'none';
    }
}


// ========================================================
function pedido_fora() {

    // mostrar ou esconder o espaço para a morada alternativa.
    var e = document.getElementById('check_pedido_fora');
    document.getElementById("pedido_local").style.display = 'none';
    document.getElementById("buttons").style.display = 'none';


    if (e.checked == true) {

        // mostra o quadro para definir morada alternativa
        document.getElementById("pedido_fora").style.display = 'block';


    } else {

        // esconde o quadro para definir morada alternativa
        document.getElementById("pedido_fora").style.display = 'none';

    }
}


// ========================================================
function usar_morada_alternativa() {

    // mostrar ou esconder o espaço para a morada alternativa.
    var e = document.getElementById('check_morada_alternativa');
    if (e.checked == true) {

        // mostra o quadro para definir morada alternativa
        document.getElementById("morada_alternativa").style.display = 'block';

    } else {

        // esconde o quadro para definir morada alternativa
        document.getElementById("morada_alternativa").style.display = 'none';
    }
}

// ========================================================
function morada_alternativa() {


 
    axios({
        method: 'post',
        url: '?a=morada_alternativa',
        data: {
            text_morada: document.getElementById('text_morada_alternativa').value,
            text_email: document.getElementById('text_email_alternativo').value,
            text_telefone: document.getElementById('text_telefone_alternativo').value,
            text_mesa: document.getElementById('text_mesa').value
        }
    });
}