<?php
/**
 * Created by PhpStorm.
 * User: Stas
 * Date: 30/03/19
 * Time: 12:37
 */

class TemplateEngine
{
    public $output_path = "/output/";
    public $cache_path = "/cache/";
    public $blade_extension = "._blade";
    public $php_extension = ".php";

    /**
     * Processes _blade template
     * And returns pure html code.
     *
     * @param $template_name
     * @param array $data_arr - Empty by default.
     * @return string
     */
    public function output($template_name, $data_arr = []) {
        $source_filename = __DIR__ ."/..". $this->output_path . $template_name . $this->blade_extension . $this->php_extension;
        $parsed_filename = __DIR__ ."/..". $this->cache_path . $template_name . $this->php_extension;

        //todo: fix warning --- filemtime()
        $isNewer = filemtime($parsed_filename) > filemtime($source_filename);
        if (file_exists($parsed_filename) and $isNewer) {
            echo "\n<br><i style='color: coral;'>//templator log: The newest version of file was obtained from CACHE</i><br>\n";
            extract($data_arr, EXTR_SKIP);
            ob_start();
            require "$parsed_filename";
            return ob_get_clean();
        }
        if (file_exists($source_filename)) {
            echo "\n<br><i style='color: coral;'>//templator log: The file was PARSED and now you see it</i><br>\n";
            $blade_code = file_get_contents($source_filename);
            $parsed = $this->parse_blade($blade_code);
            $this->record_pure_php_to_cache_folder($template_name, $parsed);
            extract($data_arr, EXTR_SKIP);
            ob_start();
            require "$parsed_filename";
            return ob_get_clean();
        } else {
            echo "\nThe file $source_filename does not exist";
            return -1;
        }
    }

    /**
     * Parses our *._blade.php files.
     * @param $blade_code
     * @return mixed $php - pure php code.
     */
    public function parse_blade($blade_code)
    {
        $php = str_replace("{{", " <?php ", $blade_code);
        $php = str_replace("}}", " ?> ", $php);
        $php = str_replace(">>>", " echo ", $php);
        $php = str_replace("[[", " foreach ", $php);
        $php = str_replace("]]", " endforeach; ", $php);
        $php = str_replace("^^", " htmlentities", $php);
        return $php;
    }


    /**
     * Saves file to cache directory.
     * @param $name
     * @param $php
     */
    public function record_pure_php_to_cache_folder($name, $php)
    {
        $filename = __DIR__ . "/.." . $this->cache_path . $name . $this->php_extension;
        file_put_contents($filename, $php);
    }

    public function showPage($html){
        echo $html;
    }
}