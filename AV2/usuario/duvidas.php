<?php
session_start();
include 'header.php';
?>

<div style="max-width: 1000px; margin: 40px auto; padding: 20px; text-align: center;">
    
    <div style="text-align: left;">
        <a href="index.php" class="btn-back"><i class="fa fa-arrow-left"></i></a>
    </div>

    <h2 style="font-size: 32px; margin-bottom: 10px;">Perguntas frequentes</h2>
    <p style="margin-bottom: 30px; color: #666;">Como podemos ajudar?</p>

    <div style="margin-bottom: 40px;">
        <input type="text" placeholder="Procure por palavras chave" style="padding: 10px; width: 300px; border-radius: 20px; border: 1px solid #ccc; text-align: center;">
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 15px; justify-content: center;">
        
        <?php
        $topicos = [
            "Pré-requisitos e formas de pagamento",
            "Dados cadastrais e dados de acesso",
            "Prorrogação",
            "Nome social",
            "Tag de pagamento",
            "Revisão e cuidados com o carro",
            "Diárias e horas extras",
            "Multa de trânsito",
            "Substituição de carro",
            "Condutor adicional",
            "Devolução do carro",
            "Agências",
            "Seguros e proteções",
            "Reserva",
            "Lavagem e limpeza garantida"
        ];

        foreach($topicos as $topico): ?>
            <div style="background: #d3d3d3; padding: 15px; border-radius: 10px; width: 300px; cursor: pointer; font-weight: 500;">
                <?php echo $topico; ?>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<?php include 'footer.php'; ?>
