<?php
class Excel {
    private $path = './upfile/Excel/';
    
    public function setPath($path) {
        $this->path = $path;
    }
    public function getPath($path) {
        return $this->path;
    }
    //
    public function storeExcel() {
        echo "hello world";
        if (! empty ( $_FILES ['file_stu'] ['name'] )) {
            $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
            $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
            $file_type = $file_types [count ( $file_types ) - 1];
            /*判别是不是.xls文件，判别是不是excel文件*/
            if (strtolower ( $file_type ) != "xlsx")
            {
                $this->error ( '不是Excel文件，重新上传' );
            }
            /*设置上传路径*/
          //  $savePath = SITE_PATH . './upfile/Excel/';
            /*以时间来命名上传的文件*/
            $str = date ( 'Ymdhis' );
            $filename = $str . "." . $file_type;
            /*是否上传成功*/
            if (! copy ( $tmp_file, $this->path . $filename ))
            {
                $this->error ( '上传失败' );
            }
            return $filename;
        }
    }
   
    
    //提供文件的路径和文件名，将Excel表格里的数据导出到数组中去
    public function importExcel() {
        if (! empty ( $_FILES ['file_stu'] ['name'] )) {
            $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
            $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
            $file_type = $file_types [count ( $file_types ) - 1];
            /*判别是不是.xls文件，判别是不是excel文件*/
            if (strtolower ( $file_type ) != "xlsx")
            {
                $this->error ( '不是Excel文件，重新上传' );
            }
            /*设置上传路径*/
            //  $savePath = SITE_PATH . './upfile/Excel/';
            /*以时间来命名上传的文件*/
            $str = date ( 'Ymdhis' );
            $filename = $str . "." . $file_type;
            /*是否上传成功*/
            if (! copy ( $tmp_file, $this->path . $filename ))
            {
                $this->error ( '上传失败' );
            }
            
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objPHPExcel = PHPExcel_IOFactory::load($this->path . $filename);
        
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); //取得行数
            $highestColumn = $sheet->getHighestColumn(); // 取得列数
            $result = array();
            for ($j = 1; $j <= $highestRow; $j++) 
                for ($k = 'A'; $k <= $highestColumn; $k++) { 
                $str = iconv('GBK', 'UTF-8', $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue());  //如果中文乱码需要使用这段话
                   // $str = $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue();
                    $result[$j][$k] = $str;     
                }
            return $result;
        }
        
        
    }
    
    //将数组中的数据导入到EXCEl表格中去
    public function exportExcel($input = array(),$excelName){
        $objPHPExcel = new PHPExcel();       
        $objPHPExcel->setActiveSheetIndex(0);
        
        //将数组中的数据赋值给表
        foreach ($input as $keyRow => $Row)
            foreach ($Row as $keyCol => $value) {
               // $value = iconv('gb2312', 'utf-8', $value);
                $objPHPExcel->getActiveSheet()->setCellValue($keyCol.$keyRow,$value);
            }
        
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $datetime = date('Y-m-d', time());
        if (preg_match("/MSIE/", $ua)) {
            $excelName = urlencode($excelName); //IE浏览器可以出现的问题
        }
        
        // excel导出
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$excelName.'.xlsx"');  
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
        $objWriter->save('php://output');
    }
}