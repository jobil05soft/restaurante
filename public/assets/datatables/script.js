
$(document).ready(function () {
    $('#tabela').DataTable({

        info: true,
        ordering: true,
        paging: true,

        language:
        {
            "decimal": "",
            "emptyTable": "Não foram encontrados registos correspondentes",
            "info": "Mostrando _START_ a _END_ de entradas _TOTAL_",
            "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "infoFiltered": "",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Apresentar _MENU_",
            "loadingRecords": "Loading...",
            "processing": "",
            "search": "Pesquisar:",
            "zeroRecords": "Não foram encontrados registos correspondentes",
            "aria": {
                "orderable": "Ordenar por esta coluna",
                "orderableReverse": "Ordem inversa desta coluna"
            }
        },
    });
});

$(document).ready(function () {
    $('#reserva').DataTable({

        info: true,
        ordering: true,
        paging: true,

        language:
        {
            "decimal": "",
            "emptyTable": "Não foram encontrados registos correspondentes",
            "info": "Mostrando _START_ a _END_ de entradas _TOTAL_",
            "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "infoFiltered": "",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Apresentar _MENU_",
            "loadingRecords": "Loading...",
            "processing": "",
            "search": "Pesquisar:",
            "zeroRecords": "Não foram encontrados registos correspondentes",
            "aria": {
                "orderable": "Ordenar por esta coluna",
                "orderableReverse": "Ordem inversa desta coluna"
            }
        },

        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: 'Exportar Excel',
                className: 'btn btn-success',
                extend: 'excel',
                title: 'Relatório de Reservas - RESTAURANTE - CERVEJARIA KISSANGA',
                messageTop: 'Este relatório contém as reservas registradas no sistema.',
                messageBottom: 'Relatório gerado em: ' + new Date().toLocaleDateString(),
            },
            {
                extend: 'pdf',
                text: 'Exportar PDF',
                className: 'btn btn-danger',
                extend: 'pdfHtml5',
                title: 'Relatório de Reservas - RESTAURANTE - CERVEJARIA KISSANGA',
                messageTop: 'Este relatório contém as reservas registradas no sistema.',
                messageBottom: 'Relatório gerado em: ' + new Date().toLocaleDateString(),
                orientation: 'landscape', // ou 'portrait'
                pageSize: 'A4',
                customize: function (doc) {
                    // Centralizar título
                    doc.styles.title = {
                        alignment: 'center',
                        fontSize: 16,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    };
                    // Ajustar tamanho da fonte da tabela
                    doc.styles.tableHeader.fontSize = 11;
                    doc.defaultStyle.fontSize = 10;
                }
            },
            {
                extend: 'print',
                text: 'Imprimir',
                className: 'btn btn-primary',
                extend: 'print',
                title: 'Relatório de Reservas - RESTAURANTE - CERVEJARIA KISSANGA',
                messageTop: 'Este relatório contém as reservas registradas no sistema.',
                messageBottom: 'Relatório gerado em: ' + new Date().toLocaleDateString(),
                orientation: 'landscape', // ou 'portrait'
                pageSize: 'A4',
                customize: function (doc) {
                    // Centralizar título
                    doc.styles.title = {
                        alignment: 'center',
                        fontSize: 16,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    };
                    // Ajustar tamanho da fonte da tabela
                    doc.styles.tableHeader.fontSize = 11;
                    doc.defaultStyle.fontSize = 10;
                }
            }
        ],


    });


});

$(document).ready(function () {
    $('#pedido').DataTable({

        info: true,
        ordering: true,
        paging: true,

        language:
        {
            "decimal": "",
            "emptyTable": "Não foram encontrados registos correspondentes",
            "info": "Mostrando _START_ a _END_ de entradas _TOTAL_",
            "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "infoFiltered": "",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Apresentar _MENU_",
            "loadingRecords": "Loading...",
            "processing": "",
            "search": "Pesquisar:",
            "zeroRecords": "Não foram encontrados registos correspondentes",
            "aria": {
                "orderable": "Ordenar por esta coluna",
                "orderableReverse": "Ordem inversa desta coluna"
            }
        },

        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: 'Exportar Excel',
                className: 'btn btn-success',
                extend: 'excel',
                title: 'Relatório de Pedidos - RESTAURANTE - CERVEJARIA KISSANGA',
                messageTop: 'Este relatório contém os Pedidos registradas no sistema.',
                messageBottom: 'Relatório gerado em: ' + new Date().toLocaleDateString(),
            },
            {
                extend: 'pdf',
                text: 'Exportar PDF',
                className: 'btn btn-danger',
                extend: 'pdfHtml5',
                title: 'Relatório de Pedidos - RESTAURANTE - CERVEJARIA KISSANGA',
                messageTop: 'Este relatório contém os Pedidos registradas no sistema.',
                messageBottom: 'Relatório gerado em: ' + new Date().toLocaleDateString(),
                orientation: 'landscape', // ou 'portrait'
                pageSize: 'A4',
                customize: function (doc) {
                    // Centralizar título
                    doc.styles.title = {
                        alignment: 'center',
                        fontSize: 16,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    };
                    // Ajustar tamanho da fonte da tabela
                    doc.styles.tableHeader.fontSize = 11;
                    doc.defaultStyle.fontSize = 10;
                }
            },
            {
                extend: 'print',
                text: 'Imprimir',
                className: 'btn btn-primary',
                extend: 'print',
                title: 'Relatório de Pedidos - RESTAURANTE - CERVEJARIA KISSANGA',
                messageTop: 'Este relatório contém os Pedidos registradas no sistema.',
                messageBottom: 'Relatório gerado em: ' + new Date().toLocaleDateString(),
                orientation: 'landscape', // ou 'portrait'
                pageSize: 'A4',
                customize: function (doc) {
                    // Centralizar título
                    doc.styles.title = {
                        alignment: 'center',
                        fontSize: 16,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    };
                    // Ajustar tamanho da fonte da tabela
                    doc.styles.tableHeader.fontSize = 11;
                    doc.defaultStyle.fontSize = 10;
                }
            }
        ],


    });


});

$(document).ready(function () {
    $('#mesa').DataTable({

        info: true,
        ordering: true,
        paging: true,

        language:
        {
            "decimal": "",
            "emptyTable": "Não foram encontrados registos correspondentes",
            "info": "Mostrando _START_ a _END_ de entradas _TOTAL_",
            "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "infoFiltered": "",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Apresentar _MENU_",
            "loadingRecords": "Loading...",
            "processing": "",
            "search": "Pesquisar:",
            "zeroRecords": "Não foram encontrados registos correspondentes",
            "aria": {
                "orderable": "Ordenar por esta coluna",
                "orderableReverse": "Ordem inversa desta coluna"
            }
        },



    });


});

$(document).ready(function () {
    $('#cliente').DataTable({

        info: true,
        ordering: true,
        paging: true,

        language:
        {
            "decimal": "",
            "emptyTable": "Não foram encontrados registos correspondentes",
            "info": "Mostrando _START_ a _END_ de entradas _TOTAL_",
            "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "infoFiltered": "",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Apresentar _MENU_",
            "loadingRecords": "Loading...",
            "processing": "",
            "search": "Pesquisar:",
            "zeroRecords": "Não foram encontrados registos correspondentes",
            "aria": {
                "orderable": "Ordenar por esta coluna",
                "orderableReverse": "Ordem inversa desta coluna"
            }
        },

    });


});

