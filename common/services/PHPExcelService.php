<?php

namespace common\services;
class PHPExcelService
{
    /**
     * 获取Excel表单数据
     * @param int $inFile 读取文件路径
     * @param bool $index 读取表格索引，默认读取所有数据 合并后返回
     * @return array
     */
    public static function readSheet($inFile, $index = false)
    {
        $type = \PHPExcel_IOFactory::identify($inFile);
        $reader = \PHPExcel_IOFactory::createReader($type);
        $sheet = $reader->load($inFile);
//        $aIndex = $sheet->getActiveSheetIndex();//获取当前活动表格索引
        $sCount = $sheet->getSheetCount();//获取文件中表格数量
//        Jeen::echoln($aIndex.' of '.$sCount);
        if (is_int($index) && $index < $sCount && $index >= 0)
            return $sheet->getSheet($index)->toArray();
        if ($sCount == 1)
            return $sheet->getSheet(0)->toArray();
        $data = [];
        for ($i = 0; $i < $sCount; $i++) {
            $data[] = $sheet->getSheet($i)->toArray();
        }
        unset($sheet);
        unset($reader);
        unset($type);
        return $data;
    }

    /**
     * 将数据保存至Excel 表格
     * @param string $outFile 输出文件路径
     * @param array $data 需要保存的数据  二维数组
     * @return bool
     */
    public static function outSheet(array $data)
    {
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('B1:F1');
        $objectPHPExcel->getActiveSheet()->setCellValue('B1', '红包统计报表');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '日期：' . date("Y年m月j日"));
        //表格头的输出
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '时间');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(22);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', '发红包数');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', '交易红包数');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', '发总额度（B币）');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', '交易总额度（B币）');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);

        //设置居中
        $objectPHPExcel->getActiveSheet()->getStyle('B3:F3')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置边框
        $objectPHPExcel->getActiveSheet()->getStyle('B3:F3')
            ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B3:F3')
            ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B3:F3')
            ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B3:F3')
            ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B3:F3')
            ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        //设置颜色
        $objectPHPExcel->getActiveSheet()->getStyle('B3:F3')->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');

        $count = count($data);
        $n = 0;
        foreach ($data as $key => $value) {
            //明细的输出
            $objectPHPExcel->getActiveSheet()->setCellValue('B' . ($n + 4), $key);
            $objectPHPExcel->getActiveSheet()->setCellValue('C' . ($n + 4), $value['creatCount']);
            $objectPHPExcel->getActiveSheet()->setCellValue('D' . ($n + 4), $value['openCount']);
            $objectPHPExcel->getActiveSheet()->setCellValue('E' . ($n + 4), $value['creatAmount']);
            $objectPHPExcel->getActiveSheet()->setCellValue('F' . ($n + 4), $value['openAmount']);
            //设置边框
            $currentRowNum = $n + 4;
            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':F' . $currentRowNum)
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':F' . $currentRowNum)
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':F' . $currentRowNum)
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':F' . $currentRowNum)
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':F' . $currentRowNum)
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $n = $n + 1;
        }
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        ob_end_clean();
        ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="' . '红包统计报表-' . date("Y年m月j日") . '.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        return true;
    }

    /**自动根据表格的数据量将数据保存至Excel 表格
     * @param array $data 分为 title：报表的标题 list：数据的数组  column：每列的命名
     * @return bool
     */
    public static function autoOutSheet(array $data)
    {
        $column = count(current($data['list']));//每个数组有多少列
        $ABCinfo = self::ABCinfo();
        $end_column = $ABCinfo[$column];

        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('B1:' . $end_column . '1');
        $objectPHPExcel->getActiveSheet()->setCellValue('B1', $data['title']);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')->getFont()->setSize(24);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B1')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '日期：' . date("Y年m月j日"));
        //表格头的输出
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        for ($i = 0; $i < $column; $i++) {
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($ABCinfo[$i + 1] . '3', $data['column'][$i]);
            $objectPHPExcel->getActiveSheet()->getColumnDimension($ABCinfo[$i + 1])->setWidth(25);
        }
        //设置居中
        $objectPHPExcel->getActiveSheet()->getStyle('B3:' . $end_column . '3')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置边框
        $objectPHPExcel->getActiveSheet()->getStyle('B3:' . $end_column . '3')
            ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B3:' . $end_column . '3')
            ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B3:' . $end_column . '3')
            ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B3:' . $end_column . '3')
            ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        $objectPHPExcel->getActiveSheet()->getStyle('B3:' . $end_column . '3')
            ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        //设置颜色
        $objectPHPExcel->getActiveSheet()->getStyle('B3:' . $end_column . '3')->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
        $n = 0;
        foreach ($data['list'] as $key => $value) {

            $currentRowNum = $n + 4;
            //明细的输出
            foreach ($value as $k=> $v){
                $objectPHPExcel->getActiveSheet()->setCellValue($ABCinfo[$k+1] . $currentRowNum, $v);
            }
            //设置边框
            $objectPHPExcel->getActiveSheet()->getStyle('B' . $currentRowNum . ':' . $end_column . $currentRowNum)
                ->getBorders()->getTop()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B' . $currentRowNum . ':' . $end_column . $currentRowNum)
                ->getBorders()->getLeft()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B' . $currentRowNum . ':' . $end_column . $currentRowNum)
                ->getBorders()->getRight()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B' . $currentRowNum . ':' . $end_column . $currentRowNum)
                ->getBorders()->getBottom()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objectPHPExcel->getActiveSheet()->getStyle('B' . $currentRowNum . ':' . $end_column . $currentRowNum)
                ->getBorders()->getVertical()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $n = $n + 1;
        }


        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        ob_end_clean();
        ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="' . $data['title'].'-' . date("Y年m月j日") . '.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        return true;
    }

    /**
     * @param array $data 需要过滤处理的数据 二维数组
     * @param int $cols 取N列
     * @param int $offset 排除 N 行
     * @param bool|int $must 某列不可为空  0 - index
     * @return array
     */
    public static function handleSheetArray(array $data, $cols = 10, $offset = 1, $must = false)
    {
        $final = [];
        if ($must && $must >= $cols)
            $must = false;
        foreach ($data as $k => $row) {
            if ($k < $offset)
                continue;
            $t = [];
            for ($i = 0; $i < $cols; $i++) {
                if (isset($row[$i]))
                    $t[$i] = trim(strval($row[$i]));
                else $t[$i] = '';
            }
            if (is_array($row) && implode('', $t) && ($must === false || $t[$must])) {
                $final[] = $t;
                continue;
            }
        }
        return $final;
    }


    public static function ABCinfo()
    {
        return [
            1 => 'B',
            2 => 'C',
            3 => 'D',
            4 => 'E',
            5 => 'F',
            6 => 'G',
            7 => 'H',
            8 => 'I',
            9 => 'J',
            10 => 'K',
            11 => 'L',
            12 => 'M',
            13 => 'N',
            14 => 'O',
            15 => 'P',
            16 => 'Q',
            17 => 'R',
            18 => 'S',
            19 => 'T',
            20 => 'U',
            21 => 'V',
            22 => 'W',
            23 => 'X',
            24 => 'Y',
            25 => 'Z',
            26 => 'AA',
            27 => 'AB',
            28 => 'AC',
            29 => 'AD',
            30 => 'AE',
            31 => 'AF',
            32 => 'AG',
            33 => 'AH',
            34 => 'AI',
            35 => 'AJ',
            36 => 'AK',
            37 => 'AL',
            38 => 'AM',
            39 => 'AN',
            40 => 'AO',
            41 => 'AP',
        ];
    }
}