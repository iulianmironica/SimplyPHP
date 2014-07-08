<?php

/**
 * Description of ProductModel
 *
 * @author Iulian Mironica
 */
class ProductModel extends Model {

    // Hardcoded product data
    public static $data = array(
        'Canned Foods' => array(
            0 => array(
                'id' => '1142',
                'productId' => '17',
                'classId' => '62',
                'product' => 'Blue Label Creamed Corn',
                'price' => '1.11',
                'category' => 'Vegetables',
                'department' => 'Canned Foods'
            ),
            1 => array(
                'id' => '4632',
                'productId' => '19',
                'classId' => '58',
                'product' => 'Blue Label Chicken Soup',
                'price' => '3.40',
                'category' => 'Canned Soup',
                'department' => 'Canned Foods'
            ),
            2 => array(
                'id' => '9',
                'productId' => '22',
                'classId' => '62',
                'product' => 'Blue Label Canned Tomatos',
                'price' => '1.38',
                'category' => 'Vegetables',
                'department' => 'Canned Foods'
            )
        ),
        'Dairy' => array(
            0 => array(
                'id' => '1264',
                'productId' => '45',
                'classId' => '14',
                'product' => 'Club Sour Cream',
                'price' => '6.56',
                'category' => 'Dairy',
                'department' => 'Dairy'
            ),
            1 => array(
                'id' => '964',
                'productId' => '47',
                'classId' => '11',
                'product' => 'Club Muenster Cheese',
                'price' => '6.10',
                'category' => 'Dairy',
                'department' => 'Dairy'
            ),
            2 => array(
                'id' => '166',
                'productId' => '53',
                'classId' => '11',
                'product' => 'Club Sharp Cheddar Cheese',
                'price' => '2.33',
                'category' => 'Dairy',
                'department' => 'Dairy'
            )
        ),
        'Beverages' => array(
            0 => array(
                'id' => '262',
                'productId' => '2',
                'classId' => '52',
                'product' => 'Washington Mango Drink',
                'price' => '6.84',
                'category' => 'Drinks',
                'department' => 'Beverages'
            ),
            1 => array(
                'id' => '1129',
                'productId' => '5',
                'classId' => '19',
                'product' => 'Washington Diet Soda',
                'price' => '6.42',
                'category' => 'Carbonated Beverages',
                'department' => 'Beverages'
            ),
            2 => array(
                'id' => '958',
                'productId' => '8',
                'classId' => '30',
                'product' => 'Washington Orange Juice',
                'price' => '8.14',
                'category' => 'Pure Juice Beverages',
                'department' => 'Beverages'
            )
        ),
        'Deli' => array(
            0 => array(
                'id' => '1529',
                'productId' => '63',
                'classId' => '81',
                'product' => 'Red Spade Chicken Hot Dogs',
                'price' => '6.58',
                'category' => 'Meat',
                'department' => 'Deli'
            ),
            1 => array(
                'id' => '728',
                'productId' => '66',
                'classId' => '91',
                'product' => 'Red Spade Low Fat Bologna',
                'price' => '9.64',
                'category' => 'Meat',
                'department' => 'Deli'
            ),
            2 => array(
                'id' => '2037',
                'productId' => '70',
                'classId' => '16',
                'product' => 'Red Spade Cole Slaw',
                'price' => '5.75',
                'category' => 'Side Dishes',
                'department' => 'Deli'
            )
        )
    );

    public function getProducts() {
        return self::$data;
    }

    /**
     * @param array $basket
     * @return array with ids or null
     */
    public static function getProductsIdsFromBasket($basket) {
        $basketItemIds = array();
        if (is_array($basket)) {
            foreach ($basket as $item) {
                $basketItemIds[] = $item['productId'];
            }
            return $basketItemIds;
        }
        return null;
    }

    public function getItemsForAutocomplete($keyword, $limit = 30) {

        /* MySQL Query example
         * ----------------
          $limitCondition = 'LIMIT ' . (empty($limit) ? 100 : $limit);

          $sql = "SELECT DISTINCT productId, classId, product as name, product, price
          FROM transaction
          WHERE product LIKE '%{$keyword}%'
          HAVING COUNT(*) > 2
          {$limitCondition}
          ";
          return $this->query($sql);
         */

        return self::$data['Deli'];
    }

}
