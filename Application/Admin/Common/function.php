<?php
/**
 * 获取配置的分组
 * @param int $group 配置分组
 * @return string
 */
function get_config_group($group = 0)
{
    $list = C('CONFIG_GROUP_LIST');
    return $group?$list[$group]:'';
}

/**
 * 获取配置的类型
 * @param int $type 配置类型
 * @return string
 */
function get_config_type($type = 0)
{
    $list = C('CONFIG_TYPE_LIST');
    return $list[$type];
}

/**
 * 分析枚举类型配置值
 * 格式 a:名称1,b:名称2
 * @param $string
 * @return array
 */
function parse_config_attr($string)
{
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if (strpos($string, ':')) {
        $value  =   array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k]   = $v;
        }
    } else {
        $value  =   $array;
    }
    return $value;
}

/**
 * 将相关int变量替换为文本,以便显示
 * @param $data
 * @param array $map
 * @return array
 */
function int_to_string(
    &$data,
    $map = array('status' => array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿'))
) {
    if ($data === false || $data === null) {
        return $data;
    }
    $data = (array)$data;
    foreach ($data as $key => $row) {
        foreach ($map as $col => $pair) {
            if (isset($row[$col]) && isset($pair[$row[$col]])) {
                $data[$key][$col.'_text'] = $pair[$row[$col]];
            }
        }
    }
    return $data;
}

/**
 * 将相关int变量替换为图标class，以便显示
 * @param $data
 * @param array $map
 * @return array
 */
function int_to_icon(
    &$data,
    $map = array('hide' => array(1 => 'icon-eye', 0 => 'icon-eye-close'),
        'is_dev' => array(1 => 'icon-eye', 0 => 'icon-eye-close'))
) {
    if ($data === false || $data === null) {
        return $data;
    }
    $data = (array) $data;
    foreach ($data as $key => $row) {
        foreach ($map as $col => $pair) {
            if (isset($row[$col]) && isset($pair[$row[$col]])) {
                $data[$key][$col.'_icon'] = $pair[$row[$col]];
            }
        }
    }
    return $data;
}

/**
 * 动态扩展左侧菜单,模板里用到
 *
 * @param $extra_menu
 * @param $base_menu
 */
function extra_menu($extra_menu, &$base_menu)
{
    foreach ($extra_menu as $key => $group) {
        if (isset($base_menu['child'][$key])) {
            $base_menu['child'][$key] = array_merge(
                $base_menu['child'][$key],
                $group
            );
        } else {
            $base_menu['child'][$key] = $group;
        }
    }
}


function single(array $param)
{
    return array_product($param);
}
function multiSum(array $data)
{
    if (!empty($data) && is_array($data)) {
        $multiArr = array_map('single', $data);
        $result = array_sum($multiArr);
    }
    return isset($result) ? $result : 0.00;
}
function reGruopArr(array $arr)
{
    $len = count($arr[0]);
    $result = array();
    for ($i = 0; $i < $len; $i++) {
        $result[] = array_column($arr, $i);
    }
    return $result;
}
