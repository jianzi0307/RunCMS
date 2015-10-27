<?php
namespace Lib;

class ExcelUtil
{
    /**
     * 数字转英文字母
     * @param $num
     * @return mixed
     */
    public static function numToStr($num)
    {
        $arr = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',9=>'J',10=>'K',
            11=>'L',12=>'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',18=>'S',19=>'T',20=>'U',
            21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',
            31=>'AF',32=>'AG',33=>'AH',34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',
            41=>'AP',42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',50=>'AY',
            51=>'AZ',52=>'BA',53=>'BB',54=>'BC',55=>'BD',56=>'BE',57=>'BF',58=>'BG',59=>'BH',60=>'BI',
            61=>'BJ',62=>'BK',63=>'BL',64=>'BM',65=>'BN',66=>'BO',67=>'BP',68=>'BQ',69=>'BR',70=>'BS',
            71=>'BT',72=>'BU',73=>'BV',74=>'BW',75=>'BX',76=>'BY',77=>'BZ',78=>'CA',79=>'CB',80=>'CC',
            81=>'CD',82=>'CE',83=>'CF',84=>'CG',85=>'CH',86=>'CI',87=>'CJ',88=>'CK',89=>'CL',90=>'CM',
            91=>'CN',92=>'CO',93=>'CP',94=>'CQ',95=>'CR',96=>'CS',97=>'CT',98=>'CU',99=>'CV',100=>'CW');
        return $arr[$num];
    }

    /**
     * 填充Excel单元格居中
     * @param \PHPExcel $phpExcel
     * @param $field
     */
    public static function setTextAlignCenter(\PHPExcel $phpExcel, $field)
    {
        $phpExcel->getActiveSheet()->getStyle($field)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
    }

    /**
     * 填充Excel单元格颜色
     * @param \PHPExcel $phpExcel PHPExcel对象
     * @param string $field 例如：A1
     * @param string $color 颜色
     */
    public static function fillColor(\PHPExcel $phpExcel, $field, $color)
    {
        $phpExcel->getActiveSheet()->getStyle($field)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $phpExcel->getActiveSheet()->getStyle($field)->getFill()->getStartColor()->setARGB($color);
    }

    /**
     * 设置单元框边框
     * 例如：
     *  ExcelUtil::setBorder($phpExcel,'A1','FF000000');
     *  ExcelUtil::setBorder($phpExcel,'A1:C9','FF000000');
     * @param \PHPExcel $phpExcel PHPExcel对象
     * @param string $field 例如：A1 单个单元格， A1:C9 某范围单元区域
     * @param string $color 颜色
     */
    public static function setBorder(\PHPExcel $phpExcel, $field, $color = 'FF000000')
    {
        $styleThinBlackBorderOutline = array(
            'borders' => array (
                'outline' => array (
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,  //设置border样式
                    //'style' => PHPExcel_Style_Border::BORDER_THICK, 另一种样式
                    'color' => array ('argb' => $color),     //设置border颜色
                ),
            ),
        );
        $phpExcel->getActiveSheet()->getStyle($field)->applyFromArray($styleThinBlackBorderOutline);
    }


    /**
     * 设置单元格内自动换行，适用于单元格内容过长的情况
     * @param \PHPExcel $phpExcel
     * @param string $field
     */
    public static function setWrap(\PHPExcel $phpExcel, $field)
    {
        //$phpExcel->getActiveSheet()->getStyle($field)->getAlignment()->setShrinkToFit(true);
        $phpExcel->getActiveSheet()->getStyle($field)->getAlignment()->setWrapText(true);
    }

    /**
     * 设置列自适应宽度
     * @param \PHPExcel $phpExcel
     * @param string $col 例如：A
     */
    public static function setAutoSize(\PHPExcel $phpExcel, $col)
    {
        $phpExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
    }


    /**
     * 行高
     * 例如：　ExcelUtil::setRowHeight($phpExcel,6,30);
     * @param \PHPExcel $phpExcel
     * @param int $row
     * @param int $value
     */
    public static function setRowHeight(\PHPExcel $phpExcel, $row, $value)
    {
        $phpExcel->getActiveSheet()->getRowDimension($row)->setRowHeight($value);
    }

    /**
     * 设置列宽
     * 例如：　ExcelUtil::setColWith($phpExcel,'A',30);
     * @param \PHPExcel $phpExcel
     * @param string $col
     * @param int $value
     */
    public static function setColWidth(\PHPExcel $phpExcel, $col, $value)
    {
        $phpExcel->getActiveSheet()->getColumnDimension($col)->setWidth($value);
    }

    /**
     * 设置worksheet标题
     * 例如：　ExcelUtil::setSheetTitle($phpExcel,'XXX');
     * @param \PHPExcel $phpExcel
     * @param string $title
     */
    public static function setSheetTitle(\PHPExcel $phpExcel, $title)
    {
        $phpExcel->getActiveSheet()->setTitle($title);
    }

    /**
     * 设置单元格值
     * @param \PHPExcel $phpExcel
     * @param string $field
     * @param string $value
     */
    public static function setCellValue(\PHPExcel $phpExcel, $field, $value)
    {
        $phpExcel->getActiveSheet()->setCellValue($field, $value);
    }

    /**
     * 将A1单元格设置为加粗，水平垂直居中
     * @param \PHPExcel $phpExcel
     * @param $field
     * @param $size
     * @param $argb
     */
    public static function setCenterBold(\PHPExcel $phpExcel, $field, $size = 10, $argb = '00000000')
    {
        $style = array(
            'font' => array(
                'bold' => true,
                'size' => $size,
                'color' => array(
                    'argb' => $argb,
                ),
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
        );
        $phpExcel->getActiveSheet()->getStyle($field)->applyFromArray($style);
    }

    /**
     * 创建一个新的WorkSheet
     * @param \PHPExcel $phpExcel
     * @param string $title 标题
     */
    public static function newWorkSheet(\PHPExcel $phpExcel, $title)
    {
        $workSheet = $phpExcel->createSheet();
        $workSheet->setTitle($title);
    }

    /**
     * 下载Excel文件
     * @param \PHPExcel $phpExcel
     * @param string $fileName
     */
    public static function downloadExcel(\PHPExcel $phpExcel, $fileName)
    {
        $writer = new \PHPExcel_Writer_Excel2007($phpExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$fileName.'"');
        header("Content-Transfer-Encoding:binary");
        $writer->save('php://output');
    }

    /**
     * 设置表头
     * @param \PHPExcel $phpExcel
     * @param string $field
     * @param string $col
     * @param string $name
     * @param int $size 字体大小
     * @param string $fillColor 填充颜色
     * @param string $fontColor 字体颜色
     * @param string $borderColor 边框颜色
     */
    public static function setSheetTitleStyle(
        \PHPExcel $phpExcel,
        $field,
        $col,
        $name,
        $size = 10,
        $fillColor = 'FF3399FF',
        $fontColor = '00000000',
        $borderColor = '00000000'
    ) {
        self::setCellValue($phpExcel, $field, $name);
        self::setCenterBold($phpExcel, $field, $size, $fontColor);
        self::setAutoSize($phpExcel, $col);
        self::setWrap($phpExcel, $field);
        self::fillColor($phpExcel, $field, $fillColor);
        self::setBorder($phpExcel, $field, $borderColor);
    }

    /**
     * 读Excel
     * @param string $path
     * @return \PHPExcel|void
     * @throws \PHPExcel_Reader_Exception
     */
    public static function readExcelFile($path)
    {
        $reader = new \PHPExcel_Reader_Excel2007();
        if (!$reader->canRead($path)) {
            $reader = new \PHPExcel_Reader_Excel5();
            if (!$reader->canRead($path)) {
                echo("no Excel!");
                return ;
            }
        }
        return $reader->load($path);
    }

    /**
     * 获取工作表数据
     * @param \PHPExcel $phpExcel
     * @param int $t 评价周期t
     * @param int $sheetId 数据表ID 0开始
     * @return array
     * @throws \PHPExcel_Exception
     */
    public static function getSheetData(\PHPExcel $phpExcel, $t, $sheetId)
    {
        $currentSheet = $phpExcel->getSheet($sheetId);
        $allColumn = $currentSheet->getHighestColumn();
        $theData = array();
        //$i为行 $j为列
        for ($i = 0; $i < $t; $i++) {
            $row = array();
            for ($j = 'A'; $j <= $allColumn; $j++) {
                $val = $currentSheet
                    ->getCellByColumnAndRow(ord($j) - 65, $i+2)
                    ->getValue();/**ord()将字符转为十进制数*/
                $row[] = $val;
            }
            $theData[] = $row;
        }
        return $theData;
    }

    /**
     * 设置工作表数据
     * @param \PHPExcel $phpExcel
     * @param string $startDate
     * @param string $endDate
     * @param int $sheetId 工作表ID
     * @param array $data 表头数据
     */
    public static function setSheetData(\PHPExcel $phpExcel, $startDate, $endDate, $sheetId, array $data)
    {
        //评价周期
        $days = DateUtil::inDays($startDate, $endDate);
        $t = count($days);

        $phpExcel->setActiveSheetIndex($sheetId);
        $sheetFieldNum = count($data);
        for ($i = 0; $i < $sheetFieldNum; $i++) {
            $name = $data[$i];
            $col = self::numToStr($i);
            $field = $col.'1';
            self::setSheetTitleStyle($phpExcel, $field, $col, $name);
            //$i为列，$j为行
            for ($j=0; $j < $t; $j++) {
                if ($i == 0) {
                    self::setCellValue($phpExcel, self::numToStr($i) . ($j + 2), $days[$j]);
                } else {
                    self::setCellValue($phpExcel, self::numToStr($i) . ($j + 2), '');
                }
                self::setBorder($phpExcel, self::numToStr($i) . ($j + 2));
            }
        }
    }

    /**
     * 设置工作表
     * @param array $data 工作表选项卡名称数组
     * @return \PHPExcel
     */
    public static function setSheet(array $data)
    {
        $phpExcel = new \PHPExcel();
        //创建worksheet
        $workSheetNum = count($data);
        for ($i = 0; $i < $workSheetNum; $i++) {
            if ($i > 0) {
                self::newWorkSheet($phpExcel, $data[$i]);
            } else {
                self::setSheetTitle($phpExcel, $data[0]);
            }
            //$phpExcel->setActiveSheetIndex($i);
            //$phpExcel->getActiveSheet()->setTitle($workSheetNames[$i]);
        }
        return $phpExcel;
    }
}
