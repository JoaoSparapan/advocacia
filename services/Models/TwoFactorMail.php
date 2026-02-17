<?php

class TwoFactorMail {
    private $code;
    private $name;
    private $title;

    function __construct($code = '', $name = '', $title = 'Código de Verificação') {
        $this->code = $code;
        $this->name = $name;
        $this->title = $title;
    }

    public function content() {
        return '
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . $this->title . '</title>
            <style>
                body {
                    background-color: #f4f4f4;
                    font-family: Arial, sans-serif;
                    text-align: center;
                    padding: 40px;
                }
                .container {
                    background-color: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                    display: inline-block;
                }
                .code {
                    font-size: 32px;
                    color: #3e64ff;
                    margin: 20px 0;
                    letter-spacing: 4px;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Olá, ' . htmlspecialchars($this->name) . '!</h2>
                <p>Use o código abaixo para concluir seu login:</p>
                <div class="code">' . $this->code . '</div>
                <p>Este código expira em 10 minutos.</p>
            </div>
        </body>
        </html>';
    }
}
?>
