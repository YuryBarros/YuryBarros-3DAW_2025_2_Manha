<?php
session_start();
include 'header.php';
?>

<div style="max-width: 800px; margin: 40px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    
    <h2 style="color: var(--primary-green); text-align: center; margin-bottom: 20px;">Fale Conosco</h2>
    
    <?php if(isset($_GET['enviado'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;">
            Mensagem enviada com sucesso! Entraremos em contato em breve.
        </div>
    <?php endif; ?>

    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            <h4 style="margin-bottom: 15px;">Nossos Contatos</h4>
            <p style="margin-bottom: 10px;"><i class="fa fa-phone"></i> (21) 4002-8922</p>
            <p style="margin-bottom: 10px;"><i class="fab fa-whatsapp"></i> (21) 99999-9999</p>
            <p style="margin-bottom: 10px;"><i class="fa fa-envelope"></i> contato@fallscar.com</p>
            <p style="margin-bottom: 10px;"><i class="fa fa-map-marker-alt"></i> Rua do Saci Pererê, 666 - Bahia</p>
            
            <hr style="margin: 20px 0; border-color: #eee;">
            
            <h4 style="margin-bottom: 15px;">Horário de Atendimento</h4>
            <p>Segunda a Sexta: 08h às 18h</p>
            <p>Sábado: 08h às 12h</p>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <form action="contato.php?enviado=1" method="POST">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #666;">Nome</label>
                    <input type="text" name="nome" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #666;">E-mail</label>
                    <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #666;">Mensagem</label>
                    <textarea name="mensagem" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; resize: vertical;"></textarea>
                </div>

                <button type="submit" style="width: 100%; padding: 12px; background: var(--primary-green); color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
                    ENVIAR MENSAGEM
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
