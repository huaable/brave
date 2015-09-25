<?php
namespace Cms\Extensions;

use Brave\Helpers\View;

class ParseSql
{

    public $tableContent = [];
    public $tableDesc = [];


    public function __construct($sqlFile)
    {
        $tableSyntax = View::renderFile($sqlFile, [], true);
        preg_match_all('/CREATE TABLE.*(\(.*?\))(.*?)/isU', $tableSyntax, $typefile);
        $tableContent = explode(',', trim($typefile[1][0], '()'));
        $tmp = [];
        foreach ($tableContent as $value) {
            $arr = explode(' ', trim($value));
            $arrNew = [];
            foreach ($arr as $v) {
                if (trim($v) !== '') {
                    $arrNew[] = trim($v);
                }
            }
            $tmp[] = $arrNew;
        }

        $this->tableContent = $tmp;

        $tableDesc = explode(' ', trim($typefile[2][0], '()'));
        $tmp = [];
        foreach ($tableDesc as $item) {
            if (trim($item) !== '') {
                $tmp[] = $item;
            }
        }
        $this->tableDesc = $tmp;
    }

    public function attributes()
    {
        $words = [
            'PRIMARY',
            'UNIQUE',
        ];
        $attrs = [];
        foreach ($this->tableContent as $item) {
            if (!in_array(strtoupper($item[0]), $words)) {
                $attrs[] = trim($item[0], '\`');
            }
        }
        return $attrs;
    }
}