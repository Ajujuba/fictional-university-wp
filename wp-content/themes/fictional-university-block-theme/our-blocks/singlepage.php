<?php 

while (have_posts()) { //comment if you update to WP 6.4
    the_post(); //comment if you update to WP 6.4
    pageBanner(); ?>

    <div class="container container--narrow page-section">

        <?php 
            $theParent = wp_get_post_parent_id(get_the_ID()); //gets the parent page ID of the current page. get_the_ID() gets the ID of the current page and sends that ID to wp_get_post_parent_id() to get the ID of the parent page.
            if($theParent):
        ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p><a class="metabox__blog-home-link" href="<?= get_permalink($theParent)?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?= get_the_title($theParent) ?></a> <span class="metabox__main"><?php the_title();?></span></p>
            </div>
        <?php endif ?>
            
        <?php 
            // se a página atual tiver filhos, a função retorna a coleção de todas as paginas filhas 
            $testArray= get_pages([
                'child_of' => get_the_ID()
            ]);

            //verifica se tem um pai ou é um pai, se for verdadeiro, entra no if
            if($theParent || $testArray): ?>
            <div class="page-links">
                <h2 class="page-links__title"><a href="<?= get_permalink($theParent) ?>"><?= get_the_title($theParent) ?></a></h2>
                <ul class="min-list">
                    <?php 
                        if($theParent){
                            $findChildrenOf = $theParent ;
                        }else{
                            $findChildrenOf = get_the_ID();
                        }
                        wp_list_pages([
                            'title_li' => NULL,
                            'child_of' => $findChildrenOf
                        ]);
                    ?>
                </ul>
            </div>
        <?php endif ?>

        <div class="generic-content">
            <?php 
                the_content(); 
                $skyColorvalue = sanitize_text_field(get_query_var('skyColor'));
                if($skyColorvalue == 'blue'){
                    echo 'hi guys, the life is beautiful!';
                }
            ?>
        </div>

    </div>

<?php } //comment if you update to WP 6.4


