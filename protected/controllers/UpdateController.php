<?php
class UpdateController extends BmsBaseController
{
    public function actionChangeSpareReplacement() {
        try{
            $sql = "ALTER TABLE `spare_replacement`
ADD COLUMN `quantity`  int(11) UNSIGNED NOT NULL DEFAULT 1 AFTER `unit_price`;";
            Yii::app()->dbAdmin->createCommand($sql)->execute();
            $this->renderJsonBms(true, 'OK', 'OK');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }

    public function actionAlterViewSpareReplacement() {
        try{
            $sql = "ALTER 
ALGORITHM=UNDEFINED 
DEFINER=`root`@`localhost` 
SQL SECURITY DEFINER 
VIEW `view_spare_replacement` AS 
SELECT
spare_replacement.id AS id,
spare_replacement.car_id AS car_id,
car.vin AS vin,
spare_replacement.node_trace_id AS node_trace_id,
spare_replacement.component_id AS component_id,
component.`name` AS component_name,
component.`code` AS component_code,
spare_replacement.provider_id AS provider_id,
provider.display_name AS provider_name,
provider.`code` AS provider_code,
provider.factory_code AS factory_code,
spare_replacement.bar_code AS bar_code,
spare_replacement.is_collateral AS is_collateral,
spare_replacement.unit_price AS unit_price,
spare_replacement.duty_department_id AS duty_department_id,
duty_department.`name` AS duty_department_name,
spare_replacement.replace_time AS replace_time,
spare_replacement.fault_id AS fault_id,
spare_replacement.fault_component_name AS fault_component_name,
spare_replacement.fault_mode AS fault_mode,
spare_replacement.duty_area AS duty_area,
spare_replacement.`handler` AS `handler`,
spare_replacement.treatment AS treatment,
component.sap_code AS sap_code,
spare_replacement.series AS series,
spare_replacement.assembly_line AS assembly_line,
spare_replacement.quantity
from ((((`spare_replacement` left join `provider` on((`spare_replacement`.`provider_id` = `provider`.`id`))) join `component` on((`spare_replacement`.`component_id` = `component`.`id`))) join `duty_department` on((`spare_replacement`.`duty_department_id` = `duty_department`.`id`))) left join `car` on((`spare_replacement`.`car_id` = `car`.`id`))) ;
";
            Yii::app()->dbAdmin->createCommand($sql)->execute();
            $this->renderJsonBms(true, 'OK', 'OK');
        } catch(Exception $e) {
            $this->renderJsonBms(false , $e->getMessage());
        }
    }
}