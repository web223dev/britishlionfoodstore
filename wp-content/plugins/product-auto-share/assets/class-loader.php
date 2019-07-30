<?php
class ced_pas_loader {
	function loader() {
		?>
		<div id="overlay-loader" class="hidesection">
			<svg width="110px" height="110px" xmlns="http://cedcommerce.com" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-ring-alt">
				<rect x="0" y="0" width="100" height="100" fill="none" class="bk"/>
				<circle cx="50" cy="50" r="40" stroke="#85a69e" fill="none" stroke-width="10" stroke-linecap="round"/>
				<circle cx="50" cy="50" r="40" stroke="#5cffd6" fill="none" stroke-width="6" stroke-linecap="round">
					<animate attributeName="stroke-dashoffset" dur="2s" repeatCount="indefinite" from="0" to="502"/>
					<animate attributeName="stroke-dasharray" dur="2s" repeatCount="indefinite" values="150.6 100.4;1 250;150.6 100.4"/>
				</circle>
			</svg>
		</div>
	<?php 
	}
}