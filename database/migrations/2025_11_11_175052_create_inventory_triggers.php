<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * AFTER INSERT: suma/resta stock según el tipo de movimiento.
         */
        DB::unprepared("
            CREATE TRIGGER trg_mi_det_ai
            AFTER INSERT ON movimiento_inventario_detalle
            FOR EACH ROW
            BEGIN
                DECLARE v_tipo VARCHAR(10);
                DECLARE v_almacen BIGINT;
                DECLARE v_sign INT;
                DECLARE v_delta DECIMAL(18,6);

                SELECT tipo_movimiento, almacen_id
                  INTO v_tipo, v_almacen
                  FROM movimiento_inventario
                 WHERE id = NEW.movimiento_inventario_id;

                SET v_sign = CASE
                    WHEN v_tipo IN ('entrada','compra') THEN 1
                    WHEN v_tipo = 'salida' THEN -1
                    ELSE 1 -- 'ajuste' respeta el signo de NEW.cantidad (puede ser +/-)
                END;

                SET v_delta = CASE
                    WHEN v_tipo = 'ajuste' THEN NEW.cantidad
                    ELSE (NEW.cantidad * v_sign)
                END;

                INSERT INTO existencia (almacen_id, material_id, cantidad_disponible, created_at, updated_at)
                VALUES (v_almacen, NEW.material_id, v_delta, NOW(), NOW())
                ON DUPLICATE KEY UPDATE cantidad_disponible = cantidad_disponible + v_delta, updated_at = NOW();
            END
        ");

        /**
         * AFTER UPDATE: ajusta stock considerando cambio de cantidades
         * y/o cambio de material o incluso de movimiento (almacén/tipo).
         */
        DB::unprepared("
            CREATE TRIGGER trg_mi_det_au
            AFTER UPDATE ON movimiento_inventario_detalle
            FOR EACH ROW
            BEGIN
                DECLARE v_tipo_old VARCHAR(10);
                DECLARE v_almacen_old BIGINT;
                DECLARE v_sign_old INT;
                DECLARE v_delta_old DECIMAL(18,6);

                DECLARE v_tipo_new VARCHAR(10);
                DECLARE v_almacen_new BIGINT;
                DECLARE v_sign_new INT;
                DECLARE v_delta_new DECIMAL(18,6);

                -- Datos OLD
                SELECT tipo_movimiento, almacen_id
                  INTO v_tipo_old, v_almacen_old
                  FROM movimiento_inventario
                 WHERE id = OLD.movimiento_inventario_id;

                SET v_sign_old = CASE
                    WHEN v_tipo_old IN ('entrada','compra') THEN 1
                    WHEN v_tipo_old = 'salida' THEN -1
                    ELSE 1
                END;

                SET v_delta_old = CASE
                    WHEN v_tipo_old = 'ajuste' THEN OLD.cantidad
                    ELSE (OLD.cantidad * v_sign_old)
                END;

                -- Revertir efecto OLD
                INSERT INTO existencia (almacen_id, material_id, cantidad_disponible, created_at, updated_at)
                VALUES (v_almacen_old, OLD.material_id, -v_delta_old, NOW(), NOW())
                ON DUPLICATE KEY UPDATE cantidad_disponible = cantidad_disponible - v_delta_old, updated_at = NOW();

                -- Datos NEW
                SELECT tipo_movimiento, almacen_id
                  INTO v_tipo_new, v_almacen_new
                  FROM movimiento_inventario
                 WHERE id = NEW.movimiento_inventario_id;

                SET v_sign_new = CASE
                    WHEN v_tipo_new IN ('entrada','compra') THEN 1
                    WHEN v_tipo_new = 'salida' THEN -1
                    ELSE 1
                END;

                SET v_delta_new = CASE
                    WHEN v_tipo_new = 'ajuste' THEN NEW.cantidad
                    ELSE (NEW.cantidad * v_sign_new)
                END;

                -- Aplicar efecto NEW
                INSERT INTO existencia (almacen_id, material_id, cantidad_disponible, created_at, updated_at)
                VALUES (v_almacen_new, NEW.material_id, v_delta_new, NOW(), NOW())
                ON DUPLICATE KEY UPDATE cantidad_disponible = cantidad_disponible + v_delta_new, updated_at = NOW();
            END
        ");

        /**
         * AFTER DELETE: revierte el efecto del detalle eliminado.
         */
        DB::unprepared("
            CREATE TRIGGER trg_mi_det_ad
            AFTER DELETE ON movimiento_inventario_detalle
            FOR EACH ROW
            BEGIN
                DECLARE v_tipo VARCHAR(10);
                DECLARE v_almacen BIGINT;
                DECLARE v_sign INT;
                DECLARE v_delta DECIMAL(18,6);

                SELECT tipo_movimiento, almacen_id
                  INTO v_tipo, v_almacen
                  FROM movimiento_inventario
                 WHERE id = OLD.movimiento_inventario_id;

                SET v_sign = CASE
                    WHEN v_tipo IN ('entrada','compra') THEN 1
                    WHEN v_tipo = 'salida' THEN -1
                    ELSE 1
                END;

                SET v_delta = CASE
                    WHEN v_tipo = 'ajuste' THEN OLD.cantidad
                    ELSE (OLD.cantidad * v_sign)
                END;

                -- Al borrar, hay que revertir el efecto (restar lo sumado o sumar lo restado)
                INSERT INTO existencia (almacen_id, material_id, cantidad_disponible, created_at, updated_at)
                VALUES (v_almacen, OLD.material_id, -v_delta, NOW(), NOW())
                ON DUPLICATE KEY UPDATE cantidad_disponible = cantidad_disponible - v_delta, updated_at = NOW();
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_mi_det_ai");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_mi_det_au");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_mi_det_ad");
    }
};
