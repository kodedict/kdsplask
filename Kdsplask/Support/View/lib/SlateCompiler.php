<?php


namespace Kdsplask\Support\View\lib;


use DOMDocument;
use DOMXPath;
use Exception;

class SlateCompiler
{
    private string $basePath = __DIR__ . '/../../../../template/views/';
    public array $data;

    public function extends(string $extendRaw, string $slate = '',$c ='')
    {
        $extendPathClean = str_replace('(','', $extendRaw);
        $extendPathClean = str_replace(')','', $extendPathClean);
        $extendPathClean = str_replace("'",'', $extendPathClean);

        $extendPath = "$this->basePath$extendPathClean.slate.php";

        /**
         * @param string $extendPath
         * @throws Exception
         */
        if (!file_exists($extendPath)) {
            throw new Exception(sprintf('The directive %s could not be found.','@extends'));
        }

        $content = file_get_contents($extendPath);

        /*
        * == Handles @yield directive ==
        * */
        $yieldContainer = "";
       if( preg_match_all("/@yield(.*)/",
            $content,
            $out, PREG_PATTERN_ORDER) ) {
           $yield =(array_filter($out,function ($key,$val){
               return $val != "@yield";
           },ARRAY_FILTER_USE_BOTH));

           $yieldContainer = $this->yield($yield[1][0],$slate);
       }

        $content = preg_replace("/@include(.*)/","<?php include($1.'.slate.php') ?>",$content);
        $content = preg_replace("/@yield(.*)/",$yieldContainer,$content);
        return eval(' ?>' . $content . '<?php');
    }

    public function yield(string $yieldRaw, string $slate): string
    {
        $content = file_get_contents($slate);
        foreach ($this->data as $key => $value){
            $content = preg_replace('/{{'.$key.'}}/',$value,$content);
        }
        $content = preg_replace("/@section(.*)/","<div id=$1>",$content);
        $content = preg_replace("/@endSection/","</div>",$content);

        $dom = new DOMDocument();
        $dom->loadHTML($content);
        $x = new DOMXPath($dom);
        $node = $dom->getElementById("$yieldRaw");
        return $node ? $node->nodeValue : '';
    }

    public function section(){}

    public function endSection(){}
}
