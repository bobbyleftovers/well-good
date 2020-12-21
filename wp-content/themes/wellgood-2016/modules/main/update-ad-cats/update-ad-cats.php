<?php
$file = get_field('ad_cat_update_csv_file', 'options')['url'];

if ($file) :
    $csv = file_get_contents($file);
    $data = array_map("str_getcsv", explode("\n", $csv));
    $count = count($data);
    $data = json_encode($data);
    $index = (int) get_field('ad_cat_current_csv_position', 'options'); ?>

    <script>
        var csvData = <?= $data; ?>;
        var csvIndex = <?= $index; ?>;
        var csvCount = <?= $count; ?>;
    </script>

    <div id="update-ad-cats" class="update-ad-cats" data-module-init="update-ad-cats">
        <div class="update-ad-cats-head">
            Updating Ad Cats
        </div>
    </div>

<?php
else : ?>
    <div id="update-ad-cats" class="update-ad-cats">
        <div class="update-ad-cats-head">
            Upload a CSV file in theme options to update ad cats
        </div>
    </div>
<?php
endif;
