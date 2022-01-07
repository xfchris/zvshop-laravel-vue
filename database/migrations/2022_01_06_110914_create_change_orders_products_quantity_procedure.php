<?php

use App\Constants\AppConstants;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateChangeOrdersProductsQuantityProcedure extends Migration
{
    public function up(): void
    {
        if (in_array(config('database.default'), ['pgsql', 'mysql'])) {
            $this->down();
            DB::unprepared("CREATE PROCEDURE change_orders_products_quantity(_product_id_var INT, _quantity_var INT)
            BEGIN
                DECLARE _order_id INT;
                DECLARE _product_id INT;
                DECLARE _quantity INT;
                DECLARE done INT DEFAULT FALSE;
                DECLARE orders CURSOR FOR
                    SELECT order_id,
                        product_id,
                        quantity
                    FROM   order_product
                        JOIN orders
                            ON orders.id = order_product.order_id
                    WHERE  orders.status = '" . AppConstants::CREATED . "'
                        AND order_product.product_id = _product_id_var;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

                OPEN orders;
                x_loop : LOOP
                    FETCH orders INTO _order_id, _product_id, _quantity;
                        IF _quantity_var > 0 THEN
                            IF _quantity > _quantity_var THEN
                                UPDATE order_product SET quantity = _quantity_var WHERE order_id = _order_id AND product_id = _product_id;
                            END IF;
                        ELSE
                            DELETE FROM order_product WHERE order_id = _order_id AND product_id = _product_id;
                        END IF;

                    IF done THEN
                        LEAVE x_loop;
                    END IF;
                END LOOP;
                CLOSE orders;
            END;");
        }
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS change_orders_products_quantity;');
    }
}
