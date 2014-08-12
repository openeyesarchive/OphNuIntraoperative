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

/**
 * This is the model class for table "et_ophnuintraoperative_handoff".
 *
 * The followings are the available columns in table:
 * @property string $id
 * @property integer $event_id
 * @property integer $wristband_verified
 * @property integer $allergies_verified
 * @property integer $hand_off_from_id
 * @property integer $hand_off_to_id
 * @property integer $anesthesia_type_id
 * @property integer $nonoperative_eye_protected_id
 * @property integer $tape_or_shield_id
 *
 * The followings are the available model relations:
 *
 * @property ElementType $element_type
 * @property EventType $eventType
 * @property Event $event
 * @property User $user
 * @property User $usermodified
 * @property OphNuIntraoperative_Handoff_Identifiers $two_identifierss
 * @property OphNuIntraoperative_Handoff_HandOffFrom $hand_off_from
 * @property Address $hand_off_to
 * @property AnaestheticType $anesthesia_type
 * @property OphNuIntraoperative_Handoff_NonoperativeEyeProtected $nonoperative_eye_protected
 * @property OphNuIntraoperative_Handoff_TapeOrShield $tape_or_shield
 */

class Element_OphNuIntraoperative_Operation extends  BaseEventTypeElement
{
	public $booking_event_id;

	/**
	 * Returns the static model of the specified AR class.
	 * @return the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'et_ophnuintraoperative_operation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('eye_id, booking_event_id', 'safe'),
			array('eye_id', 'required'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'element_type' => array(self::HAS_ONE, 'ElementType', 'id','on' => "element_type.class_name='".get_class($this)."'"),
			'eventType' => array(self::BELONGS_TO, 'EventType', 'event_type_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
			'usermodified' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
			'eye' => array(self::BELONGS_TO, 'Eye', 'eye_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'eye_id' => 'Eye',
		);
	}

	public function afterFind()
	{
		$this->booking_event_id = Element_OphNuIntraoperative_PatientId::model()->find('event_id=?',array($this->event_id))->booking_event_id;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('event_id', $this->event_id, true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria' => $criteria,
		));
	}

	public function afterValidate()
	{
		$criteria = new CDbCriteria;

		$criteria->addCondition('booking_event_id=:eid');
		$criteria->params['eid'] = $this->booking_event_id;

		$criteria->addCondition('event.deleted=0 and episode.deleted=0');

		if ($this->id) {
			$criteria->addCondition('t.id != :id');
			$criteria->params[':id'] = $this->id;
		}

		foreach (Element_OphNuIntraoperative_PatientId::model()->with(array('event' => array('with' => 'episode')))->findAll($criteria) as $patient_id) {
			if (Element_OphNuIntraoperative_Operation::model()->find('event_id=? and eye_id=?',array($patient_id->event_id,$this->eye_id))) {
				$this->addError('eye_id','There is already an intra-operative record for this operation and the '.strtolower($this->eye->name).' eye');
			}
		}

		return parent::afterValidate();
	}
}
?>
