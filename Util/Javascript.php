<?php
namespace AgileKernelBundle\Util;

/**
 * Class Javascript
 */
class Javascript
{
    /**
     * @param array $js
     * @param bool $removeNull
     * @return mixed|string
     */
    public static function encodeJS($js, $removeNull = false)
    {
        if ($removeNull) {
            foreach ($js as $k => $v) {
                if (null === $v) {
                    unset($js[$k]);
                }
            }
        }
        $json = json_encode($js);
        $json = preg_replace_callback('#\"function\s*(?:[a-z_][a-z_0-9]*)?\(((\\\")*|(.*?[^\\\](\\\")*))\"#ius', function ($regs) {
            return substr(str_replace(["\\n", "\\t"], '', str_replace('\\"', '"', $regs[0])), 1, -1);
        }, $json);

        return $json;
    }
}
