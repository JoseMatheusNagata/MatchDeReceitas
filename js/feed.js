document.addEventListener('DOMContentLoaded', function() {
    const receitasContainer = document.getElementById('receitas-container');
    
    if (!receitasContainer) return; 

    const receitas = receitasContainer.getElementsByClassName('receita-card');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const counter = document.getElementById('receita-counter');

    let receitaAtual = 0;

    function mostrarReceita(index) {
        for (let i = 0; i < receitas.length; i++) {
            receitas[i].style.display = 'none';
        }
        if (receitas[index]) {
            receitas[index].style.display = 'block';
        }

        if (counter) {
            counter.textContent = `Receita ${index + 1} de ${receitas.length}`;
        }

        if (prevBtn) prevBtn.disabled = index === 0;
        if (nextBtn) nextBtn.disabled = index === receitas.length - 1;
    }

    if (receitas.length > 0) {
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                if (receitaAtual > 0) {
                    receitaAtual--;
                    mostrarReceita(receitaAtual);
                }
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                if (receitaAtual < receitas.length - 1) {
                    receitaAtual++;
                    mostrarReceita(receitaAtual);
                }
            });
        }

        mostrarReceita(0);
    } else {
        const navControls = document.querySelector('.navigation-controls');
        if(navControls) {
            navControls.style.display = 'none';
        }
    }
});