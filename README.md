# Fractal Handles

Drupal 8 module. Convert a fractal Handle '@components/name' to a twig template path.

# Usage

Similar to the drupal module [Components Library](https://www.drupal.org/project/components/)
this module allows you to have a folder called `components` in your active theme.
Within this folder you can put `components/into/any/file/file.twig` and it will be
discovered by referencing it via

```twig
{% include '@components/into/any/file' %}
```

The idea is to reference [fractal compound components](http://fractal.build/guide/components#compound-components)
and the twig file inside the folder with the very same name.

## Todo

- make variants available

# History

- since v2.0 this module depends on `drupal/components`

# Credits

code base: [github.com/WondrousLLC/fractal_handles](https://github.com/WondrousLLC/fractal_handles/)

developed by [WONDROUS LLC](https://www.wearewondrous.com/)