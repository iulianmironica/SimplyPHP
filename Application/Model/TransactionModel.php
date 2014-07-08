<?php

/**
 * Description of TransactionModel
 *
 * @author Iulian Mironica
 */
class TransactionModel extends Model {

    /**
     * @param type $productIds
     * @param type $storeIds
     * @param type $transactionsLimit
     * @param type $transactionItemsLimit
     * @return array
     */
    public function getTransactions($productIds = array(), $storeIds = null, $transactionsLimit = ApplicationSettings::MAX_TRANSACTIONS, $transactionItemsLimit = ApplicationSettings::MAX_PRODUCTS) {
        // Get the transactions list where product appears

        $productIds = implode(",", $productIds);
        $storeIdsCondition = empty($storeIds) ? '' : " AND t.storeId IN (" . implode(",", $storeIds) . ")";

        // Get distinct transactions where products are present
        $transactionList = $this->query("
                SELECT DISTINCT t.timeId
                FROM `transaction` t
                WHERE t.productId IN ({$productIds})
                {$storeIdsCondition}
                ORDER BY t.timeId
                LIMIT {$transactionsLimit}
                ");

//        var_dump($transactionList);
//        exit();

        $j = 0;
        $transactionProducts = array();
        foreach ($transactionList as $transaction) {
            // $transactionProducts[$transaction['timeId']] = array();
            $transactionProducts[$j] = array();
            $productsFromTransaction = $this->getProductsFromTransaction($transaction['timeId'], $storeIds, $transactionItemsLimit);
            $i = 0;
            foreach ($productsFromTransaction as $product) {
                $transactionProducts[$j][$i] = (int) $product['productId'];
                $i++;
            }
            $j++;
        }

//        var_dump($transactionProducts);
//        exit();
        return $transactionProducts;
    }

    /**
     * @param type $productIds
     * @param type $storeIds
     * @param type $transactionsLimit
     * @param type $transactionItemsLimit
     * @return array
     */
    public function getTransactionsOld($productIds = array(), $storeIds = null, $transactionsLimit = ApplicationSettings::MAX_TRANSACTIONS, $transactionItemsLimit = ApplicationSettings::MAX_PRODUCTS) {
        // Get the transactions list where product appears

        $productIds = implode(",", $productIds);
        $storeIdsCondition = empty($storeIds) ? '' : " AND t.storeId IN (" . implode(",", $storeIds) . ")";

        // Get distinct transactions where products are present
        $transactionList = $this->query("
                SELECT DISTINCT t.timeId
                FROM `transaction` t
                WHERE t.productId IN ({$productIds})
                {$storeIdsCondition}
                ORDER BY t.timeId
                LIMIT {$transactionsLimit}
                ");

//        var_dump($transactionList);
//        exit();

        $transactionIds = array();
        foreach ($transactionList as $transaction) {
            if (!in_array($transaction, $transactionIds)) {
                $transactionIds[] = $transaction['timeId'];
            }
        }

//          var_dump($transactionIds);
        // Now get all the products within those transactions
        $transactionProducts = $this->getTransactionProducts($transactionIds, $storeIds, $transactionItemsLimit);

//        var_dump($transactionProducts);
//        exit();

        $transactionProductIds = array();

        foreach ($transactionProducts as $product) {
            if (!isset($transactionProductIds[$product['timeId']])) {
                $transactionProductIds[$product['timeId']][] = (int) $product['productId'];
            } else {
                if (!in_array($product['productId'], $transactionProductIds[$product['timeId']])) {
                    $transactionProductIds[$product['timeId']][] = (int) $product['productId'];
                }
            }
        }

//        var_dump($transactionProductIds);
//        exit();

        $data = array();
        foreach ($transactionProductIds as $key => $val) {
            $data[] = $val;
        }

//        var_dump($data);
//        exit();

        return $data;
    }

    /**
     * @param type $transactionIds
     * @param array/null $storeIds
     * @param type $limit
     * @return type
     */
    public function getTransactionProducts($transactionIds = array(), $storeIds = null, $limit = 50) {
        $transactionIds = implode(",", $transactionIds);
        $storeIdsCondition = empty($storeIds) ? '' : " AND t.storeId IN (" . implode(",", $storeIds) . ")";

        return $this->query("
                SELECT t.productId, t.timeId
                FROM `transaction` t
                WHERE t.timeId IN ({$transactionIds})
                {$storeIdsCondition}
                ORDER BY t.timeId
                LIMIT {$limit}
                ");
    }

    /**
     * @param type $transactionId
     * @param type $storeIds
     * @param type $limit
     * @return type
     */
    public function getProductsFromTransaction($transactionId, $storeIds = null, $limit = 50) {
        $storeIdsCondition = empty($storeIds) ? '' : " AND t.storeId IN (" . implode(",", $storeIds) . ")";

        return $this->query("
                -- SELECT DISTINCT t.productId, t.timeId
                SELECT DISTINCT t.productId
                FROM `transaction` t
                WHERE t.timeId = {$transactionId}
                {$storeIdsCondition}
                -- ORDER BY t.productId
                -- ORDER BY RAND()
                LIMIT {$limit}
                ");
    }

    /**
     * @param type $productIds
     * @param type $storeIds
     * @return type
     */
    public function countAllTransactions($productIds = array(1011, 568), $storeIds = null) {
        // Get the transactions list where product appears

        $productIds = implode(",", $productIds);
        $storeIdsCondition = empty($storeIds) ? '' : " AND t.storeId IN (" . implode(",", $storeIds) . ")";

        // Count distinct transactions where products are present
        $transactionCount = $this->query("
                SELECT DISTINCT COUNT(t.timeId) as total
                FROM `transaction` t
                WHERE t.productId IN ({$productIds})
                {$storeIdsCondition}
                ORDER BY t.timeId
                ");

//        var_dump($transactionCount);
//        exit();

        return $transactionCount[0]['total'];
    }

}
