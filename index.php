<?php include_once 'inc/head.php'; ?>
<?php require_once 'inc/db.php'; ?>

<?php
    $stmtLojas = $pdo->query("SELECT nome, slug, logoLoja FROM lojas ORDER BY nome ASC");
    $lojas = $stmtLojas->fetchAll(PDO::FETCH_ASSOC);

    $stmtLojasDestaque = $pdo->query("SELECT nome, slug FROM lojas ORDER BY RAND() LIMIT 20");
    $lojasDestaque = $stmtLojasDestaque->fetchAll(PDO::FETCH_ASSOC);

    $stmtCupons = $pdo->query("
        SELECT cupons.id, cupons.titulo, cupons.descricao, cupons.codigoCupom, cupons.urlCupom, cupons.logoLoja, 
            lojas.nome AS loja, lojas.slug AS slugLoja
        FROM cupons
        INNER JOIN lojas ON cupons.urlLoja = lojas.slug
        ORDER BY cupons.created DESC
        LIMIT 20
    ");
    $cupons = $stmtCupons->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <header>
        <?php include_once 'inc/navbar.php'; ?>
    </header>

    <section class="c1">
        <div class="container text-center">
            <h1>Cupons de Desconto, Produtos e Cashback</h1>
            <p>Economize na sua compra usando os melhores Cupons de Desconto e Cashback!</p>

            <div class="cards">
                <?php foreach ($lojas as $loja): ?>
                    <a href="<?php echo $base_url; ?>/desconto/<?php echo htmlspecialchars($loja['slug']); ?>" class="loja-card">
                        <img src="<?php echo $base_url; ?>/<?php echo !empty($loja['logoLoja']) ? htmlspecialchars($loja['logoLoja']) : 'assets/images/uploads/lojas/default.svg'; ?>" alt="<?php echo htmlspecialchars($loja['nome']); ?>">
                        <span><?php echo htmlspecialchars($loja['nome']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="c2">
        <div class="container">
            <h2>Os melhores Cupons de Desconto</h2>
            <div class="block">
                <div>
                <?php foreach ($cupons as $cupom): ?>
                    <div class="coupon shadowCustom">
                        <div class="logo">
                            <img src="<?php echo $base_url; ?>/<?php echo !empty($cupom['logoLoja']) ? htmlspecialchars($cupom['logoLoja']) : 'assets/images/uploads/lojas/default.svg'; ?>" alt="<?php echo htmlspecialchars($cupom['loja']); ?>">
                        </div>
                        <div class="description">
                            <h3><?php echo htmlspecialchars($cupom['titulo']); ?></h3>
                            <p><?php echo htmlspecialchars($cupom['descricao']); ?></p>
                            <small>
                                <img src="<?php echo $base_url; ?>/assets/images/icon/icCheck.svg" alt="Icone"> Verificado &nbsp;
                                <img src="<?php echo $base_url; ?>/assets/images/icon/icUp.svg" alt="Icone">
                                <?php 
                                    $cuponsUsados = rand(5, 50);
                                    echo $cuponsUsados . " cupons usados hoje";
                                ?>
                            </small>
                        </div>
                        <div class="share">
                            <button 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalDiscount"
                                data-titulo="<?php echo htmlspecialchars($cupom['titulo']); ?>"
                                data-descricao="<?php echo htmlspecialchars($cupom['descricao']); ?>"
                                data-codigo="<?php echo htmlspecialchars($cupom['codigoCupom']); ?>"
                                data-url="<?php echo htmlspecialchars($cupom['urlCupom']); ?>"
                                data-logo="<?php echo !empty($cupom['logoLoja']) ? htmlspecialchars($cupom['logoLoja']) : 'assets/images/uploads/lojas/default.svg'; ?>"
                                onclick="abrirModal(this)">
                                Ver Cupom
                            </button>
                            <div class="mask">CUPOM20</div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>

                <div class="colun2">
                    <span><img src="<?php echo $base_url; ?>/assets/images/icon/icUse.svg" alt="Icone"> Como usar os cupons de descontos nas lojas</span>
                    <span><img src="<?php echo $base_url; ?>/assets/images/icon/icAll.svg" alt="Icone"> Veja todas ofertas e cupons de desconto</span>
                    <nav>
                        <h4>Cupons de lojas em destaque</h4>
                        <ul>
                            <?php foreach ($lojasDestaque as $loja): ?>
                                <li>
                                    <a href="<?php echo $base_url; ?>/desconto/<?php echo htmlspecialchars($loja['slug']); ?>">
                                        <?php echo htmlspecialchars($loja['nome']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    
    <div class="work">
        <div class="container">
            <div class="block">
                <div>
                    <span>Código de Desconto</span>
                    <p>Para aproveitar seu desconto, basta inserir o código <b>OLHAAMIGA20OFF</b> antes de finalizar a compra. Esse 
                    campo geralmente aparece na etapa do Carrinho ou Pagamento, identificado como <b>“Cupom de Desconto”</b> ou <b>“Código Promocional”</b>. 
                    Algumas lojas exigem que você esteja logado para visualizar essa opção. Assim que o código for aplicado, o valor final será 
                    ajustado automaticamente. Vale lembrar que nem sempre o desconto aparece destacado, então confira o total antes de concluir 
                    a compra.</p>
                </div>

                <div>
                    <span>Link de Desconto</span>
                    <p>Em alguns casos, não é necessário inserir um código – o desconto é aplicado automaticamente nos 
                    produtos do site. Isso significa que os preços já aparecem reduzidos, sem necessidade de ativação manual.
                    As lojas podem exibir essa oferta de duas formas:
                    <b>(A)</b> Algumas mostram o valor com desconto diretamente na listagem de produtos, seja com a indicação “de/por” ou apenas com o preço ajustado.
                    <b>(B)</b> Outras aplicam o desconto apenas no Carrinho ou na tela de Pagamento, onde a redução no valor fica mais evidente.

                    Fique de olho nos detalhes para garantir que o desconto foi realmente aplicado antes de finalizar a compra!</p>
                </div>

                <div>
                    <span>Ofertas</span>
                    <p>No Olha Amiga, também reunimos as melhores promoções que as lojas disponibilizam diariamente, 
                    como **Subday do Submarino, Blackout do Walmart e Extra Confidencial**, entre outras.
                    Nesses casos, os preços já estão com grandes descontos, então nem sempre será possível aplicar um 
                    cupom adicional para reduzir ainda mais o valor. Mas não se preocupe! Sempre buscamos as melhores 
                    ofertas para você economizar ao máximo.</p>
                </div>
            </div>

            <p><b>❗ Observação:</b> Confira atentamente as restrições na descrição de cada cupom. Normalmente a % de desconto varia por categoria e nem sempre é válido para o site todo (ex: lançamentos e algumas marcas podem ser excluídos, o desconto pode requerer um valor de compra mínima, ou só ser aplicado em itens vendidos e entregues pela própria loja, etc.)</p>
        </div>
    </div>
    
    <footer>
        <?php include_once 'inc/footer.php'; ?>
    </footer>

    <!-- MODAL DISCOUNT -->
    <div class="modal fade" id="modalDiscount" tabindex="-1" aria-labelledby="modalDiscountLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <img src="<?php echo $base_url; ?>/assets/images/icon/icClose.svg" alt="Icone">
                    </button>

                    <div class="logo">
                        <img id="modalLogo">
                    </div>
                    <h5 id="modalTitulo"></h5>
                    <p id="modalDescricao"></p>

                    <span>Copie e cole o código no carrinho de compras</span>
                    <div class="code">
                        <span id="modalCodigo"></span>
                        <a href="<?php echo htmlspecialchars($cupom['urlCupom']);?>" target="_blank"><img src="<?php echo $base_url; ?>/assets/images/icon/icCopy.svg" alt="Icone"></a>
                    </div>

                    <div class="share">
                        <div>
                            <small>Aproveite a melhor experiência com nossos cupons, compartilhe com suas amigas!</small>
                        </div>

                        <div>
                            <img src="<?php echo $base_url; ?>/assets/images/icon/icWhatsapp.svg" alt="Rede Social">
                            <img src="<?php echo $base_url; ?>/assets/images/icon/icFacebook.svg" alt="Rede Social">
                            <img src="<?php echo $base_url; ?>/assets/images/icon/icInstagram.svg" alt="Rede Social">
                            <img src="<?php echo $base_url; ?>/assets/images/icon/icTwitter.svg" alt="Rede Social">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once 'inc/bottom.php'; ?>
</body>
</html>