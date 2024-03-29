<?php 
    //require_once __DIR__ . "/inicio-html.php";
    //$this->insert("inicio-html");
    $this->layout('layout');
?>

<ul class="videos__container" alt="videos alura">
    <?php foreach ($videoList as $video) { //inicio foreach
    ?>
        <li class="videos__item">
            <?php if ($video->getFilePath() !== null) {?>
                <a href="<?php echo $video->url; ?>">
                    <img src="/img/uploads/<?php echo $video->getFilePath(); ?>" alt="" style="width: 100%" />
                </a>
            <?php } else {?>
                <iframe width="100%" height="72%" src="<?php echo $video->url; ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php } ?>
                <div class="descricao-video">
                    <img src="./img/logo.png" alt="logo canal alura">
                    <h3><?php echo $video->title; ?></h3>
                    <div class="acoes-video">
                        <a href="/editar-video?id=<?php echo $video->id; ?>">Editar</a>
                        <a href="/remover-video?id=<?php echo $video->id; ?>">Excluir</a>
                        <a href="/remover-capa?id=<?php echo $video->id; ?>">Remover Capa</a>
                    </div>
                </div>
        </li>
    <?php } //fim foreach 
    ?>
</ul>
<?php 
    //require_once __DIR__ .  "/fim-html.php";
    //$this->insert("fim-html"); 
?>