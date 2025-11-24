<style>
    .main-footer {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 20px 10px;
        margin-top: auto; 
        width: 100%;
        border-top: 4px solid #4cae4c;
    }

    .main-footer p {
        margin: 5px 0;
        font-size: 0.9rem;
    }

    .developer-info {
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px solid #555;
    }

    .social-links {
        margin-top: 10px;
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .social-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #fff;
        font-weight: bold;
        padding: 8px 16px;
        border-radius: 20px;
        background-color: rgba(255,255,255,0.1);
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .social-btn:hover {
        background-color: #fff;
        color: #333;
        transform: translateY(-2px);
    }

    .social-btn img {
        width: 20px;
        height: 20px;
        filter: brightness(0) invert(1); 
    }
    
    .social-btn:hover img {
        filter: none;
    }

</style>

<footer class="main-footer">
    <p>&copy; <?php echo date("Y"); ?> Match de Receitas. Todos os direitos reservados.</p>
    <p> <?php ?> Projeto para fins de aprendizado.</p>

    <div class="developer-info">
        <p>Desenvolvido por <strong>José Matheus Nagata Kulibaba</strong></p>
        
        <div class="social-links">
            <a href="https://github.com/JoseMatheusNagata/MatchDeReceitas" target="_blank" class="social-btn">
                <img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" alt="GitHub">
                GitHub
            </a>
            
            <a href="https://www.linkedin.com/in/jos%C3%A9-matheus-nagata-kulibaba-0b5216237/" target="_blank" class="social-btn">
                <img src="https://cdn-icons-png.flaticon.com/512/174/174857.png" alt="LinkedIn">
                LinkedIn
            </a>
        </div>
    </div>
</footer>