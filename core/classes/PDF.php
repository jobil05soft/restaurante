<?php

namespace core\classes;

use Mpdf\Mpdf;

class PDF
{

    private $pdf;
    private $html;

    private $x; // left
    private $y; // top
    private $width; //largura
    private $heigth; // altura
    private $align;

    private $cor; // for color
    private $background; // background

    private $font_family; // font-family
    private $size; // font-size
    private $font_weight; // font-weight

    private $mostrar_areas; //border

    private $imagem;

    //====================================================================================
    public function __construct($mostrar_areas = false, $formato = 'A4', $orientacao = 'P', $modo = 'utf-8')
    {
        $this->pdf = new Mpdf([
            'format' => $formato,
            'orientation' => $orientacao,
            'modo' => $modo
        ]);

        // iniciar o html
        $this->inicar_html();

        $this->mostrar_areas = $mostrar_areas;
    }

    //====================================================================================
    public function set_template($template)
    {
        $this->pdf->SetDocTemplate($template);
    }
    //====================================================================================
    public function inicar_html()
    {
        //zerar html
        $this->html = '';
    }

    //====================================================================================
    public function apresentar_pdf()
    {
        //output para o browser
        $this->pdf->WriteHTML($this->html);
        $this->pdf->Output('');
    }

    //====================================================================================
    public function download_pdf($nome_ficheiro)
    {
        //output para o browser
        $this->pdf->WriteHTML($this->html);
        $this->pdf->Output($nome_ficheiro, 'D');
    }
    //====================================================================================
    public function salvar_pdf($nome_ficheiro)
    {
        // guardar o ficheiro pdf com o nome especifico
        $this->pdf->WriteHTML($this->html);
        $this->pdf->Output(PDF_PATH . $nome_ficheiro);
    }

    //====================================================================================
    public function nova_pagina()
    {
        $this->html .= '<pagebreak>';
    }

    //====================================================================================

    // Metodos para definir a posição do texto
    public function posicao($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
    //====================================================================================
    public function dimensao($largura, $altura)
    {
        $this->width = $largura;
        $this->heigth = $altura;
    }
    //====================================================================================
    public function posicao_dimensao($x, $y, $largura, $altura)
    {
        $this->posicao($x, $y);
        $this->dimensao($largura, $altura);
    }

    //====================================================================================
    // Cor
    public function set_cor($cor)
    {
        //com de texto
        $this->cor = $cor;
    }

    //====================================================================================
    public function set_cor_funco($cor)
    {
        // cor do fundo
        $this->background = $cor;
    }

    //====================================================================================
    // caracteristica do texto
    public function set_align($align)
    {
        $this->align = $align;
    }

    //====================================================================================
    public function set_font_family($fonte)
    {
        $fontes_possiveis = [
            'Arial',
            'Segoe UI',
            'Times New Roman',
            'Courier New',
        ];

        //verificar se familia existe ao conjunto de letras permitidas
        if (!in_array($fonte, $fontes_possiveis)) {
            $this->font_family = 'Arial';
        } else {
            $this->font_family = $fonte;
        }
    }

    //====================================================================================
    public function set_size($size)
    {
        $this->size = $size;
    }

    //====================================================================================
    public function set_font_weight($weight)
    {

        $this->font_weight = $weight;
    }

    //====================================================================================
    public function set_permissoes($permissoes = [], $password = '')
    {
        $this->pdf->SetProtection($permissoes, $password);
    }

    public function set_imagem($caminho)
    {
        //<img src="">
        $this->html .= '<img width="100%" height="100%" src="' . $caminho . '">';
    }
    //====================================================================================
    public function escrever($texto)
    {
        // escreve texto no documento
        $this->html .= '<div style="';

        // posicionamento e dimensão
        $this->html .= 'position: absolute;';
        $this->html .= 'left: ' . $this->x . 'px;';
        $this->html .= 'top: ' . $this->y . 'px;';

        $this->html .= 'width: ' . $this->width . 'px;';
        $this->html .= 'height: ' . $this->heigth . 'px;';

        $this->html .= 'text-align: ' . $this->align . ';';

        //cor
        $this->html .= 'color: ' . $this->cor . ';';
        $this->html .= 'background-color: ' . $this->background . ';';


        // letra
        $this->html .= 'font-family: ' . $this->font_family . ';';
        $this->html .= 'font-size: ' . $this->size . 'px;';
        $this->html .= 'font-weight: ' . $this->font_weight . ';';

        // mostrar contorno da área
        if ($this->mostrar_areas) {
            $this->html .= 'box-shadow: inset 0px 0px 0px 1px red;';
        }

        $this->html .= '">' . $texto . '</div>';
    }
}
