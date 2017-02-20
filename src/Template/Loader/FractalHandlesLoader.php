<?php

namespace Drupal\fractalhandles\Template\Loader;

use Symfony\Component\Finder\Finder;
use Twig_Loader_Filesystem;

class FractalHandlesLoader extends Twig_Loader_Filesystem
{

  private $rootPath;

  /**
   * Constructs a new FilesystemLoader object.
   *
   * @param string|array $paths
   *   A path or an array of paths to check for templates.
   */
  public function __construct($paths = [])
  {
    parent::__construct($paths);
    $this->rootPath = $paths;

  }

  /**
   *
   * Just return the default namespace with the name
   *
   * @param $name
   * @param string $default
   * @return array
   */
  protected function parseName($name, $default = self::MAIN_NAMESPACE)
  {
    return [$default, $name];
  }

  /**
   *
   * changes the # Handle to the template name
   *
   * @param string $name
   * @return bool|string
   */
  public function getCacheKey($name)
  {
    return parent::getCacheKey($this->handleToName($name));
  }

  /**
   *
   * Run exists with the correct template path
   *
   * @param string $name
   * @return bool
   */
  public function exists($name)
  {
    return parent::exists($this->handleToName($name));
  }

  /**
   *
   * Run getSourceContext with the correct template path
   *
   * @param string $name
   * @return \Twig_Source
   */
  public function getSourceContext($name)
  {
    return parent::getSourceContext($this->handleToName($name));
  }

  /**
   *
   * Convert a fractal Handle '#componentName' to a twig template path
   *
   * @param $handle
   * @return string
   */
  private function handleToName($handle)
  {
    if($handle[0] !== '#') return $handle;

    $activeTheme = \Drupal::theme()->getActiveTheme()->getPath();
    $componentName = substr($handle, 1);

    // find the correct folder;
    $finder = new Finder();
    $finder
      ->directories()
      ->in($this->rootPath . '/' . $activeTheme . '/' . 'components')
      ->name($componentName);

    foreach ($finder as $directory) {
      return $activeTheme . '/' . 'components' .'/' . $directory->getRelativePathname() . '/' . $directory->getFilename() .'.twig';
    }
  }
}
