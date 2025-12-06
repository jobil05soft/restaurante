/* globals Chart:false */

(() => {
  'use strict'

  // Graphs
  const ctx = document.getElementById('myChart')
  // eslint-disable-next-line no-unused-vars
  const myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
      ],
      datasets: [{
        data: [
          15,
          21345,
          18483,
          24003,
          23489,
          24092,
          12034
        ],
        lineTension: 0,
        backgroundColor: 'transparent',
        borderColor: '#007bff',
        borderWidth: 4,
        pointBackgroundColor: '#007bff'
      }]
    },
    options: {
      plugins: {
        legend: {
          display: true
        },
        tooltip: {
          boxPadding: 3
        }
      }
    }
  })
})()

const adicionar_novo_item = document.getElementById("adicionar_novo_item");
if (adicionar_novo_item) {
  adicionar_novo_item.addEventListener("submit", async (e) => {
    e.preventDefault();

    const dadosForm = new FormData(adicionar_novo_item);

    console.log(adicionar_novo_item);
    const dados = await fetch("?a=adicionar_novo_item", {
      method: "POST",
      body: dadosForm
    });


    const resposta = await dados.json();

    // verifica se os campos estão preenchidos
    if (resposta['status']) {
      swal.fire({
        text: resposta['msg'],
        icon: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '?a=cardapio';
        }
      });

    } else {
      swal.fire({
        text: resposta['msg'],
        icon: 'warning',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Fechar'
      });
    }
  });
}
