<div class="border-styleguide p-e14 base-component-box">
  <?php brrl_the_module('main-2020/post-card', array(
    'title' => 'This is a placeholder title. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    'excerpt' => 'This is a placeholder excerpt. Donec euismod ac mauris sed congue. Morbi turpis est, porta mattis justo non, mollis congue ante. Nulla tincidunt metus nisi, nec ornare nisl egestas nec.',
    'author' => 'Noam Chomsky',
    'date' => 'April 30, 2020',
    'href' => 'http://www.wellandgood.com/path-to-post',
    )); ?>
</div>


<?php brrl_the_module('styleguide-2020/code', array(
            'title' => 'PHP',
            'footer' => '*You can pass a post object instead of field by field, and all the arguments will be populated using that object',
            'lang' => 'php',
            'code' => "brrl_the_module(
  'main-2020/post-card',
  array(
    'post' => null*,
    'title' => 'Lorem Ipsum',
    'excerpt' => 'This is a placeholder excerpt',
    'date' => 'April 30, 2020',
    'author' => 'Noam Chomsky',
    'href' => 'http://www.wellandgood.com/path-to-post',
    'thumbnail' => 'http://www.wellandgood.com/path-to/thumbnail.jpg',
    'is_featured' => false,
    'is_video' => false
  )
);
"
)); ?>
