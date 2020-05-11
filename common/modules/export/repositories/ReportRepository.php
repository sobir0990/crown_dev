<?php
/**
 * Created by PhpStorm.
 * User: OKS
 * Date: 30.11.2019
 * Time: 21:59
 */

namespace common\modules\export\repositories;

use common\models\Categories;
use common\models\Models;
use common\models\Product;
use common\models\User;
use common\models\UserTokens;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use common\components\ExcelHelper;
use Yii;
use yii\helpers\ArrayHelper;

class ReportRepository
{

    /**
     * @param null $start
     * @param null $end
     * @return bool|string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function getOrderReport($start = null, $end = null)
    {
        $token = User::getByToken();
        $auth = Yii::$app->authManager;
        $ids = $auth->getUserIdsByRole(User::ROLE_ADMIN);
        $company = $auth->getUserIdsByRole(User::ROLE_COMPANY);

        $userToken = User::find()
            ->andWhere(['id' => $token->id])
            ->andWhere(['id' => $ids])
            ->orWhere(['id' => $company])
            ->one();

        if (!is_object($userToken)) {
            throw new \DomainException("Access Denied");
        }

        $start_data = $this->getStartData($start);
        $end_data = $this->getEndData($end);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        ExcelHelper::border($sheet, "A1:Z1");
        ExcelHelper::align($sheet, "A1:Z1");
        ExcelHelper::text($sheet, "A1:Z1");
        $spreadsheet->getActiveSheet()->setAutoFilter('A1:F20');

        $sheet->setCellValue('A1', "No");
        $sheet->setCellValue('B1', 'Семейство');
        $sheet->setCellValue('C1', 'Наименования');
        $sheet->setCellValue('D1', 'Остаток');
        $sheet->setCellValue('D2', 'на  ' . $start_data);
        $sheet->setCellValue('E1', 'Цена');
        $sheet->setCellValue('F1', 'Сумма остатка');
        $sheet->setCellValue('F2', 'на  ' . $start_data);
        $sheet->setCellValue('G1', 'Итого прихода');
        $sheet->setCellValue('H1', 'Сумма обшего прихода');
        $sheet->setCellValue('I1', 'общий количество шт остаткаа');
        $sheet->setCellValue('J1', 'общий сумма остатка');
        $sheet->getColumnDimension('B')->setWidth(14);
        $sheet->getColumnDimension('C')->setWidth(14);
        $i = 0;
        $categories = Categories::find()->all();
        foreach ($categories as $category) {
            $models = Models::find()->andWhere(['category_id' => $category->id])->all();
            foreach ($models as $model) {
                $product = $this->getProduct($model, $start, $end);
                $prihod = Product::find()
                    ->select('SUM(count) as count')
                    ->andWhere(['models_id' => $model->id])
                    ->andWhere(['coming_outgo' => Product::COMING])
                    ->asArray()
                    ->one();

                $coming = Product::find()
                    ->andWhere(['models_id' => $model->id])
                    ->andWhere(['coming_outgo' => Product::COMING])
                    ->count();

                $i++;
                $remainderData = $this->getRemainderData($product, $start, $end);
                $remainder = $this->getRemainder($product);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A' . (2 + $i), 0 + $i);
                $sheet->setCellValue('B' . (2 + $i), $category->name);
                $sheet->setCellValue('C' . (2 + $i), $model->name);
                $sheet->setCellValue('D' . (2 + $i), $remainderData);
                $sheet->setCellValue('E' . (2 + $i), $model->price);
                $sheet->setCellValue('F' . (2 + $i), $model->price * $remainderData);
                $sheet->setCellValue('G' . (2 + $i), $prihod['count']);
                $sheet->setCellValue('H' . (2 + $i), $prihod['count'] * $product->price);
                $sheet->setCellValue('I' . (2 + $i), $remainder);
                $sheet->setCellValue('J' . (2 + $i), $remainder * $product->price);

                $array = ArrayHelper::map(Product::find()->andWhere(['models_id' => $model->id])->all(), 'id', 'count');
                $cord = ExcelHelper::getAlphaIncDecPosition("K", $coming) . '1';
                $sheet->setCellValue($cord, 'Приход');
                $sheet->fromArray([$array], NULL, 'K' . (2 + $i));

            }
        }

        $time = time();
        return ExcelHelper::export($spreadsheet, "report.xls");

    }


    public function getProduct($model, $start, $end)
    {
        $products = Product::find()
            ->andWhere(['models_id' => $model->id]);

        if ($start !== null && $end !== null) {
            $products->andWhere(['between', 'created_at', $start, $end]);
        }
        $product = $products->one();

        return $product;
    }


    public function getStartData($start)
    {
        $start_data = new \DateTime();
        $start_data->setTimestamp($start);
        $start_data = $start_data->format('d-M') . "\n";

        return $start_data;
    }

    public function getEndData($end)
    {
        $end_data = new \DateTime();
        $end_data->setTimestamp($end);
        $end_data = $end_data->format('d-M') . "\n";
        return $end_data;
    }

    public function getRemainderData($product, $start, $end)
    {

        $coming = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['models_id' => $product->models_id])
            ->andWhere(['between', 'created_at', $start, $end])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();


        $outgo = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['models_id' => $product->models_id])
            ->andWhere(['between', 'created_at', $start, $end])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();


        $productCount = $coming['count'] - $outgo['count'];

        return $productCount;
    }

    public function getRemainder($product)
    {
        $coming = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['models_id' => $product->models_id])
            ->andWhere(['coming_outgo' => Product::COMING])
            ->asArray()
            ->one();


        $outgo = Product::find()
            ->select("SUM(count) as count")
            ->andWhere(['not', ['status' => Product::STATUS_REJECTED]])
            ->andWhere(['models_id' => $product->models_id])
            ->andWhere(['coming_outgo' => Product::OUTGO])
            ->asArray()
            ->one();

        $productCount = $coming['count'] - $outgo['count'];

        return $productCount;
    }
}
