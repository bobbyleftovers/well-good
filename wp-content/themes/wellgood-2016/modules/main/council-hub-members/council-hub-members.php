<?php if( have_rows('council_members','options') ) : ?>

    <section class="council-members">
        <h2 class="module-heading lochub-blocks-heading"><?= the_field('council_grid_title', 'options'); ?></h2>
        <div class="council-grid">
        <?php while( have_rows('council_members', 'options') ) : the_row();
            // Variables
            $name = get_sub_field('member_name');
            $bio = get_sub_field('member_bio');
            $photo = get_sub_field('member_photo');
            $photo_url = $photo['sizes']['medium'] ? $photo['sizes']['medium'] : $photo['url'];
            $photo_retina_url = $photo['sizes']['article-retina'] ? $photo['sizes']['article-retina'] : '';
            $read_more_url = get_sub_field('read_more_link');
            $read_more_label = get_sub_field('read_more_label');
        ?>

            <div class="council-member">
                <div class="council-member__image">
                    <?php the_module('image', $photo_url, $photo_retina_url, $name); ?>
                </div>
                <h2 class="council-member__name"><?= $name; ?></h2>
                <p class="council-member__bio"><?= $bio; ?></p>
                <a href="<?= $read_more_url; ?>" class="council-member__read-more"><?= $read_more_label; ?></a>
            </div>

        <?php endwhile; ?>
        </div>
    </section>

<?php endif; ?>
