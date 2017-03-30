<div class="container-fluid overview-container">
    <!-- Title in Overview is added in sidebar.php -->
    <!-- <header>
        <h5><?= $title ?></h5>
    </header> -->

	<header class="clearfix">
		<h5 id="current-port-label">DC1 Outlet Port</h5>
		<div class="bootstrap-switch-square outlet-switch hidden" id="dc1-switch">
		  	<input type="checkbox" data-toggle="switch" name="square-switch" id="dc1-port-switch" />
		</div>
		<div class="bootstrap-switch-square outlet-switch hidden" id="dc2-switch">
		  	<input type="checkbox" data-toggle="switch" name="square-switch" id="dc2-port-switch" />
		</div>
		<div class="bootstrap-switch-square outlet-switch hidden" id="dc3-switch">
		  	<input type="checkbox" data-toggle="switch" name="square-switch" id="dc3-port-switch" />
		</div>
		<div class="bootstrap-switch-square outlet-switch hidden" id="dc4-switch">
		  	<input type="checkbox" data-toggle="switch" name="square-switch" id="dc4-port-switch" />
		</div>
		<div class="bootstrap-switch-square outlet-switch hidden" id="ac1-switch">
		  	<input type="checkbox" data-toggle="switch" name="square-switch" id="ac1-port-switch" />
		</div>
		<div class="bootstrap-switch-square outlet-switch hidden" id="ac2-switch">
		  	<input type="checkbox" data-toggle="switch" name="square-switch" id="ac2-port-switch" />
		</div>
	</header>

	<div id="overview" class="disabled dc1-overview">
		<div id="overview-graph"></div>
	</div>

	<div id="overview-data">
		<div class="row">
			<div id="total-usage" class="digidata dc1-data col-md-3">
				<span class="digidata-label">Total Usage</span>
				<span class="digidata-value">0 kWh</span>
			</div>
			<div id="usage-reading" class="digidata dc1-data col-md-3">
				<span class="digidata-label">Usage Reading</span>
				<span class="digidata-value">0 W</span>
			</div>
			<div id="utilization" class="digidata dc1-data col-md-3">
				<span class="digidata-label">Utilization</span>
				<span class="digidata-value">0 %</span>
			</div>
			<div id="power-limit" class="digidata dc1-data col-md-3">
				<span class="digidata-label">Power Limit</span>
				<span class="digidata-value">200 W</span>
			</div>
		</div>
	</div>
	<!-- <img src="/img/main.png"> -->
</div>