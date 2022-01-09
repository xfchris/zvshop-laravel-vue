<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateChangeQuantityOfProductsProcedure extends Migration
{
    public function up(): void
    {
        if (in_array(config('database.default'), ['pgsql', 'mysql'])) {
            $this->down();
            DB::unprepared('CREATE PROCEDURE change_quantity_of_products(_id_order INT, isSum BOOL)
            BEGIN
                DECLARE _product_id INT;
                DECLARE _order_quantity INT;
                DECLARE _product_quantity INT;
                   DECLARE _new_quantity INT;
                DECLARE done INT DEFAULT FALSE;

                DECLARE products_order CURSOR FOR
                    SELECT product_id,
                           order_product.quantity AS order_quantity,
                           products.quantity      AS product_quantity
                    FROM   order_product
                           JOIN products
                             ON products.id = order_product.product_id
                    WHERE  order_product.order_id = _id_order;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

                OPEN products_order;
                x_loop : LOOP
                    FETCH products_order INTO _product_id, _order_quantity, _product_quantity;
                         IF isSum THEN
                             SET _new_quantity = _order_quantity + _product_quantity;
                         ELSE
                            SET _new_quantity = (_product_quantity - _order_quantity);
                            CALL change_orders_products_quantity(_product_id, _new_quantity);
                         END IF;
                         UPDATE products SET quantity = _new_quantity WHERE id = _product_id;

                    IF done THEN
                        LEAVE x_loop;
                    END IF;
                END LOOP;
                CLOSE products_order;
            END;');
        }
    }

    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS change_quantity_of_products;');
    }
}
