<?php

namespace Kdsplask\Support\View\lib;

use Exception;

class Engine
{
    /**
     * @var string
     */
    private string $path;



    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     */

    public function __construct(string $path)
    {
        $this->path = rtrim($path, '/').'/';
    }

    /**
     * @param string $view
     * @param array $data
     * @throws Exception
     */
    public function render(string $view, array $data = [])
    {
        if (!file_exists($file = $this->path.$view)) {
            throw new Exception(sprintf('The file %s could not be found.', $view));
        }

        $content = file_get_contents($file);
        foreach ($data as $key => $value){
            $content = preg_replace('/{{'.$key.'}}/',$value,$content);
        }

        /*
         * == Handles @extends directive ==
         * */
      if(  preg_match_all("/@extends(.*)/",
            $content,
            $out, PREG_PATTERN_ORDER)) {
          $extend =(array_filter($out,function ($key,$val){
              return $val != "@extends";
          },ARRAY_FILTER_USE_BOTH));

          $slateCompiler = new SlateCompiler();
          $slateCompiler->data = $data;
          chdir(dirname($this->path.'/view/'));
          return $slateCompiler->extends($extend[1][0], $file);
      }
      else{
          if (strpos($file, 'slate') != false) {
              chdir(dirname($this->path.'/view/'));
              $content = preg_replace("/@include(.*)/","<?php include($1.'.slate.php') ?>",$content);
              $content = preg_replace("/@extends(.*)/","",$content);
              $content = preg_replace("/@section(.*)/","<div id=$1>",$content);
              $content = preg_replace("/@endSection/","</div>",$content);
          }
          return eval(' ?>' . $content . '<?php');
      }
    }



}
