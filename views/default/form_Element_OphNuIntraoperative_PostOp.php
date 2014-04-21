<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>

<section class="element <?php echo $element->elementType->class_name?>"
	data-element-type-id="<?php echo $element->elementType->id?>"
	data-element-type-class="<?php echo $element->elementType->class_name?>"
	data-element-type-name="<?php echo $element->elementType->name?>"
	data-element-display-order="<?php echo $element->elementType->display_order?>">
	<header class="element-header">
		<h3 class="element-title"><?php echo $element->elementType->name; ?></h3>
	</header>

	<div class="element-fields">
		<?php echo $form->radioButtons($element, 'specimin_collected_id', CHtml::listData(OphNuIntraoperative_PostOp_SpeciminCollected::model()->findAll(array('order'=>'display_order asc')),'id','name'), null, false, false, false, false, array('class' => 'linked-fields', 'data-linked-fields' => 'specimin_comments', 'data-linked-value' => 'Yes'), array('label' => 3, 'field' => 4))?>
		<?php echo $form->textArea($element, 'specimin_comments', array(), !$element->specimin_collected || $element->specimin_collected->name != 'Yes', array(), array('label' => 3, 'field' => 4))?>
		<?php echo $form->radioBoolean($element, 'dressing_used', array('class' => 'linked-fields', 'data-linked-fields' => 'MultiSelect_dressing_items', 'data-linked-value' => 'Yes'), array('label' => 3, 'field' => 4))?>
		<?php echo $form->multiSelectList($element, 'MultiSelect_dressing_items', 'dressing_itemss', 'ophnuintraoperative_postop_dressing_items_id', CHtml::listData(OphNuIntraoperative_PostOp_DressingItems::model()->findAll(array('order'=>'display_order asc')),'id','name'), array(), array('empty' => '- Please select -', 'label' => 'Dressing items','class' => 'linked-fields', 'data-linked-fields' => 'dressing_other', 'data-linked-value' => 'Other (please specify)'), !$element->dressing_used, false, null, false, false, array('label' => 3, 'field' => 4))?>
		<?php echo $form->textArea($element, 'dressing_other', array(), !$element->hasMultiSelectValue('dressing_itemss','Other (please specify)'), array(), array('label' => 3, 'field' => 4))?>
		<?php $form->widget('application.widgets.ProcedureSelection',array(
			'element' => $element,
			'durations' => false,
			'relation' => 'procedures',
			'procedureListPosition' => 'vertical',
			'layoutColumns' => array(
				'label' => 3,
				'field' => 4,
				'procedures' => 6,
			)
		))?>
		<?php echo $form->dropDownList($element, 'circulating_nurse_id', CHtml::listData(User::model()->findAll(array('order'=> 'first_name asc,last_name asc')),'id','fullName'),array('empty'=>'- Please select -'),false,array('label'=>3,'field'=>4))?>
		<?php echo $form->dropDownList($element, 'scrub_nurse_id', CHtml::listData(User::model()->findAll(array('order'=> 'first_name asc,last_name asc')),'id','fullName'),array('empty'=>'- Please select -'),false,array('label'=>3,'field'=>4))?>
	</div>
</section>
