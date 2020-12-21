<section class="voting-state">
    <h2 class="module-heading module-heading--nominations"><?php the_field( 'form_heading' ); ?></h2>
    <div class="voting__description post__wysiwyg"><?php the_field( 'form_description' ); ?></div>

    <div class="voting-state-nominations__container">
        <div class="voting-state-nominations__form">
            <?php the_field( 'form_embed', false, false ); ?>
        </div>

        <aside class="sidebar voting__sidebar">
            <?php the_module('advertisement', array(
                'slots' => array(
                    'rightrail'
                ),
                'page' => 0,
                'iteration' => 0
            )); ?>
        </aside>
    </div>
</section>
