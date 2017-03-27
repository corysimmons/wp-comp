# wp-comp

Component architecture in WP. Cleans up WP_Query loops while making content more reusable.

## Installation

- `cd ~/Projects/my_wordpress_site/wp-content/plugins`
- `git clone git@github.com:corysimmons/wp-comp.git`
- Activate on plugins screen.
- To update: `cd ~/Projects/my_wordpress_site/wp-content/plugins/wp-comp; git pull`

> **Note:** I'll add this to the WP plugin repository and Packagist (for adding/maintaining via Composer) after more real-world testing.

## Usage

```php
<?php // themes/foo/index.php ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>WP Comp</title>
</head>
<body>

  <?=
    component([
      'component_filepath' => 'components/slider/slider.php',
      'css_filepath' => 'components/slider/slider.css',
      'js_filepath' => 'components/slider/slider.js',
      'error_filepath' => 'components/error.php',
      'wp_query_args' => [
        'cat' => 'slide'
      ],
      'context' => [
        'title' => 'Slider Component 1',
        'classes' => [
          'slider',
          'slide'
        ]
      ]
    ]);
  ?>

</body>
</html>
```

```php
<?php // themes/foo/components/slider/slider.php ?>

<?php if ($params['context']['title']) : ?>
  <h2><?= $params['context']['title']; ?></h2>
<?php endif; ?>

<ul class="<?= $params['context']['classes'][0]; ?>">

  <?php while ($c->have_posts()) : $c->the_post(); ?>

    <li class="<?= $params['context']['classes'][1]; ?>">
      <?= get_the_title(); ?>
    </li>

  <?php endwhile; ?>

</ul>
```

```css
/* themes/foo/components/slider/slider.css */

* {
  background: red;
}
```

```js
// themes/foo/components/slider/slider.js

console.log('Sliders are cool!')
```

## API

### `component_filepath`

Path from theme dir to `.php` file containing your component's markup (and PHP).

### `wp_query_args`

Standard [WP_Query args](https://codex.wordpress.org/Class_Reference/WP_Query).

### `context`

A string, number, or array, designed to pass variables and whatever data you might need along to the component's PHP.

### `css_filepath`

Path from theme dir to `.css` file to be loaded immediately *before* your component's markup.

### `js_filepath`

Path from theme dir to `.js` file to be loaded immediately *after* your component's markup.

### `error_filepath`

Path from theme dir to `.php` file to be loaded in place of your component's markup in the event WP_Query fails or `component_filepath` was specified incorrectly.
