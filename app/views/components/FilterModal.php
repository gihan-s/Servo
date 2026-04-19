<?php
/**
 * FilterModal Component
 * Reusable filter modal with extensible filter types
 */
class FilterModal {
	private $config;
	private $defaults = [
		'modalRootId' => 'filterRoot',
		'modalId' => 'filterModal',
		'closeId' => 'filterClose',
		'applyId' => 'filterApply',
		'clearId' => 'filterClear',
		'title' => 'Add Filters',
		'filters' => []
	];

	/**
	 * Constructor
	 * @param array $config Configuration array
	 * 
	 * Filter types supported:
	 * - checkbox: ['type' => 'checkbox', 'label' => 'Category', 'id' => 'categoryList', 'options' => []]
	 * - range: ['type' => 'range', 'label' => 'Budget', 'id' => 'budgetRange', 'min' => 0, 'max' => 10000]
	 * - select: ['type' => 'select', 'label' => 'Timeline', 'id' => 'timelineSelect', 'options' => [...]]
	 * - daterange: ['type' => 'daterange', 'label' => 'Date Posted', 'id' => 'dateRange']
	 * - radio: ['type' => 'radio', 'label' => 'Status', 'id' => 'statusRadio', 'options' => [...]]
	 */
	public function __construct($config = []) {
		$this->config = array_merge($this->defaults, $config);
	}

	/**
	 * Render the filter modal component
	 */
	public function render() {
		$modalRootId = htmlspecialchars($this->config['modalRootId'], ENT_QUOTES, 'UTF-8');
		$modalId = htmlspecialchars($this->config['modalId'], ENT_QUOTES, 'UTF-8');
		$closeId = htmlspecialchars($this->config['closeId'], ENT_QUOTES, 'UTF-8');
		$applyId = htmlspecialchars($this->config['applyId'], ENT_QUOTES, 'UTF-8');
		$clearId = htmlspecialchars($this->config['clearId'], ENT_QUOTES, 'UTF-8');
		$title = htmlspecialchars($this->config['title'], ENT_QUOTES, 'UTF-8');
		?>
		<div class="pop-up-section filter-pop-up deactive" id="<?= $modalRootId ?>">
			<div class="pop-up deactive" id="<?= $modalId ?>">
				<div class="pop-up-header">
					<div class="pop-up-title"><?= $title ?></div>
					<i class="fa-solid fa-xmark" id="<?= $closeId ?>"></i>
				</div>
				<hr>
				<div class="pop-up-content">
					<div class="search-filters">
						<?php foreach ($this->config['filters'] as $filter): ?>
							<?php $this->renderFilterItem($filter); ?>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="button-apply" style="display:flex; gap:10px; justify-content:flex-end; margin-top:12px;">
					<button type="button" class="btn-outline" id="<?= $clearId ?>">Clear</button>
					<button type="button" class="btn-primary" id="<?= $applyId ?>">Apply filters</button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render a single filter item based on type
	 * @param array $filter Filter configuration
	 */
	private function renderFilterItem($filter) {
		$type = $filter['type'] ?? 'checkbox';
		$label = htmlspecialchars($filter['label'] ?? 'Filter', ENT_QUOTES, 'UTF-8');
		$filterId = htmlspecialchars($filter['id'] ?? 'filter', ENT_QUOTES, 'UTF-8');
		?>
		<div class="filter-item">
			<div class="filter-title">
				<span><?= $label ?></span>
				<i class="fa-solid fa-chevron-down rotated"></i>
			</div>
			<?php
			switch ($type) {
				case 'checkbox':
					$this->renderCheckboxFilter($filterId, $filter);
					break;
				case 'range':
					$this->renderRangeFilter($filterId, $filter);
					break;
				case 'select':
					$this->renderSelectFilter($filterId, $filter);
					break;
				case 'daterange':
					$this->renderDateRangeFilter($filterId, $filter);
					break;
				case 'radio':
					$this->renderRadioFilter($filterId, $filter);
					break;
				default:
					$this->renderCustomFilter($filterId, $filter);
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render checkbox filter (dynamically populated or static options)
	 */
	private function renderCheckboxFilter($filterId, $filter) {
		$options = $filter['options'] ?? [];
		?>
		<ul class="filter-options checkboxes active" id="<?= $filterId ?>">
			<?php if (empty($options)): ?>
				<!-- Will be dynamically populated via JavaScript -->
			<?php else: ?>
				<?php foreach ($options as $index => $option): ?>
					<?php
					$value = htmlspecialchars($option['value'] ?? $option, ENT_QUOTES, 'UTF-8');
					$label = htmlspecialchars($option['label'] ?? $option, ENT_QUOTES, 'UTF-8');
					$checkboxId = "{$filterId}_{$index}";
					?>
					<li>
						<input type="checkbox" id="<?= $checkboxId ?>" value="<?= $value ?>">
						<label for="<?= $checkboxId ?>"><?= $label ?></label>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		<?php
	}

	/**
	 * Render range filter (for future implementation)
	 */
	private function renderRangeFilter($filterId, $filter) {
		$min = $filter['min'] ?? 0;
		$max = $filter['max'] ?? 100;
		?>
		<div class="filter-options active" id="<?= $filterId ?>">
			<input type="range" min="<?= $min ?>" max="<?= $max ?>" class="filter-range">
			<div class="filter-range-values">
				<span><?= $min ?></span> - <span><?= $max ?></span>
			</div>
		</div>
		<?php
	}

	/**
	 * Render select filter (for future implementation)
	 */
	private function renderSelectFilter($filterId, $filter) {
		$options = $filter['options'] ?? [];
		?>
		<div class="filter-options active" id="<?= $filterId ?>">
			<select class="filter-select">
				<option value="">Select...</option>
				<?php foreach ($options as $option): ?>
					<?php
					$value = htmlspecialchars($option['value'] ?? $option, ENT_QUOTES, 'UTF-8');
					$label = htmlspecialchars($option['label'] ?? $option, ENT_QUOTES, 'UTF-8');
					?>
					<option value="<?= $value ?>"><?= $label ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
	}

	/**
	 * Render date range filter (for future implementation)
	 */
	private function renderDateRangeFilter($filterId, $filter) {
		?>
		<div class="filter-options active" id="<?= $filterId ?>">
			<input type="date" class="filter-date-start" placeholder="Start Date">
			<input type="date" class="filter-date-end" placeholder="End Date">
		</div>
		<?php
	}

	/**
	 * Render radio filter (for future implementation)
	 */
	private function renderRadioFilter($filterId, $filter) {
		$options = $filter['options'] ?? [];
		?>
		<ul class="filter-options active" id="<?= $filterId ?>">
			<?php foreach ($options as $index => $option): ?>
				<?php
				$value = htmlspecialchars($option['value'] ?? $option, ENT_QUOTES, 'UTF-8');
				$label = htmlspecialchars($option['label'] ?? $option, ENT_QUOTES, 'UTF-8');
				$radioId = "{$filterId}_{$index}";
				?>
				<li>
					<input type="radio" name="<?= $filterId ?>" id="<?= $radioId ?>" value="<?= $value ?>">
					<label for="<?= $radioId ?>"><?= $label ?></label>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}

	/**
	 * Render custom filter type (extensibility point)
	 */
	private function renderCustomFilter($filterId, $filter) {
		?>
		<div class="filter-options active" id="<?= $filterId ?>">
			<!-- Custom filter implementation -->
			<p style="color: #64748b; font-size: 12px;">Custom filter type: <?= htmlspecialchars($filter['type'] ?? 'unknown', ENT_QUOTES, 'UTF-8') ?></p>
		</div>
		<?php
	}

	/**
	 * Get component configuration
	 * @return array Current configuration
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * Update configuration
	 * @param array $updates Configuration updates
	 */
	public function updateConfig($updates) {
		$this->config = array_merge($this->config, $updates);
	}

	/**
	 * Add a filter to the configuration
	 * @param array $filter Filter configuration
	 */
	public function addFilter($filter) {
		$this->config['filters'][] = $filter;
	}
}
