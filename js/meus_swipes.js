document.addEventListener('DOMContentLoaded', function() {
    const receitasContainer = document.getElementById('receitas-container');
    const receitas = receitasContainer.getElementsByClassName('receita-card');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const counter = document.getElementById('receita-counter');

    let receitaAtual = 0;

    function mostrarReceita(index) {
        // Esconde todas as receitas
        for (let i = 0; i < receitas.length; i++) {
            receitas[i].style.display = 'none';
        }
        // Mostra apenas a receita atual
        if (receitas[index]) {
            receitas[index].style.display = 'block';
        }

        // Atualiza o contador
        counter.textContent = `Receita ${index + 1} de ${receitas.length}`;

        // Habilita/desabilita botões de navegação
        prevBtn.disabled = index === 0;
        nextBtn.disabled = index === receitas.length - 1;
    }

    if (receitas.length > 0) {
        prevBtn.addEventListener('click', () => {
            if (receitaAtual > 0) {
                receitaAtual--;
                mostrarReceita(receitaAtual);
            }
        });

        nextBtn.addEventListener('click', () => {
            if (receitaAtual < receitas.length - 1) {
                receitaAtual++;
                mostrarReceita(receitaAtual);
            }
        });

        // Mostra a primeira receita ao carregar a página
        mostrarReceita(0);
    } else {
        // Se não houver receitas, esconde os controles
        const navControls = document.querySelector('.navigation-controls');
        if(navControls) {
            navControls.style.display = 'none';
        }
    }
});