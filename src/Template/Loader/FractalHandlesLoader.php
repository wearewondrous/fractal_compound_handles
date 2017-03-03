<?php

namespace Drupal\fractal_handles\Template\Loader;

use Drupal\Core\Theme\ThemeManagerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Twig_Loader_Filesystem;

class FractalHandlesLoader extends Twig_Loader_Filesystem {

  /**
   * @var ThemeManagerInterface
   */
  protected $theme_manager;

  /**
   * Construct a new FilesystemLoader object.
   *
   * @param string|array $paths A path or an array of paths where to look for templates
   * @param ThemeManagerInterface $themeManager
   */
  public function __construct($paths = array(), ThemeManagerInterface $themeManager) {
    parent::__construct($paths, null);
    $this->theme_manager = $themeManager;
  }

  /**
   *
   * Just return the default namespace with the name.
   *
   * @param $name
   * @param string $default
   *
   * @return array
   */
  protected function parseName($name, $default = self::MAIN_NAMESPACE) {
    return [$default, $name];
  }

  /**
   *
   * Change the # handle to the template name.
   *
   * @param string $name
   *
   * @return bool|string
   */
  public function getCacheKey($name) {
    return parent::getCacheKey($this->convertToTwigPath($name));
  }

  /**
   *
   * Run exists with the correct template path.
   *
   * @param string $name
   *
   * @return bool
   */
  public function exists($name) {
    return parent::exists($this->convertToTwigPath($name));
  }

  /**
   *
   * Run getSourceContext with the correct template path.
   *
   * @param string $name
   *
   * @return \Twig_Source
   */
  public function getSourceContext($name) {
    return parent::getSourceContext($this->convertToTwigPath($name));
  }

  /**
   *
   * Convert a fractal Handle '#componentName' to a twig template path.
   *
   * @param $handle
   *
   * @return string
   */
  private function convertToTwigPath($handle) {
    if ($handle[0] !== '#') {
      return $handle;
    }

    $activeTheme = $this->theme_manager->getActiveTheme()->getPath();
    $componentName = substr($handle, 1);

    foreach ($this->getPaths() as $path) {
      $directoryPath = [
        $path,
        $activeTheme,
        'components'
      ];
      $directoryPath = implode(DIRECTORY_SEPARATOR, $directoryPath);

      // find the correct folder;
      $finder = new Finder();
      $finder
        ->directories()
        ->in($directoryPath)
        ->name($componentName);

      if ($finder->count() !== 1) {
        continue;
      }

      /** @var SplFileInfo $file */
      foreach ($finder as $file) {
        $twigPath = [
          $activeTheme,
          'components',
          $file->getRelativePathname(),
          $file->getFilename() . '.twig'
        ];

        return implode(DIRECTORY_SEPARATOR, $twigPath);
      }

    }

    throw new \Twig_Error_Loader("Fractal component <code>{$handle}</code> not found.");
  }
}
