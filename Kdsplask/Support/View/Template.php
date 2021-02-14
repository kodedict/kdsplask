<?php


namespace Kdsplask\Support\View;

use Kdsplask\Support\View\lib\Engine;

class Template
{
    private string $basePath = __DIR__.'/../../../template/views/';

    public function view($views, array $data = [])
    {
        $template = new Engine($this->basePath);
        try {
          echo $template->render("{$views}.slate.php",$data);
          return null;
        } catch (\Exception $e) {
            echo "Error: $e";
            return null;
        }
    }
}
