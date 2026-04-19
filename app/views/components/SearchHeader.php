<?php
// SearchHeader Component
// Reusable search header with input, filter button, optional title and tabs

class SearchHeader {
	private $config;
	private $defaults = [
		'inputId' => 'searchInput',
		'placeholder' => 'Search...',
		'filterBtnId' => 'filterBtn',
		'searchBtnId' => 'searchBtn',
		'showTitle' => true,
		'title' => 'Search',
		'note' => '',
		'showTabs' => false,
		'tabs' => []
	];

	/**
	 * Constructor
	 * @param array $config Configuration array
	 */
	public function __construct($config = []) {
		$this->config = array_merge($this->defaults, $config);
	}

	/**
	 * Render the search header component
	 */
	public function render() {
		$inputId = htmlspecialchars($this->config['inputId'], ENT_QUOTES, 'UTF-8');
		$placeholder = htmlspecialchars($this->config['placeholder'], ENT_QUOTES, 'UTF-8');
		$filterBtnId = htmlspecialchars($this->config['filterBtnId'], ENT_QUOTES, 'UTF-8');
		$searchBtnId = htmlspecialchars($this->config['searchBtnId'], ENT_QUOTES, 'UTF-8');
		?>
		<div class="header-requests">
			<?php if ($this->config['showTitle']): ?>
				<h1><?= htmlspecialchars($this->config['title'], ENT_QUOTES, 'UTF-8') ?></h1>
				<?php if ($this->config['note']): ?>
					<p class="section-note"><?= htmlspecialchars($this->config['note'], ENT_QUOTES, 'UTF-8') ?></p>
				<?php endif; ?>
			<?php endif; ?>

			<div class="search-header">
				<div class="search-button">
					<input type="text" id="<?= $inputId ?>" placeholder="<?= $placeholder ?>">
					<button type="button" id="<?= $searchBtnId ?>" aria-label="Search">
						<i class="fa-solid fa-magnifying-glass"></i>
					</button>
				</div>
				<button class="filter" id="<?= $filterBtnId ?>" type="button">
					<i class="fa-solid fa-filter"></i>
					<span>Filter</span>
				</button>
			</div>

			<?php if ($this->config['showTabs'] && !empty($this->config['tabs'])): ?>
				<div class="container-changer">
					<?php foreach ($this->config['tabs'] as $index => $tab): ?>
						<?php
						$tabId = htmlspecialchars($tab['id'], ENT_QUOTES, 'UTF-8');
						$tabLabel = htmlspecialchars($tab['label'], ENT_QUOTES, 'UTF-8');
						$tabTarget = htmlspecialchars($tab['target'], ENT_QUOTES, 'UTF-8');
						$isActive = isset($tab['active']) && $tab['active'];
						$activeClass = $isActive ? 'active' : '';
						?>
						<div id="<?= $tabId ?>" class="buttons <?= $activeClass ?>" data-target="<?= $tabTarget ?>">
							<?= $tabLabel ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
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
}
