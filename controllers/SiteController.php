<?php
/**
 * Created by PhpStorm.
 * User: Avenax
 * Date: 08.11.2018
 * Time: 16:28
 */

class SiteController {

    private static $inputFileName = ROOT . '/xlsx/base.xlsx';

    /**
     * Редактировать
     * @param $id
     * @return bool
     */
    public static function actionEdit($id) {
        $value = GuestBook::checkOwner($id);

        if ($value == false) {
            BaseClass::redirect('/');
        }

        if (filter_has_var(INPUT_POST, 'submit')) {

            if (empty($_POST['message'])) {
                $_SESSION['message'] = 'Сообщение не может быть пустым';
                BaseClass::redirect('/edit/' . $id);
            } else {
                GuestBook::setEdit($_POST['message'], $id);
            }
        }

        $title = 'Редактировать';
        require_once(ROOT . '/view/head.html');
        require_once(ROOT . '/view/edit.html');
        require_once(ROOT . '/view/foot.html');
        return true;
    }

    /**
     * Главная страницы
     * @return bool
     */
    public function actionIndex() {
        $err = [];

        if (filter_has_var(INPUT_POST, 'submit')) {
            if (empty($_POST['message']) && empty($_FILES['file']['name'])) {
                $err[] = 'Сообщение не может быть пустым!';
            } else {
                $message = $_POST['message'] ?? null;
                $file = !empty($_FILES['file']['name']) ? $_FILES['file'] : null;


                $upload = GuestBook::uploadFile($file);

                if (is_array($upload)) {
                    $err[] = $upload;
                }
            }
            if (empty($err)) {
                GuestBook::setMessage($message, $upload);
            } else {
                $_SESSION['message'] = $err;
            }
            BaseClass::redirect('/');
        }

        $books = GuestBook::getListBook();
        require_once(ROOT . '/view/head.html');
        require_once(ROOT . '/view/index.html');
        require_once(ROOT . '/view/foot.html');
        return true;
    }

    /**
     * Загружаем в файл
     * @throws Exception
     */
    public function actionLoad() {
        if (GuestBook::$idUser != $_SESSION['user']) {
            $_SESSION['message'] = ['Доступ запрещён!'];
            BaseClass::redirect('/');
        }

        $data = GuestBook::getAllBook();

        require_once ROOT . '/app/PHPExcel/Classes/PHPExcel/IOFactory.php';
        $objPHPExcel = new PHPExcel();
        $objDrawing  = new PHPExcel_Worksheet_Drawing();
        $page = $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $count = 1;
        foreach ($data as $item => $value) {
            $page->setCellValue('A' . $count, $value['id_user']);

            $page->setCellValue('B' . $count, $value['message']);
            $page->setCellValue('C' . $count, $value['rating']);

            $page->setCellValue('D' . $count, date('Y:m:d-H:i', $value['time_message']));
            if (!is_null($value['path_foto'])) {
                $objDrawing->setPath(trim($value['path_foto']));
                $objDrawing->setCoordinates('E' . $count);
                $objDrawing->setWidth(100);
                $objDrawing->setHeight(100);
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
            }
            $count ++;
        }

        $page->setTitle("БД Гостевой книги");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(self::$inputFileName);
        $_SESSION['message'] = ['Успешно выгрузили список отзывов!'];
        BaseClass::redirect('/');
    }
}