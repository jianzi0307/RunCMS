<?php
/**
 * ----------------------
 * ConfsettingController.php
 *
 * User: jian0307@icloud.com
 * Date: 2015/5/27
 * Time: 14:01
 * ----------------------
 */
/**
 * 数学公式解析器
 * 用法：
 * $formula = new FormulaParser('(8+(10*(3-5)^2))/4.8', 'en', 4);
 * $result = $formula->getResult();
 */

namespace Lib;

interface IFormulaParser
{
    public function setVariables(array $vars);
    public function getResult();
    public function getFormula();
}

class FormulaParser implements IFormulaParser
{
    /**
     * The text of the formula for handling by getResult() method
     * @var null|string
     */
    protected $formula = null;

    /**
     * The text of the formula for returning by getFormula() method
     * @var string
     */
    protected $original_formula = null;

    /**
     * The being evaluated subexpression of the formula
     * @var string
     */
    protected $expression = null;

    /**
     * Are there any errors during parsing: 1 or 0
     * @var int
     */
    protected $correct = 1;

    /**
     * A type of error for displaying a right message: 0,1,2,3 or 4
     * @var int
     */
    protected $error_type = 0;

    /**
     * The selected language in which messages should be displayed: 'en', 'ru' or 'es'
     * @var string
     */
    protected $lang = 'en';

    /**
     * The selected precision rounding of the answer
     * @var int
     */
    protected $precision_rounding = 4;

    /**
     * The passed variables x, y, z, a, b
     * @var array
     */
    protected $variables = array();

    /**
     * Constructor
     * @param $input_string The text of the formula
     * @param $language Setting the language
     * @param $precision_rounding Setting the maximum number of digits after the decimal point in a calculated answer
     */
    public function __construct($input_string, $language, $precision_rounding)
    {
        $this->formula = $this->original_formula = $input_string;

        if (in_array($language, array('en','ru','es'))) {
            $this->lang = $language;
        }

        $this->precision_rounding = $precision_rounding;
    }

    /**
     * Magic overloading method
     * @param string $name
     * @param array $arguments
     * @throws \Exception when the method doesn't exist
     */
    public function __call($name, $arguments)
    {
        throw new \Exception("No such method exists: $name (".implode(', ', $arguments).")");
    }

    /**
     * Returns the text of the formula passed to the constructor
     * @return string
     */
    public function getFormula()
    {
        return $this->original_formula;
    }

    /**
     * Sets variables
     * @param array $vars
     */
    public function setVariables(array $vars)
    {
        $this->variables = $vars;
    }

    /**
     * Helper: sorts a given array by key
     * @param array $array
     * @return array
     */
    private function resortByKey(array $array)
    {
        $new_array = array();
        foreach ($array as $item) {
            $new_array[] = $item;
        }
        return $new_array;
    }

    /**
     * Validates the being evaluated subexpression of the formula
     * @return bool
     */
    private function validation()
    {
        if (preg_match('/[^0-9*+-\/^.apiconstreqlgbxyz\s\t\(\)]/i', $this->expression)) {
            return false;
        }
        return true;
    }

    /**
     * Calculates first-order operations ^, *, /
     * @param array $array
     * @return array
     */
    private function calculate1(array $array)
    {
        $a = 0;
        if (in_array('^', $array)) {
            for ($i=count($array)-1; $i>=0; $i--) {
                $otp = 1;
                if ($array[$i] === '^') {
                    if ((is_numeric($array[$i-1])) && (is_numeric($array[$i+1]))) {
                        if ($array[$i-1] < 0) {
                            $a = pow($array[$i-1]*-1, $array[$i+1]);
                            $otp = 2;
                        } else {
                            $a = pow($array[$i-1], $array[$i+1]);
                        }
                    } else {
                        $this->correct = 0;
                        if (!$this->validation()) {
                            $this->error_type = 1;
                        }
                        break;
                        return $array;
                    }
                    unset($array[$i-1], $array[$i+1]);
                    if ($otp == 1) {
                        $array[$i] = $a;
                    } else {
                        $array[$i] = $a*-1;
                    }
                    $array = $this->resortByKey($array);
                    $i = count($array)-1;
                }
            }
        }

        $a = 0;
        if ((in_array('*', $array)) || (in_array('/', $array))) {
            for ($i=0; $i<=count($array)-1; $i++) {
                if (($array[$i] === '*') || ($array[$i] === '/')) {
                    if ((!is_numeric($array[$i-1])) || (!is_numeric($array[$i+1]))) {
                        $this->correct = 0;
                        if (!$this->validation()) {
                            $this->error_type = 1;
                        }
                        break;
                        return $array;
                    }
                    if ($array[$i] === '*') {
                        $a = $array[$i-1] * $array[$i+1];
                    } elseif ($array[$i] === '/') {
                        if ($array[$i+1] != 0) {
                            $a = round($array[$i-1] / $array[$i+1], 14);
                        } else {
                            $this->correct = 0;
                            if (!$this->validation()) {
                                $this->error_type = 1;
                            }
                            break;
                            return $array;
                        }
                    }
                    unset($array[$i-1], $array[$i+1]);
                    $array[$i] = $a;
                    $array = $this->resortByKey($array);
                    $i = 0;
                }
            }
        }
        return $array;
    }

    /**
     * Calculates second-order operations +, -
     * @param array    $array  The parsed subexpression of the formula
     * @return array
     */
    private function calculate2(array $array)
    {
        $a = 0;
        if ((in_array('+', $array)) || (in_array('-', $array))) {
            for ($i=0; $i<=count($array)-1; $i++) {
                if (($array[$i] === '+') || ($array[$i] === '-')) {
                    if ((!is_numeric($array[$i-1])) || (!is_numeric($array[$i+1]))) {
                        $this->correct = 0;
                        if (!$this->validation()) {
                            $this->error_type = 1;
                        }
                        break;
                        return $array;
                    }
                    if ($array[$i] === '+') {
                        $a = $array[$i-1] + $array[$i+1];
                    } elseif ($array[$i] === '-') {
                        $a = $array[$i-1] - $array[$i+1];
                    }
                    unset($array[$i-1], $array[$i+1]);
                    $array[$i] = $a;
                    $array = $this->resortByKey($array);
                    $i = 0;
                }
            }
        }
        return $array;
    }

    /**
     * Evaluates functions
     *
     * @param string  $function  The name of the function: sqrt, abs, sin, cos, tan, log or exp
     * @param string  $str         The subexpression of the formula
     * @param integer $strlen    The length of the given subexpression
     * @param integer $i         $i value in the parent loop
     */
    private function evaluateFunction($function, &$str, &$strlen, $i)
    {
        $valid_functions = array('sqrt','abs','sin','cos','tan','log','exp');
        if (!in_array($function, $valid_functions)) {
            $this->correct = 0;
            if (!$this->validation()) {
                $this->error_type = 1;
            }
        }
        if ($this->correct) {
            $result = 0;
            $arg = null;
            if ($function == 'sqrt') {
                $j = $i+4;
            } else {
                $j = $i+3;
            }
            while (true) {
                if (isset($str[$j])) {
                    if (((strstr('+-', $str[$j])) && ($arg === null))
                        || (strstr('0123456789', $str[$j]))
                        || (($str[$j] == '.') && (!strstr($arg, '.')))) {
                        $arg .= $str[$j];
                    } elseif ((strstr('     ', $str[$j])) && (!strpbrk($arg, '0123456789'))) {
                    } else {
                        $arg = trim($arg);
                        break;
                    }
                    $j++;
                } else {
                    break;
                }
            }
            if (!is_numeric($arg)) {
                $this->correct = 0;
            } else {
                if ($function == 'exp') {
                    $result = pow(M_E, $arg);
                } else {
                    $result = $function($arg);
                }
            }
            if (($this->correct) && (is_numeric($result))) {
                $str1 = substr($str, 0, $i);
                $str2 = substr($str, $j);
                $str = $str1.' '.$result.$str2;
                $strlen = strlen($str);
            }
        }
    }

    /**
     * Parses and evaluates the subexpression of the formula
     *
     * @param string $str  The subexpression of the formula.
     *               It's in parentheses, or the whole formula if there are no parentheses.
     * @return float
     */
    private function getAnswer($str)
    {
        $str = trim($str);
        $this->expression = $str;
        $strlen = strlen($str);
        $main_array = array();
        $count = 0;
        $valid_variables = array('x','y','z','a','b');

        for ($i=0; $i<=$strlen-1; $i++) {
            if (trim($str) == 'INF') {
                $main_array = array(INF);
                break;
            }
            if (($i == 0) && ($str[$i] == '-')) {
                $main_array[$count] = '-';
            } else {
                // Spaces and tab characters will be skipped
                if (($str[$i] == ' ') || ($str[$i] == '    ')) {
                    $count++;
                    // Number
                } elseif (is_numeric($str[$i])) {
                    if ($i+1<=$strlen-1) {
                        if (!stristr('0123456789.+-*/^e     ', $str[$i+1])) {
                            $this->correct = 0;
                            break;
                        } else {
                            $main_array[$count] = $main_array[$count].$str[$i];
                        }
                    } else {
                        $main_array[$count] = $main_array[$count].$str[$i];
                    }
                    // Constant pi
                } elseif (strtolower($str[$i-1].$str[$i]) == 'pi') {
                } elseif (strtolower($str[$i].$str[$i+1]) == 'pi') {
                    if ($i+2 <= $strlen-1) {
                        if (!strstr('+-*/^     ', strtolower($str[$i+2]))) {
                            $this->correct = 0;
                            break;
                        } else {
                            $count++;
                            $main_array[$count] = M_PI;
                        }
                    } else {
                        $count++;
                        $main_array[$count] = M_PI;
                    }
                    // Constant e
                } elseif ((strtolower($str[$i]) == 'e') && (strtolower($str[$i+1]) != 'x')
                    && (!is_numeric($str[$i-1]))) {
                    if ($i+1 <= $strlen-1) {
                        if (!strstr('+-*/^     ', strtolower($str[$i+1]))) {
                            $this->correct = 0;
                            break;
                        } else {
                            $count++;
                            $main_array[$count] = M_E;
                        }
                    } else {
                        $count++;
                        $main_array[$count] = M_E;
                    }
                    // Number in scientific E notation
                } elseif ((strtolower($str[$i]) == 'e') && (strtolower($str[$i+1]) != 'x')
                    && (is_numeric($str[$i-1]))) {
                    if ((preg_match('/(e)(?!\d|\+|\-)/i', $str[$i].$str[$i+1]))
                        || (!strstr('0123456789', $str[$i-1]))) {
                        $this->correct = 0;
                        break;
                    } else {
                        end($main_array);
                        $prev1 = prev($main_array);
                        $prev2 = prev($main_array);
                        if (($prev1 == '^') || (($prev1 == '-') && ($prev2 == '^'))) {
                            $main_array[$count] = $main_array[$count] * 10;
                            $count++;
                            $count++;
                            $main_array[$count] = '^';
                        } else {
                            $count++;
                            $main_array[$count] = '*';
                            $count++;
                            $main_array[$count] = '10';
                            $count++;
                            $main_array[$count] = '^';
                        }
                        $count++;
                    }
                } elseif (($str[$i] == '-') && (strtolower($str[$i-1]) == 'e') && (is_numeric($str[$i+1]))
                    && (is_numeric($str[$i-2]))) {
                    $main_array[$count] = $str[$i];
                } elseif (($str[$i] == '+') && (strtolower($str[$i-1]) == 'e') && (is_numeric($str[$i+1]))
                    && (is_numeric($str[$i-2]))) {
                    // Decimal point in float
                } elseif (($str[$i] == '.') && (is_numeric($str[$i-1])) && (is_numeric($str[$i+1]))) {
                    $main_array[$count] = $main_array[$count].$str[$i];
                    // Function sqrt
                } elseif (strtolower($str[$i].$str[$i+1].$str[$i+2].$str[$i+3]) == 'sqrt') {
                    $this->evaluateFunction('sqrt', $str, $strlen, $i);
                    // Function abs, sin, cos, tan, log or exp
                } elseif ((strtolower($str[$i].$str[$i+1].$str[$i+2]) == 'abs')
                    || (strtolower($str[$i].$str[$i+1].$str[$i+2]) == 'sin')
                    || (strtolower($str[$i].$str[$i+1].$str[$i+2]) == 'cos')
                    || (strtolower($str[$i].$str[$i+1].$str[$i+2]) == 'tan')
                    || (strtolower($str[$i].$str[$i+1].$str[$i+2]) == 'log')
                    || (strtolower($str[$i].$str[$i+1].$str[$i+2]) == 'exp')) {
                    $this->evaluateFunction($str[$i].$str[$i+1].$str[$i+2], $str, $strlen, $i);
                    // Variable
                } elseif ((stristr('xyzab', $str[$i])) && (count($this->variables))) {
                    if (((array_key_exists($str[$i], $this->variables))
                            && (in_array($str[$i], $valid_variables)))
                        && (is_numeric($this->variables[$str[$i]]))) {
                        if ($main_array[0] == '-' && $count == 0) {
                            $count++;
                        }
                        if ($i+1 <= $strlen-1) {
                            if (!stristr('+-*/^     ', $str[$i+1])) {
                                $this->correct = 0;
                                break;
                            } else {
                                $main_array[$count] = (float) $this->variables[$str[$i]];
                                $count++;
                            }
                        } else {
                            $main_array[$count] = (float) $this->variables[$str[$i]];
                            $count++;
                        }
                    } else {
                        $this->correct = 0;
                        $this->error_type = 4;
                        break;
                    }
                } else {
                    // Operator
                    $count++;
                    if (strstr('+-*/^', $str[$i])) {
                        if (!stristr('0123456789+-spcatelbxyz     ', $str[$i+1])) {
                            $this->correct = 0;
                            break;
                        } else {
                            if (($count == 1) && ($str[$i] == '+') && (!isset($str[$i-1]))) {
                                continue;
                            }
                            $main_array[$count] = $str[$i];
                            $count++;
                        }
                    } else {
                        $this->correct = 0;
                    }
                }
            }
            if (!$this->correct) {
                break;
            }
        }

        if (!$this->correct) {
            if (!$this->validation()) {
                $this->error_type = 1;
            }
            return 0;
        }

        if ($main_array[0] == INF) {
            return INF;
        }

        $main_array = $this->resortByKey($main_array);

        // Combination of operators
        $temp_array = array();
        $i = 0;
        foreach ($main_array as $item) {
            if (($item === '+') || ($item === '-')) {
                if ((($i == 0) && (is_numeric($main_array[$i+1])))
                    || (($i > 0) && (is_numeric($main_array[$i+1])) && (stristr('+-*/^e', $main_array[$i-1])))) {
                    if ($item === '+') {
                        $temp_array[] = $main_array[$i+1];
                    } else {
                        if (($main_array[$i-1] === '-') && ($main_array[$i-2] !== '-')) {
                            $temp_array[] = $main_array[$i+1];
                        } elseif (($main_array[$i-1] === '-') && ($main_array[$i-2] === '-')) {
                            $this->correct = 0;
                            break;
                        } else {
                            $temp_array[] = $item.$main_array[$i+1];
                        }
                    }
                } else {
                    if (($item === '-') && ($main_array[$i+1] === '-')) {
                        if ($temp_array) {
                            $temp_array[] = '+';
                        }
                    } elseif (($item === '-') && ($main_array[$i+1] === '+')) {
                        if ($temp_array) {
                            $temp_array[] = '+';
                        }
                        $temp_array[] = '0';
                        $temp_array[] = '-';
                    } else {
                        $temp_array[] = $item;
                    }
                }
            } elseif ((($i == 1) && (is_numeric($item)) && (strstr('+-', $main_array[$i-1])))
                || (($i > 1) && (is_numeric($item)) && (strstr('+-', $main_array[$i-1]))
                    && (stristr('+-*/^e', $main_array[$i-2])))) {
            } else {
                $temp_array[] = $item;
            }
            $i++;
        }

        $main_array = $temp_array;

        // Get the answer
        $main_array = $this->calculate1($main_array);
        $main_array = $this->calculate2($main_array);

        if (count($main_array) != 1) {
            $this->correct = 0;
        }
        return round($main_array[0], 14);
    }

    /**
     * Checks if there is an exponential expression
     * where the base is a negative number in parentheses,
     * e.g. '(-2) ^ 4', and if yes - calculates it correctly.
     *
     * @param string  $expression
     * @param integer $length
     * @param integer $cursor
     * @param float      $base
     * @return \stdClass
     */
    private function checkExp($expression, $length, $cursor, $base)
    {
        $response = new \stdClass();
        if ($base < 0) {
            $expression = substr($expression, $length-$cursor+1);
            $test_exp = ltrim($expression);
            if ($test_exp[0] == '^') {
                $exp = '';
                for ($q=0; $q<=$cursor-1; $q++) {
                    if ($expression[$q] == '^') {
                        $exp = ' ';
                    } elseif (($exp != '') && (!strstr('     ', $expression[$q]))) {
                        if ((strstr('+-', $expression[$q])) && ($exp == ' ')) {
                            $exp .= $expression[$q];
                        } elseif (strstr('0123456789.(', $expression[$q])) {
                            if ($expression[$q] != '(') {
                                $exp .= $expression[$q];
                            }
                        } else {
                            $exp = trim($exp);
                            $cursor = $cursor - $q;
                            if ($exp[0] == '+') {
                                $exp = substr($exp, 1);
                            }
                            break;
                        }
                    }
                }
                $response->cursor = $cursor;
                if ((!is_numeric($exp)) || (strstr($exp, '.'))) {
                    $this->correct = $response->result = 0;
                } else {
                    $response->result = pow(abs($base), $exp) * pow(-1, $exp);
                }
            }
        }
        return $response;
    }

    /**
     * Returns an error message in the set language
     *
     * @return string
     */
    private function errorMsg()
    {
        // Input error
        if ($this->error_type == 1) {
            if ($this->lang == 'en') {
                return 'Numbers, operators +-*/^, parentheses, specified constants, functions and variables only.';
            } elseif ($this->lang == 'ru') {
                return 'Только цифры, операторы +-*/^, скобки, определенные константы, функции и переменные.';
            } elseif ($this->lang == 'es') {
                return 'Sólo cifras, operadores +-*/^, paréntesis, ciertas constantes, funciones y variables.';
            }
            // Empty string
        } elseif ($this->error_type == 2) {
            if ($this->lang == 'en') {
                return 'You have not entered the formula.';
            } elseif ($this->lang == 'ru') {
                return 'Вы не ввели формулу.';
            } elseif ($this->lang == 'es') {
                return 'Usted no ha entrado en la fórmula.';
            }
            // Mismatched parentheses
        } elseif ($this->error_type == 3) {
            if ($this->lang == 'en') {
                return 'Number of opening and closing parenthesis must be equal.';
            } elseif ($this->lang == 'ru') {
                return 'Количество открывающих и закрывающих скобок должно быть равно.';
            } elseif ($this->lang == 'es') {
                return 'Número de apertura y cierre paréntesis debe ser igual.';
            }
            // Variable error
        } elseif ($this->error_type == 4) {
            if ($this->lang == 'en') {
                return 'Variable error.';
            } elseif ($this->lang == 'ru') {
                return 'Ошибка переменной.';
            } elseif ($this->lang == 'es') {
                return 'Error de variable.';
            }
            // Unexpected error
        } else {
            if ($this->lang == 'en') {
                return 'Syntax error.';
            } elseif ($this->lang == 'ru') {
                return 'Ошибка синтаксиса.';
            } elseif ($this->lang == 'es') {
                return 'Error de sintaxis.';
            }
        }
    }

    /**
     * Parses and evaluates the entered formula
     *
     * @return array  array(0=>value1, 1=>value2), where value1 is the operating status
     *           'done' or 'error', and value2 is a calculated answer
     *                or error message in the set language.
     */
    public function getResult()
    {
        $this->formula = trim($this->formula);

        // Check that the formula has been entered
        if ($this->formula[0] == '') {
            $this->correct = 0;
            $this->error_type = 2;
            //goto finish;
        }

        $open_parentheses_count = substr_count($this->formula, '(');
        $close_parentheses_count = substr_count($this->formula, ')');

        if (($open_parentheses_count > 0 || $close_parentheses_count > 0) && $this->correct) {
            // Check for an equality of opening and closing parentheses
            if ($open_parentheses_count != $close_parentheses_count) {
                $this->correct = 0;
                $this->error_type = 3;
                //goto finish;
            }

            // Check the syntax is correct when using parentheses
            if (preg_match(
                '/(\)[\s\t]*[^\)\+\-\*\/\^\s\t])|(\([\s\t]*?\))|([^nstgp\(\+\-\*\/\^\s\t][\s\t]*\()/',
                $this->formula
            )) {
                $this->correct = 0;
                //goto finish;
            }

            $temp = '';
            $processing_formula = $this->formula;

            // Begin general parse
            while ((strstr($processing_formula, '(') || strstr($processing_formula, ')'))
                && ($this->correct)) {
                $start_cursor_pos = 0;
                $end_cursor_pos = 0;
                $temp = $processing_formula;

                while (strstr($temp, '(')) {
                    $strlen_temp = strlen($temp);
                    for ($i=0; $i<=$strlen_temp-1; $i++) {
                        if ($temp[$i] == '(') {
                            $temp = substr($temp, $i+1);
                            $start_cursor_pos = $start_cursor_pos + $i+1;
                        }
                    }
                }

                $strlen_temp = strlen($temp);
                for ($i=0; $i<=$strlen_temp-1; $i++) {
                    if ($temp[$i] == ')') {
                        $end_cursor_pos = $strlen_temp - $i;
                        $temp = substr($temp, 0, $i);
                        break;
                    }
                }

                $length = strlen($processing_formula);

                if (!empty($temp)) {
                    $temp = $this->getAnswer($temp);
                    $checkExp = $this->checkExp($processing_formula, $length, $end_cursor_pos, $temp);
                    if ($checkExp->result) {
                        $temp = $checkExp->result;
                        $end_cursor_pos = $checkExp->cursor;
                    }
                }

                // Optimize excess parentheses to dynamically reduce the number of iterations
                if (($processing_formula[$start_cursor_pos-2] == '(')
                    && ($processing_formula[$length - $end_cursor_pos+2] == ')')) {
                    $processing_formula = substr($processing_formula, 0, $start_cursor_pos-2)
                        .$temp.substr($processing_formula, $length - $end_cursor_pos+2);
                } else {
                    $processing_formula = substr($processing_formula, 0, $start_cursor_pos-1)
                        .$temp.substr($processing_formula, $length - $end_cursor_pos+1);
                }
            }
            $this->formula = $processing_formula;
        }
        $result = $this->getAnswer($this->formula);

        //finish:

        if ($this->correct) {
            return (array('done', round($result, (int) $this->precision_rounding)));
        } else {
            return (array('error', $this->errorMsg()));
        }
    }
}
