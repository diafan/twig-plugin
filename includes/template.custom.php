<?php
/**
 * @package    DIAFAN.CMS
 *
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2016 OOO «Диафан» (http://www.diafan.ru/)
 */

if (! defined('DIAFAN'))
{
	$path = __FILE__; $i = 0;
	while(! file_exists($path.'/includes/404.php'))
	{
		if($i == 10) exit; $i++;
		$path = dirname($path);
	}
	include $path.'/includes/404.php';
}

class Template extends Diafan
{
  new private $twig;
  new const FROM_VIEW = 'view';
  new const FROM_TEMPLATE = 'twig';

  new function __construct(&$diafan) {
    parent::__construct($diafan);

    Custom::inc('plugins/loader.php');

    $classLoader = new SplClassLoader('Twig', ABSOLUTE_PATH.Custom::path('plugins'));
    $classLoader->register();

    $loader = new Twig_Loader_Filesystem(ABSOLUTE_PATH);
    $this->twig = new Twig_Environment($loader, array(
			'debug' => MOD_DEVELOPER,
			'cache' => ABSOLUTE_PATH.'cache'
		));

    //$classLoader->unregister();
  }

	new public function getTwig() {
		return $this->twig;
	}

  new protected function findTemplate($from, &$name, $module, $template = '') {
    $file = null;

    if(defined('IS_MOBILE') && IS_MOBILE)
		{
			if($template && Custom::exists('modules/'.$module.'/'.$from.'s/m/'.$module.'.'.$from.'.'.$name.'_'.$template.'.php'))
			{
				$file = 'modules/'.$module.'/'.$from.'s/m/'.$module.'.'.$from.'.'.$name.'_'.$template.'.php';
				$name .= '_'.$template;
			}
			if(! $file && Custom::exists('modules/'.$module.'/'.$from.'s/m/'.$module.'.'.$from.'.'.$name.'.php'))
			{
				$file = 'modules/'.$module.'/'.$from.'s/m/'.$module.'.'.$from.'.'.$name.'.php';
			}
		}
		if(! $file && $template && Custom::exists('modules/'.$module.'/'.$from.'s/'.$module.'.'.$from.'.'.$name.'_'.$template.'.php'))
		{
			$file = 'modules/'.$module.'/'.$from.'s/'.$module.'.'.$from.'.'.$name.'_'.$template.'.php';
			$name .= '_'.$template;
		}
		if(! $file && Custom::exists('modules/'.$module.'/'.$from.'s/'.$module.'.'.$from.'.'.$name.'.php'))
		{
			$file = 'modules/'.$module.'/'.$from.'s/'.$module.'.'.$from.'.'.$name.'.php';
		}

    return $file;
  }

  new private function normalizeName($name) {
    return preg_replace('/[^a-z0-9_]+/', '', $name);
  }

  replace function get($name, $module, $result, $template = '') {
    $name = $this->normalizeName($name);
    $current_module = $this->diafan->current_module;


    $file = $this->findTemplate(self::FROM_TEMPLATE, $name, $module, $template);
    if($file) {
        $this->diafan->current_module = $module;
        $this->js($name, $module);

        $template = $this->twig->load(Custom::path($file));
        $text = $template->render($result);

        $this->diafan->current_module = $current_module;

        return $text;
    }

    $file = $this->findTemplate(self::FROM_VIEW, $name, $module, $template);
    if($file) {

			$this->diafan->current_module = $module;
			$this->js($name, $module);

      ob_start();
			include(ABSOLUTE_PATH.Custom::path($file));
			$text = ob_get_contents();
			ob_end_clean();

      $this->diafan->current_module = $current_module;

      return $text;
    }

    return '';
  }

}
