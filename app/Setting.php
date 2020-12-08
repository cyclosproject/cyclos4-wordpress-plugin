<?php
/**
 * Setting class for handling a plugin setting.
 *
 * @package Cyclos
 */

namespace Cyclos;

/**
 * The Setting class.
 */
class Setting {

	/**
	 * Section of the setting.
	 *
	 * @var string $section Section of the setting.
	 */
	protected $section;

	/**
	 * Label of the setting.
	 *
	 * @var string $label Label of the setting.
	 */
	protected $label;

	/**
	 * Description of the setting.
	 *
	 * @var string $description Description of the setting.
	 */
	protected $description;

	/**
	 * Type of the setting.
	 *
	 * @var string $type Type of the setting.
	 */
	protected $type;

	/**
	 * Default value of the setting.
	 *
	 * @var string $default Default value of the setting.
	 */
	protected $default;

	/**
	 * Example value of the setting.
	 *
	 * @var string $example Example value of the setting.
	 */
	protected $example;

	/**
	 * Indicates whether the setting is required.
	 *
	 * @var bool $is_required Whether the setting is required.
	 */
	protected $is_required;

	/**
	 * Constructor.
	 *
	 * @param string $section       The section of the setting.
	 * @param string $label       The label of the setting.
	 * @param string $type        (optional) The type of the setting. Defaults to 'text'.
	 * @param bool   $is_required (optional) Whether the setting is required. Defaults to true.
	 * @param string $default     (optional) Default value of the setting. Defaults to null.
	 * @param string $description (optional) The description of the setting. Defaults to null.
	 * @param string $example     (optional) Example value of the setting. Defaults to null.
	 */
	public function __construct( string $section, string $label, string $type = 'text', bool $is_required = true, $default = null, $description = null, $example = null ) {
		$this->section     = $section;
		$this->label       = $label;
		$this->type        = $type;
		$this->is_required = $is_required;
		$this->default     = $default;
		$this->description = $description;
		$this->example     = $example;
	}

	/**
	 * Returns the setting section.
	 */
	public function get_section() {
		return $this->section;
	}

	/**
	 * Returns the setting label.
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Returns the setting type.
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Returns whether the setting is required.
	 */
	public function is_required() {
		return $this->is_required;
	}

	/**
	 * Returns the default value of the setting.
	 */
	public function get_default() {
		return $this->default;
	}

	/**
	 * Returns the setting description.
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Returns the example value of the setting.
	 */
	public function get_example() {
		return $this->example;
	}

}
